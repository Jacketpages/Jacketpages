<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

class BudgetsController extends AppController
{
	public $uses = array(
		'Budget',
		'BudgetSubmitState',
		'LineItemCategory',
		'BudgetLineItem',
		'Fundraiser',
		'Expense',
		'Asset',
		'Liability',
		'MemberContribution'
	);
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

	public function index()
	{
		if (!($this -> isSGAExec()))
			$this -> redirect($this -> referer());

		$this -> paginate = array(
			'conditions' => array('fiscal_year' => '20' . ($this -> getFiscalYear() + 2)),
			'fields' => array(
				'Organization.id',
				'Organization.name',
				'Budget.id',
				'Budget.state',
				'Budget.last_mod_date'
			),
			'recursive' => 0
		);
		$this -> set('budgets', $this -> paginate('Budget'));
	}

	public function submit($org_id = null)
	{
		//page permissions
		if (!($this -> isOfficer($org_id) || $this -> isSGAExec()))
			$this -> redirect($this -> referer());
		if ($this -> Budget -> find('count', array('conditions' => array(
					'id' => $this -> getBudgetId($org_id),
					'state' => 'Submitted'
				))) && !$this -> isSGAExec())
			$this -> redirect(array(
				'action' => 'summary',
				$org_id
			));

		if ($org_id == null)
		{
			$this -> Session -> setFlash('Please select your organization to create a budget.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}
		// This check may keep multiple budgets from being created, but it is UNTESTED.
		// if ($this -> Budget -> find('count', array('conditions' => array(
		// 'id' => $this -> getBudgetId($org_id),
		// 'state' => 'Submitted'
		// ))) && ($this -> request -> data['Budget']['id'] == null || $this -> request
		// -> data['Budget']['id'] == ''))
		// {
		// $this -> Session -> setFlash('It appears that someone else may have already
		// created a budget.');
		// $this -> redirect($this -> referer());
		// }

		if ($this -> request -> is('post') || $this -> request -> is('put'))
		{
			$this -> Budget -> data = $this -> request -> data;
			$this -> Budget -> set('state', 'Created');
			$this -> Budget -> set('last_mod_by', $this -> Session -> read('User.id'));
			if (strcmp($this -> Budget -> data['Budget']['treasurer_id'], '') == 0 || strcmp($this -> Budget -> data['Budget']['president_id'], '') == 0 || strcmp($this -> Budget -> data['Budget']['advisor_id'], '') == 0)
			{
				$this -> Session -> setFlash('You are missing officer information.');
				$this -> redirect(array(
					'controller' => 'budgets',
					'action' => 'submit',
					$org_id
				));
			}
			if ($this -> Budget -> save($this -> request -> data))
			{
				$this -> loadModel('BudgetSubmitState');
				$this -> BudgetSubmitState -> save(array('BudgetSubmitState' => array(
						'id' => $this -> getBudgetId($org_id),
						'state_1' => 1
					)));
				$this -> updateLastModBy($this -> getBudgetId($org_id));
				if (isset($this -> request -> data['redirect']) && strcmp($this -> request -> data['redirect'], 'Save and Continue') == 0)
					$this -> redirect(array(
						'controller' => 'budget_line_items',
						'action' => 'edit',
						$org_id
					));
			}
			else
			{
				$this -> Session -> setFlash('Unable to save budget.');
			}
		}
		$this -> set('org_id', $org_id);
		$this -> set('budgetSubmitted', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $this -> getBudgetId($org_id),
				'state' => 'Submitted'
			))));
		$this -> loadModel('Membership');
		$this -> loadModel('Organization');
		$this -> Organization -> id = $org_id;
		$organization = $this -> Organization -> read();

		$this -> set('organization', $organization);
		$charter_date = DateTime::createFromFormat("Y-m-d", $organization['Organization']['charter_date']);
		$yearsActive = date('Y') - $charter_date -> format("Y");
		if ($yearsActive > 2000)
			$yearsActive = 0;
		$this -> set('yearsActive', $yearsActive);
		$this -> set('orgName', $organization['Organization']['name']);
		$this -> set('tier', $this -> roman_numerals($organization['Organization']['tier']));

		$db = ConnectionManager::getDataSource('default');
		$this -> set('member_count', $this -> Membership -> find('count', array(
			'fields' => 'DISTINCT Membership.user_id',
			'conditions' => array(
				'org_id' => $org_id,
				'Membership.status' => 'Active',
				'OR' => array(
					$db -> expression('Membership.end_date >= NOW()'),
					'Membership.end_date' => null
				)
			),
			'recursive' => -1,
		)));
		$president = $this -> getMembers($org_id, array('President'), true);
		$treasurer = $this -> getMembers($org_id, array('Treasurer'), true);
		$advisor = $this -> getMembers($org_id, array('Advisor'), true);

		$this -> set('president', $president);
		$this -> set('treasurer', $treasurer);
		$this -> set('advisor', $advisor);
		$categories = $this -> LineItemCategory -> find('all');
		$this -> set('category_names', Hash::extract($categories, '{n}.LineItemCategory.name'));
		$this -> set('category_descriptions', Hash::extract($categories, '{n}.LineItemCategory.description'));
		$this -> set('budgetLineItems', array());
		$this -> request -> data = $this -> Budget -> findByOrgIdAndFiscalYear($org_id, '20' . ($this -> getFiscalYear() + 2));
		//$this ->getStudentType('sroca3');

	}

	private function getStudentType($gtid)
	{
		$json = file_get_contents('http://m2.cip.gatech.edu/proxy/iam-dev01.iam.gatech.edu/directory/people.json?uid=' . $gtid);
		$info = json_decode($json);
	}

	private static function removeBlanks()
	{
		return true;
	}

	public function fundraising($org_id = null)
	{
		//page permissions
		if (!($this -> isOfficer($org_id) || $this -> isSGAExec()))
			$this -> redirect($this -> referer());
		if ($this -> Budget -> find('count', array('conditions' => array(
					'id' => $this -> getBudgetId($org_id),
					'state' => 'Submitted'
				))) && !$this -> isSGAExec())
			$this -> redirect(array(
				'action' => 'summary',
				$org_id
			));
		if ($org_id == null)
		{
			$this -> Session -> setFlash('Please select your organization to create a budget.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}

		$this -> set('org_id', $org_id);
		$this -> set('budgetSubmitted', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $this -> getBudgetId($org_id),
				'state' => 'Submitted'
			))));
		$this -> loadModel('Fundraiser');
		$this -> loadModel('Dues');
		$budgetId = $this -> Budget -> field('id', array(
			'org_id' => $org_id,
			'fiscal_year' => '20' . $this -> getFiscalYear() + 2
		));
		$this -> set('budget_id', $budgetId);
		if ($this -> request -> is('post') || $this -> request -> is('put'))
		{
			$newIds = Hash::extract($this -> request -> data, '{s}.{n}.Fundraiser.id');
			$oldIds = Hash::extract($this -> Fundraiser -> findAllByBudgetId($budgetId), '{n}.Fundraiser.id');
			foreach (Hash::diff($oldIds, $newIds) as $id)
				$this -> Fundraiser -> delete($id);
			$types = array(
				'Executed',
				'Expected',
				'Planned'
			);
			foreach ($types as $type)
			{
				$items = $this -> request -> data[$type];
				unset($this -> request -> data[$type]);
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
			if ($this -> Dues -> saveAll($this -> request -> data))
			{

			}
			$this -> loadModel('BudgetSubmitState');
			$this -> BudgetSubmitState -> save(array('BudgetSubmitState' => array(
					'id' => $this -> getBudgetId($org_id),
					'state_3' => 1
				)));
			$this -> updateLastModBy($this -> getBudgetId($org_id));
			if (isset($this -> request -> data['redirect']) && strcmp($this -> request -> data['redirect'], 'Save and Continue') == 0)
				$this -> redirect(array(
					'controller' => 'budgets',
					'action' => 'expenses',
					$org_id
				));
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
	}

	public function expenses($org_id = null)
	{
		//page permissions
		if (!($this -> isOfficer($org_id) || $this -> isSGAExec()))
			$this -> redirect($this -> referer());
		if ($this -> Budget -> find('count', array('conditions' => array(
					'id' => $this -> getBudgetId($org_id),
					'state' => 'Submitted'
				))) && !$this -> isSGAExec())
			$this -> redirect(array(
				'action' => 'summary',
				$org_id
			));
		$redirect = false;
		if (isset($this -> request -> data['redirect']) && strcmp($this -> request -> data['redirect'], 'Save and Continue') == 0)
			$redirect = true;
		if ($org_id == null)
		{
			$this -> Session -> setFlash('Please select your organization to create a budget.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}

		$this -> set('org_id', $org_id);
		$this -> loadModel('Expense');
		$budgetId = $this -> getBudgetId($org_id);
		$this -> set('budgetSubmitted', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $this -> getBudgetId($org_id),
				'state' => 'Submitted'
			))));
		if ($this -> request -> is('post'))
		{
			$expenseIds = Hash::extract($this -> Expense -> findAllByBudgetId($budgetId), '{n}.Expense.id');
			$newExpenseIds = Hash::extract($this -> request -> data, '{n}.Expense.id');
			foreach ($expenseIds as $id)
			{
				if (!in_array($id, $newExpenseIds))
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
				$this -> loadModel('BudgetSubmitState');
				$this -> BudgetSubmitState -> save(array('BudgetSubmitState' => array(
						'id' => $this -> getBudgetId($org_id),
						'state_4' => 1
					)));
				$this -> updateLastModBy($this -> getBudgetId($org_id));
			}
			if ($redirect)
				$this -> redirect(array(
					'controller' => 'budgets',
					'action' => 'assets_and_liabilities',
					$org_id
				));
		}
		$expenses = $this -> Expense -> findAllByBudgetId($budgetId);
		$this -> set('expenses', $expenses);
	}

	public function assets_and_liabilities($org_id = null)
	{
		//page permissions
		if (!($this -> isOfficer($org_id) || $this -> isSGAExec()))
			$this -> redirect($this -> referer());
		if ($this -> Budget -> find('count', array('conditions' => array(
					'id' => $this -> getBudgetId($org_id),
					'state' => 'Submitted'
				))) && !$this -> isSGAExec())
			$this -> redirect(array(
				'action' => 'summary',
				$org_id
			));
		if ($org_id == null)
		{
			$this -> Session -> setFlash('Please select your organization to create a budget.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}

		$this -> set('org_id', $org_id);
		$this -> set('budgetSubmitted', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $this -> getBudgetId($org_id),
				'state' => 'Submitted'
			))));
		if (isset($this -> request -> data['redirect']) && strcmp($this -> request -> data['redirect'], 'Save and Continue') == 0)
			$redirect = true;
		else
			$redirect = false;
		$this -> request -> data = Hash::extract($this -> request -> data, 'Budget');
		$assets = Hash::remove($this -> request -> data, '{n}.Liability');
		$liabilities = Hash::remove($this -> request -> data, '{n}.Asset');
		$budgetId = $this -> getBudgetId($org_id);
		if ($this -> request -> is('post'))
		{
			$this -> assets($assets, $budgetId);
			$this -> liabilities($liabilities, $budgetId);
			$this -> loadModel('BudgetSubmitState');
			$this -> BudgetSubmitState -> save(array('BudgetSubmitState' => array(
					'id' => $this -> getBudgetId($org_id),
					'state_5' => 1
				)));
			$this -> updateLastModBy($this -> getBudgetId($org_id));
			if ($redirect)
				$this -> redirect(array(
					'controller' => 'budgets',
					'action' => 'member_contributions',
					$org_id
				));
		}
		$this -> set('assets', $this -> Asset -> findAllByBudgetId($budgetId));
		$this -> set('liabilities', $this -> Liability -> findAllByBudgetId($budgetId));
	}

	private function assets($assets, $budgetId)
	{
		$assetIds = Hash::extract($this -> Asset -> findAllByBudgetId($budgetId), '{n}.Asset.id');
		$newAssetIds = Hash::extract($assets, '{n}.Asset.id');
		foreach ($assetIds as $id)
		{
			if (!in_array($id, $newAssetIds))
			{
				$this -> Asset -> delete($id);
			}
		}
		$limit = count($assets);
		for ($i = 0; $i < $limit; $i++)
		{
			$assets[$i]['Asset']['budget_id'] = $budgetId;
			if (strcmp($assets[$i]['Asset']['item'], '') == 0)
			{
				unset($assets[$i]);
			}
		}
		if ($this -> Asset -> saveMany($assets))
		{

		}
	}

	private function liabilities($liabilities, $budgetId)
	{
		$liabilityIds = Hash::extract($this -> Liability -> findAllByBudgetId($budgetId), '{n}.Liability.id');
		$newLiabilityIds = Hash::extract($liabilities, '{n}.Liability.id');
		foreach ($liabilityIds as $id)
		{
			if (!in_array($id, $newLiabilityIds))
			{
				$this -> Liability -> delete($id);
			}
		}
		$limit = count($liabilities);
		for ($i = 0; $i < $limit; $i++)
		{
			if (count($liabilities[$i]) == 0)
			{
				unset($liabilities[$i]);
			}
			else if (strcmp($liabilities[$i]['Liability']['item'], '') == 0)
			{
				unset($liabilities[$i]);
			}
			else
			{
				$liabilities[$i]['Liability']['budget_id'] = $budgetId;
			}
		}
		if ($this -> Liability -> saveMany($liabilities))
		{

		}
	}

	public function member_contributions($org_id = null)
	{
		//page permissions
		if (!($this -> isOfficer($org_id) || $this -> isSGAExec()))
			$this -> redirect($this -> referer());
		if ($this -> Budget -> find('count', array('conditions' => array(
					'id' => $this -> getBudgetId($org_id),
					'state' => 'Submitted'
				))) && !$this -> isSGAExec())
			$this -> redirect(array(
				'action' => 'summary',
				$org_id
			));
		if (isset($this -> request -> data['redirect']) && strcmp($this -> request -> data['redirect'], 'Save and Continue') == 0)
			$redirect = true;
		else
			$redirect = false;
		unset($this -> request -> data['redirect']);
		if ($org_id == null)
		{
			$this -> Session -> setFlash('Please select your organization to create a budget.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}

		$this -> set('budgetSubmitted', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $this -> getBudgetId($org_id),
				'state' => 'Submitted'
			))));
		$this -> loadModel('MemberContribution');
		$budgetId = $this -> getBudgetId($org_id);

		if ($this -> request -> is('post'))
		{
			$memberContributionIds = Hash::extract($this -> MemberContribution -> findAllByBudgetId($budgetId), '{n}.MemberContribution.id');
			$newMemberContributionIds = Hash::extract($this -> request -> data, '{n}.MemberContribution.id');
			foreach ($memberContributionIds as $id)
			{
				if (!in_array($id, $newMemberContributionIds))
				{
					$this -> MemberContribution -> delete($id);
				}
			}
			for ($i = 0; $i < count($this -> request -> data); $i++)
			{
				$this -> request -> data[$i]['MemberContribution']['budget_id'] = $budgetId;
				if (strcmp($this -> request -> data[$i]['MemberContribution']['item'], '') == 0)
				{
					unset($this -> request -> data[$i]);
				}
			}
			if ($this -> MemberContribution -> saveMany($this -> request -> data))
			{
				$this -> loadModel('BudgetSubmitState');
				$this -> BudgetSubmitState -> save(array('BudgetSubmitState' => array(
						'id' => $this -> getBudgetId($org_id),
						'state_6' => 1
					)));
				$this -> updateLastModBy($budgetId);
				if ($redirect)
					$this -> redirect(array(
						'controller' => 'budgets',
						'action' => 'summary',
						$org_id
					));
			}

		}
		$this -> set('memberContributions', $this -> MemberContribution -> findAllByBudgetId($budgetId));
		$this -> set('org_id', $org_id);
	}

	public function summary($org_id = null)
	{
		if (!($this -> isOfficer($org_id) || $this -> isSGAExec()))
			$this -> redirect($this -> referer());
		if ($org_id == null)
		{
			$this -> Session -> setFlash('Please select your organization to create a budget.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}

		$budgetId = $this -> getBudgetId($org_id);
		$this -> set('state', $this -> BudgetSubmitState -> findById($this -> getBudgetId($org_id)));
		$this -> set('org_id', $org_id);
		$this -> set('budgetSubmitted', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $budgetId,
				'state' => 'Submitted'
			))));
		$totals = array();
		$totals[] = 'N/A';
		$totals[] = '$' . $this -> BudgetLineItem -> field('SUM(amount)', array('budget_id' => $budgetId));
		$totals[] = '$' . $this -> Fundraiser -> field('SUM(revenue)', array('budget_id' => $budgetId));
		$totals[] = '$' . $this -> Expense -> field('SUM(amount)', array('budget_id' => $budgetId));
		$totals[] = '$' . ($this -> Asset -> field('SUM(amount)', array('budget_id' => $budgetId)) - $this -> Liability -> field('SUM(amount)', array('budget_id' => $budgetId)));
		$totals[] = '$' . $this -> MemberContribution -> field('SUM(amount)', array('budget_id' => $budgetId));
		$this -> set('totals', $totals);
		$this -> set('last_updated', $this -> Budget -> field('last_mod_date', array('id' => $budgetId)));
		$this -> loadModel('User');
		$this -> set('last_updated_by', $this -> User -> field("CONCAT(first_name,' ',last_name)", array('id' => $this -> Budget -> field('last_mod_by', array('id' => $budgetId)))));
		if ($this -> request -> is('post'))
		{
			$this -> Budget -> id = $budgetId;
			if ($this -> Budget -> saveField('state', 'Submitted'))
			{
				$this -> redirect(array(
					'controller' => 'organizations',
					'action' => 'view',
					$org_id
				));
			}
		}
	}

	public function view($budget_id)
	{
		$budget = $this -> Budget -> find('first', array(
			'conditions' => array('Budget.id' => $budget_id),
			'recursive' => 1
		));
		debug($budget);
		$this -> loadModel('Organization');
		$this -> set('organization', $this -> Organization -> findById($budget['Budget']['org_id']));
	}

	private function updateLastModBy($budget_id)
	{
		$this -> Budget -> id = $budget_id;
		$this -> Budget -> saveField('last_mod_by', $this -> Session -> read('User.id'));
		$this -> Budget -> saveField('last_mod_date', date('Y-m-d H:i:s'));
	}

}
?>