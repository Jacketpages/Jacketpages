<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

class BudgetsController extends AppController
{
	public $helpers = array(
		'Form',
		'Html',
		'Session',
		'Number',
		'Time'
	);

	public $components = array(
		'RequestHandler',
		'Session'
	);

	public function index($org_id = null)
	{
		if ($org_id != null)
		{
			$this -> set('orgId', $org_id);
		}
		$this -> set('budgets', $this -> paginate('Budget'));
	}

	public function submit($org_id = null, $redirect = true)
	{
		if ($this -> request -> is('get'))
		{
			$this -> loadModel('Membership');
			$this -> loadModel('Organization');
			$this -> Organization -> id = $org_id;
			$organization = $this -> Organization -> read();

			$this -> set('organization', $organization);
			$charter_date = DateTime::createFromFormat("Y-m-d", $organization['Organization']['charter_date']);
			$this -> set('yearsActive', date('Y') - $charter_date -> format("Y"));
			$this -> set('orgName', $organization['Organization']['name']);
			$this -> set('tier', $this -> roman_numerals($organization['Organization']['tier']));

			//$members = $this -> Membership -> find('all', array('conditions' =>
			// array('Organization.id' => $org_id,'Membership.role !=' => 'Member','end_date'
			// => '0000-00-00')));
			$this -> set('member_count', $this -> Membership -> find('count', array('conditions' => array(
					'Organization.id' => $org_id,
					'Membership.end_date' => null
				))));
			$president = $this -> Membership -> findByOrgIdAndRoleAndEndDate($org_id, 'President', null);
			$treasurer = $this -> Membership -> findByOrgIdAndRoleAndEndDate($org_id, 'Treasurer', null);
			$advisor = $this -> Membership -> findByOrgIdAndRoleAndEndDate($org_id, 'Advisor', null);

			$this -> set('president', $president);
			$this -> set('treasurer', $treasurer);
			$this -> set('advisor', $advisor);
			$this -> loadModel('LineItemCategory');
			$categories = $this -> LineItemCategory -> find('all');
			$this -> set('category_names', Hash::extract($categories, '{n}.LineItemCategory.name'));
			$this -> set('category_descriptions', Hash::extract($categories, '{n}.LineItemCategory.description'));
			$this -> set('budgetLineItems', array());
			debug($this -> getFiscalYear());
			$this -> request -> data = $this -> Budget -> findByOrgIdAndFiscalYear($org_id, '20' . ($this -> getFiscalYear() + 2));
			//$this ->getStudentType('sroca3');
		}
		else
		{
			debug($this -> request -> data);
			$this -> Budget -> data = $this -> request -> data;
			$this -> Budget -> set('status', 'Submitted');
			$this -> Budget -> set('last_mod_by', $this -> Session -> read('User.id'));
			debug($this -> Budget -> data);
			if ($this -> Budget -> save($this -> request -> data))
			{
				if ($redirect)
					$this -> redirect(array(
						'controller' => 'budgetlineitems',
						'action' => 'edit',
						$org_id
					));
			}
			else
			{
				$this -> Session -> setFlash('Unable to save budget.');
			}
		}
	}

	private function getStudentType($gtid)
	{
		$json = file_get_contents('http://m2.cip.gatech.edu/proxy/iam-dev01.iam.gatech.edu/directory/people.json?uid=' . $gtid);
		$info = json_decode($json);
		debug($info);
	}

	private static function removeBlanks()
	{
		debug($items);
		return true;
	}

	public function fundraising($org_id)
	{
		debug($this -> request -> data);
		$this -> loadModel('Fundraiser');
		$this -> loadModel('Dues');
		$budgetId = $this -> Budget -> field('id', array(
			'org_id' => $org_id,
			'fiscal_year' => '20' . $this -> getFiscalYear() + 2
		));
		$this -> set('budget_id', $budgetId);
		if ($this -> request -> is('post'))
		{
			$types = array(
				'Executed',
				'Expected',
				'Planned'
			);
			foreach ($types as $type)
			{
				$items = $this -> request -> data[$type];
				unset($this -> request -> data[$type]);
				//debug(array_filter($items, "BudgetsController::removeBlanks"));
				foreach ($items as $item)
				{
					if (strcmp($item['Fundraiser']['activity'], '') != 0)
					{
						$item['Fundraiser']['budget_id'] = $budgetId;
						$this -> Fundraiser -> create();
						$this -> Fundraiser -> save($item);
					}
				}
			}
			debug($this -> request -> data);
			if ($this -> Dues -> saveAll($this -> request -> data))
			{

			}
		}
		$executed = $this -> Fundraiser -> findAllByBudgetIdAndType($budgetId, 'Executed');
		$expected = $this -> Fundraiser -> findAllByBudgetIdAndType($budgetId, 'Expected');
		$planned = $this -> Fundraiser -> findAllByBudgetIdAndType($budgetId, 'Planned');
		$this -> set('fundraisers', array(
			'Executed' => $executed,
			'Expected' => $expected,
			'Planned' => $planned
		));
		$dues = $this -> Dues -> findAllByBudgetId($budgetId);
		$this -> set('dues', $dues);
	}

	private function getBudgetId($org_id)
	{
		return $budgetId = $this -> Budget -> field('id', array(
			'org_id' => $org_id,
			'fiscal_year' => '20' . $this -> getFiscalYear() + 2
		));
		;
	}

	public function expenses($org_id)
	{
		$this -> loadModel('Expense');
		$budgetId = $this -> getBudgetId($org_id);

		if ($this -> request -> is('post'))
		{
			$expenseIds = Hash::extract($this -> Expense -> findAllByBudgetId($budgetId), '{n}.Expense.id');
			$newExpenseIds = Hash::extract($this -> request -> data, '{n}.Expense.id');
			foreach($expenseIds as $id)
			{
				if(!in_array($id, $newExpenseIds))
				{
					$this -> Expense -> delete($id);
				}
			}
			for ($i = 0; $i < count($this -> request -> data); $i++)
			{
				$this -> request -> data[$i]['Expense']['budget_id'] = $budgetId;
				if (strcmp($this -> request -> data[$i]['Expense']['item'], '') == 0)
				{
					unset($this -> request -> data[$i]);
				}
			}
			if ($this -> Expense -> saveMany($this -> request -> data))
			{

			}
		}
		$expenses = $this -> Expense -> findAllByBudgetId($budgetId);
		$this -> set('expenses', $expenses);
	}

	public function assets_and_liabilities()
	{
		
	}

	public function member_contributions()
	{
		
	}

	public function summary()
	{

	}

	public function view()
	{

	}

}
?>
