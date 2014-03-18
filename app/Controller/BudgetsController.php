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
		'Time',
		'Excel'
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
			'conditions' => array('Budget.fiscal_year' => '20' . ($this -> getFiscalYear() + 2)),
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
		$redirect = false;
		if (isset($this -> request -> data['redirect']) && strcmp($this -> request -> data['redirect'], 'Save and Continue') == 0)
			$redirect = true;

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
		$this -> set('budgetCreated', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $this -> getBudgetId($org_id),
				'state' => 'Created'
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
		//can only get here if budget has been started
		if (!($this -> getBudgetId($org_id)) && !$this -> isSGAExec())
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
		$this -> set('budgetCreated', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $this -> getBudgetId($org_id),
				'state' => 'Created'
			))));
		$this -> loadModel('Fundraiser');
		$this -> loadModel('Dues');
		$budgetId = $this -> Budget -> field('id', array(
			'org_id' => $org_id,
			'Budget.fiscal_year' => '20' . $this -> getFiscalYear() + 2
		));
		$this -> set('budget_id', $budgetId);
		if ($this -> request -> is('post') || $this -> request -> is('put'))
		{
			// MRE: check here if it's a redirect and remove from the data
			// since leaving 'redirect' => 'Save and Continue' in post data
			// kills cake's saveall command
			$redirect = false;
			if (isset($this -> request -> data['redirect']) && strcmp($this -> request -> data['redirect'], 'Save and Continue') == 0)
				$redirect = true;
			unset($this -> request -> data['redirect']);

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
			if ($redirect)
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

		// for breadcrumb
		$this -> loadModel('Organization');
		$this -> Organization -> id = $org_id;
		$organization = $this -> Organization -> read();
		$this -> set('organization', $organization);
	}

	private function getBudgetId($org_id)
	{
		return $budgetId = $this -> Budget -> field('id', array(
			'org_id' => $org_id,
			'Budget.fiscal_year' => '20' . $this -> getFiscalYear() + 2
		));
	}

	public function expenses($org_id = null)
	{
		if ($org_id == null)
		{
			$this -> Session -> setFlash('Please select your organization to create a budget.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}
		//page permissions
		if (!($this -> isOfficer($org_id) || $this -> isSGAExec()))
			$this -> redirect($this -> referer());
		//can only get here if budget has been started
		if (!($this -> getBudgetId($org_id)) && !$this -> isSGAExec())
			$this -> redirect(array(
				'action' => 'summary',
				$org_id
			));
		$redirect = false;
		if (isset($this -> request -> data['redirect']) && strcmp($this -> request -> data['redirect'], 'Save and Continue') == 0)
			$redirect = true;

		$this -> set('org_id', $org_id);
		$this -> loadModel('Expense');
		$budgetId = $this -> getBudgetId($org_id);
		$this -> set('budgetSubmitted', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $this -> getBudgetId($org_id),
				'state' => 'Submitted'
			))));
		$this -> set('budgetCreated', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $this -> getBudgetId($org_id),
				'state' => 'Created'
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
			$limit = count($this -> request -> data);
			for ($i = 0; $i < $limit; $i++)
			{
				if (count($this -> request -> data[$i]) == 0)
				{
					unset($this -> request -> data[$i]);
				}
				else if (strcmp($this -> request -> data[$i]['Expense']['item'], '') == 0)
				{
					unset($this -> request -> data[$i]);
				}
				else
				{
					$this -> request -> data[$i]['Expense']['budget_id'] = $budgetId;
				}
			}
			// MRE: update even if nothing was submited
			$this -> Expense -> saveMany($this -> request -> data);
			$this -> loadModel('BudgetSubmitState');
			$this -> BudgetSubmitState -> save(array('BudgetSubmitState' => array(
					'id' => $this -> getBudgetId($org_id),
					'state_4' => 1
				)));
			$this -> updateLastModBy($this -> getBudgetId($org_id));
			if ($redirect)
				$this -> redirect(array(
					'controller' => 'budgets',
					'action' => 'assets_and_liabilities',
					$org_id
				));
		}
		$expenses = $this -> Expense -> findAllByBudgetId($budgetId);
		$this -> set('expenses', $expenses);

		// for breadcrumb
		$this -> loadModel('Organization');
		$this -> Organization -> id = $org_id;
		$organization = $this -> Organization -> read();
		$this -> set('organization', $organization);
	}

	public function assets_and_liabilities($org_id = null)
	{
		if ($org_id == null)
		{
			$this -> Session -> setFlash('Please select your organization to create a budget.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}
		//page permissions
		if (!($this -> isOfficer($org_id) || $this -> isSGAExec()))
			$this -> redirect($this -> referer());
		//can only get here if budget has been started
		if (!($this -> getBudgetId($org_id)) && !$this -> isSGAExec())
			$this -> redirect(array(
				'action' => 'summary',
				$org_id
			));

		$this -> set('org_id', $org_id);
		$this -> set('budgetSubmitted', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $this -> getBudgetId($org_id),
				'state' => 'Submitted'
			))));
		$this -> set('budgetCreated', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $this -> getBudgetId($org_id),
				'state' => 'Created'
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

		// for breadcrumb
		$this -> loadModel('Organization');
		$this -> Organization -> id = $org_id;
		$organization = $this -> Organization -> read();
		$this -> set('organization', $organization);
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

		if ($org_id == null)
		{
			$this -> Session -> setFlash('Please select your organization to create a budget.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}
		//page permissions
		if (!($this -> isOfficer($org_id) || $this -> isSGAExec()))
			$this -> redirect($this -> referer());
		//can only get here if budget has been started
		if (!($this -> getBudgetId($org_id)) && !$this -> isSGAExec())
			$this -> redirect(array(
				'action' => 'summary',
				$org_id
			));

		if (isset($this -> request -> data['redirect']) && strcmp($this -> request -> data['redirect'], 'Save and Continue') == 0)
			$redirect = true;
		else
			$redirect = false;
		unset($this -> request -> data['redirect']);

		$this -> set('budgetSubmitted', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $this -> getBudgetId($org_id),
				'state' => 'Submitted'
			))));
		$this -> set('budgetCreated', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $this -> getBudgetId($org_id),
				'state' => 'Created'
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
			$limit = count($this -> request -> data);
			if ($limit > 0)
			{
				for ($i = 0; $i < $limit; $i++)
				{
					$this -> request -> data[$i]['MemberContribution']['budget_id'] = $budgetId;
					if (strcmp($this -> request -> data[$i]['MemberContribution']['item'], '') == 0)
					{
						unset($this -> request -> data[$i]);
					}
				}
			}
			// MRE: allow blanks to be saved
			if ($this -> MemberContribution -> saveMany($this -> request -> data))
			{

			}
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
		$this -> set('memberContributions', $this -> MemberContribution -> findAllByBudgetId($budgetId));
		$this -> set('org_id', $org_id);

		// for breadcrumb
		$this -> loadModel('Organization');
		$this -> Organization -> id = $org_id;
		$organization = $this -> Organization -> read();
		$this -> set('organization', $organization);
	}

	public function summary($org_id = null)
	{
		if ($org_id == null)
		{
			$this -> Session -> setFlash('Please select your organization to create a budget.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}

		if (!($this -> isOfficer($org_id) || $this -> isSGAExec()))
			$this -> redirect($this -> referer());

		$budgetId = $this -> getBudgetId($org_id);
		//can only get here if budget has been started
		if (!$budgetId && !$this -> isSGAExec())
			$this -> redirect(array(
				'action' => 'submit',
				$org_id
			));

		$this -> set('state', $this -> BudgetSubmitState -> findById($this -> getBudgetId($org_id)));
		$this -> set('org_id', $org_id);
		$this -> set('budgetSubmitted', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $budgetId,
				'state' => 'Submitted'
			))));
		$this -> set('budgetCreated', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $budgetId,
				'state' => 'Created'
			))));
		$totals = array();
		$totals[] = 'N/A';
		$totals[] = '$' . $this -> BudgetLineItem -> field('SUM(amount)', array('budget_id' => $budgetId));
		$totals[] = '$' . $this -> Fundraiser -> field('SUM(revenue)', array(
			'budget_id' => $budgetId,
			'type' => 'Planned'
		));
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

		// for breadcrumb
		$this -> loadModel('Organization');
		$this -> Organization -> id = $org_id;
		$organization = $this -> Organization -> read();
		$this -> set('organization', $organization);
	}

	public function view()
	{
		if ($this -> isSGAExec())
		{
			$goToNext = false;
			if (isset($this -> request -> data['redirect']))
			{
				$goToNext = true;
				unset($this -> request -> data['redirect']);
			}
			// Start - Set default search settings
			if (count($this -> request -> data) == 0)
			{
				$this -> request -> data = array('Budget' => array(
						'fiscal_year' => '20' . ($this -> getFiscalYear() + 2),
						'tier' => 2,
						'org_id' => 27855
					));
			}
			// End - Set default search settings
			$fiscal_year = $this -> request -> data['Budget']['fiscal_year'];
			$org_id = $this -> request -> data['Budget']['org_id'];
			$tier = $this -> request -> data['Budget']['tier'];
			if (!isset($this -> request -> data['Budget']['state']))
			{
				$this -> request -> data['Budget']['state'] = 'JFC';
			}
			$this -> set('state', $this -> request -> data['Budget']['state']);
			if ($this -> request -> is('post') && strcmp($this -> request -> data['Budget']['state'], '') != 0)
			{
				if (strcmp($this -> Session -> read('Budget.state'), $this -> request -> data['Budget']['state']) == 0 && $this -> Session -> read('org_id') == $org_id)
				{
					$budgetId = $this -> Budget -> field('id', array(
						'org_id' => $org_id,
						'Budget.fiscal_year' => $fiscal_year
					));
					$BLIs = $this -> BudgetLineItem -> find('all', array(
						'conditions' => array(
							'budget_id' => $budgetId,
							'fiscal_year' => $fiscal_year,
							'BudgetLineItem.state' => 'Submitted'
						),
						'fields' => array(
							'BudgetLineItem.id',
							'BudgetLineItem.name'
						)
					));
					$existingBLIs = Hash::extract($BLIs, '{n}.BudgetLineItem.name');
					$newBLIs = Hash::extract($this -> request -> data, 'BudgetLineItem.{n}.name');

					$diff = array_diff($existingBLIs, $newBLIs);
					foreach ($diff as $missingName)
					{
						// TODO change to be by budget id and line item name.
						$this -> BudgetLineItem -> deleteAll(array(
							'BudgetLineItem.name' => $missingName,
							'original' => 0,
							'BudgetLineItem.budget_id' => $budgetId
						));
					}

					$difference = array_keys(array_diff($newBLIs, $existingBLIs));
					$states = array(
						'Submitted' => 'Submitted',
						'JFC' => 'JFC',
						'UHRC' => 'UHRC',
						'GSSC' => 'GSSC',
						'UHR' => 'UHR',
						'GSS' => 'GSS',
						'CONF' => 'CONF',
						'Final' => 'Final'
					);
					for ($pos = 0; $pos < count($difference); $pos++)
					{
						$line = $this -> request -> data['BudgetLineItem'][$difference[$pos]];
						$line['line_number'] = $difference[$pos] + 1;
						$line['budget_id'] = $budgetId;
						$line['amount'] = 0;
						foreach ($states as $state)
						{
							$line['state'] = $state;
							$this -> BudgetLineItem -> save($line);
						}
					}
					// Remember that the line item ids refer to that specific state. not the
					// submitted state.
					for ($i = 0; $i < count($this -> request -> data['BudgetLineItem']); $i++)
					{
						$this -> request -> data['BudgetLineItem'][$i]['budget_id'] = $budgetId;
						//$this -> request -> data['BudgetLineItem'][$i]['line_number'] = ($i + 1);
						$this -> request -> data['BudgetLineItem'][$i]['state'] = $this -> request -> data['Budget']['state'];
						//TODO Add in last mod by and date.
					}
					if ($this -> BudgetLineItem -> saveAll($this -> request -> data['BudgetLineItem']))
					{
					}
					foreach ($this -> request -> data['BudgetLineItem'] as $line)
					{
						$this -> BudgetLineItem -> updateAll(array('line_number' => $line['line_number']), array(
							'BudgetLineItem.name' => $line['name'],
							'BudgetLineItem.budget_id' => $line['budget_id']
						));
					}
				}
				$this -> Session -> write('Budget.state', $this -> request -> data['Budget']['state']);
				$this -> Session -> write('org_id', $org_id);
				if ($goToNext)
				{
					$org_id = $this -> getNextOrgId($fiscal_year, $tier, $org_id);
				}
			}
			$this -> set('org_id', $org_id);
			if ($org_id != 0)
			{
				$budgets = $this -> Budget -> find('all', array(
					'conditions' => array(
						'Budget.fiscal_year' => $fiscal_year,
						'Budget.org_id' => $org_id
					),
					'recursive' => 2,
					'order' => 'Organization.name',
				));
			}
			else
			{
				$budgets = $this -> Budget -> find('all', array(
					'conditions' => array(
						'Budget.fiscal_year' => $fiscal_year,
						'tier' => ($tier) ? $tier : array(
							1,
							2,
							3
						)
					),
					'recursive' => 2,
					'order' => 'Organization.name'
				));
			}

			$orgIds = $this -> setOrganizationDropDown($fiscal_year, $tier);
			if ($org_id != 0)
			{
				$this -> setRequested($fiscal_year, $org_id);
				$this -> setAllocated($fiscal_year, $org_id);
			}
			else
			{
				$this -> setRequested($fiscal_year, $orgIds);
				$this -> setAllocated($fiscal_year, $orgIds);
			}
			$this -> set('budgets', $budgets);
		}
	}

	private function setOrganizationDropDown($fiscal_year, $tier)
	{
		$result = false;
		//Cache::read('organizations');
		$years = Hash::extract($this -> Budget -> find('all', array('fields' => array('DISTINCT fiscal_year'))), '{n}.Budget.fiscal_year');
		$this -> set('fiscal_years', array_combine($years, $years));
		if (!$result)
		{
			$orgIds = $this -> Budget -> find('all', array(
				'conditions' => array(
					'state' => 'Submitted',
					'Budget.fiscal_year' => $fiscal_year
				),
				'fields' => array('DISTINCT Budget.org_id')
			));
			$orgIds = Hash::extract($orgIds, '{n}.Budget.org_id');
			$this -> loadModel('Organization');
			$orgs = $this -> Organization -> find('list', array(
				'conditions' => array(
					'status' => array(
						'Active',
						'Under Review',
						'Pending'
					),
					'Organization.id' => $orgIds,
					'tier' => ($tier) ? $tier : array(
						1,
						2,
						3
					)
				),
				'recursive' => -1,
				'fields' => array('Organization.name'),
				'order' => 'Organization.name asc'
			));
			$orgIds = array_keys($orgs);
			Cache::write('organizations', $orgs);
			$result = $orgs;
		}
		$orgs = array('All') + $result;
		$this -> set('organizations', $orgs);
		return array_keys($result);
	}

	private function setRequested($fiscal_year = null, $orgIds)
	{
		if ($fiscal_year != null)
		{
			$total_requested = false;
			//Cache::read("total_requested");
			if (!$total_requested)
			{
				$total_requested = $this -> BudgetLineItem -> find('first', array(
					'conditions' => array(
						'BudgetLineItem.state' => 'Submitted',
						'Budget.fiscal_year' => $fiscal_year,
						'Budget.state' => 'Submitted',
						'Budget.org_id' => $orgIds
					),
					'fields' => array('SUM(amount) AS Total_Requested')
				));
				Cache::write("total_requested", $total_requested);
			}
			$ly_total_requested = Cache::read("ly_total_requested");
			if (!$ly_total_requested)
			{
				$ly_total_requested = $this -> BudgetLineItem -> find('first', array(
					'conditions' => array(
						'BudgetLineItem.state' => 'Submitted',
						'Budget.fiscal_year' => $fiscal_year - 1,
						'Budget.state' => 'Final',
						'Budget.org_id' => $orgIds
					),
					'fields' => array('SUM(amount) AS LY_Total_Requested')
				));
				Cache::write("ly_total_requested", $ly_total_requested);
			}
			$this -> set('total_requested', $total_requested[0]['Total_Requested']);
			$this -> set('ly_total_requested', $ly_total_requested[0]['LY_Total_Requested']);
		}
	}

	private function setAllocated($fiscal_year = null, $orgIds)
	{
		if ($fiscal_year != null)
		{
			$total_allocated = $this -> BudgetLineItem -> find('first', array(
				'conditions' => array(
					'BudgetLineItem.state' => 'Final',
					'Budget.fiscal_year' => $fiscal_year,
					'Budget.state' => 'Final',
					'Budget.org_id' => $orgIds
				),
				'fields' => array('SUM(amount) AS Total_Allocated')
			));
			$ly_total_allocated = $this -> BudgetLineItem -> find('first', array(
				'conditions' => array(
					'BudgetLineItem.state' => 'Final',
					'Budget.fiscal_year' => $fiscal_year - 1,
					'Budget.state' => 'Final',
					'Budget.org_id' => $orgIds
				),
				'fields' => array('SUM(amount) AS LY_Total_Allocated')
			));
			$this -> set('total_allocated', $total_allocated[0]['Total_Allocated']);
			$this -> set('ly_total_allocated', $ly_total_allocated[0]['LY_Total_Allocated']);
		}
	}

	private function updateLastModBy($budget_id)
	{
		$this -> Budget -> id = $budget_id;
		$this -> Budget -> saveField('last_mod_by', $this -> Session -> read('User.id'));
		$this -> Budget -> saveField('last_mod_date', date('Y-m-d H:i:s'));
	}

	private function getNextOrgId($fiscal_year = null, $tier = null, $org_id)
	{
		if ($fiscal_year != null && $tier != null && $org_id != null)
		{
			$orgIds = $this -> Budget -> find('all', array(
				'conditions' => array(
					'state' => 'Submitted',
					'Budget.fiscal_year' => $fiscal_year
				),
				'fields' => array('DISTINCT Budget.org_id')
			));
			$orgIds = Hash::extract($orgIds, '{n}.Budget.org_id');
			$this -> loadModel('Organization');
			$orgs = $this -> Organization -> find('list', array(
				'conditions' => array(
					'status' => array(
						'Active',
						'Under Review',
						'Pending'
					),
					'Organization.id' => $orgIds,
					'tier' => ($tier) ? $tier : array(
						1,
						2,
						3
					)
				),
				'recursive' => -1,
				'fields' => array('Organization.name'),
				'order' => 'Organization.name asc'
			));
			$orgIds = array_keys($orgs);
			$index = array_search($org_id, $orgIds);
			return $orgIds[$index + 1];
		}
	}

	public function copy()
	{
		$from_state = $this -> request -> data['Budget']['from_state'];
		$to_state = $this -> request -> data['Budget']['to_state'];
		if ($from_state == null || $to_state == null || strcasecmp($from_state, $to_state) == 0)
		{
			$this -> redirect(array(
				'controller' => 'budgets',
				'action' => 'view'
			));
		}
		else
		{
			$lineItems = $this -> BudgetLineItem -> find('all', array('conditions' => array(
					'Budget.fiscal_year' => '20' . ($this -> getFiscalYear() + 2),
					'BudgetLineItem.state' => $from_state
				)));
			$count = $this -> BudgetLineItem -> find('count', array('conditions' => array(
					'Budget.fiscal_year' => '20' . ($this -> getFiscalYear() + 2),
					'BudgetLineItem.state' => $to_state
				)));
			if ($count == 0)
			{
				for ($i = 0; $i < count($lineItems); $i++)
				{
					$lineItems[$i]['BudgetLineItem']['state'] = $to_state;
					$lineItems[$i]['BudgetLineItem']['comments'] = '';
					$lineItems[$i]['BudgetLineItem']['id'] = null;
				}
				$this -> BudgetLineItem -> saveAll($lineItems);
			}
		}
		$this -> redirect(array(
			'controller' => 'budgets',
			'action' => 'view'
		));
	}

	public function export()
	{
		$budgets = $this -> Budget -> find('all', array(
			'conditions' => array('Budget.fiscal_year' => '20' . ($this -> getFiscalYear() + 2), ),
			'recursive' => 2,
			'order' => 'Organization.name'
		));

		$export = array('Organizations' => '');

		$states = array(
			'JFC' => 'JFC',
			'UHRC' => 'UHRC',
			'GSSC' => 'GSSC',
			'UHR' => 'UHR',
			'GSS' => 'GSS',
			'CONF' => 'CONF',
			'Final' => 'Final'
		);
		foreach ($budgets as $budget)
		{
			$category = 0;
			$category_name = '';
			$org_name = $budget['Organization']['name'];
			foreach ($budget['Requested'] as $k => $budgetLineItem)
			{
				if ($category != $budgetLineItem['category'])
				{
					$category_name = $budgetLineItem['LineItemCategory']['name'];
				}
				$amounts = array();
				$amounts[] = (isset($budget['Previous_Budget']['Requested'][$k])) ? $budget['Previous_Budget']['Requested'][$k]['amount'] : 0;
				$amounts[] = (isset($budget['Previous_Budget']['Allocated'][$k])) ? $budget['Previous_Budget']['Allocated'][$k]['amount'] : 0;
				$amounts[] = $budgetLineItem['amount'];
				foreach ($states as $state)
				{
					$amounts[] = (isset($budget[$state][$k])) ? $budget[$state][$k]['amount'] : 0;
				}
				$export['Organizations'][$org_name]['Categories'][$category_name]['LineItems'][$budgetLineItem['name']] = $amounts;
				$category = $budgetLineItem['category'];
			}

		}
		$this -> layout = 'csv';
		$this -> set('states', $states);
		$this -> set('export', $export);
	}

	public function processCommentDialog($id = null)
	{
		$this -> autoRender = false;
		if ($this -> request -> is('get'))
		{
			$this -> BudgetLineItem -> id = $id;
			return json_encode(array(
				'success' => true,
				'data' => $this -> BudgetLineItem -> read()
			));
		}
		$this -> log("here");
		$this -> log($this -> request -> data);
		if ($this -> request -> is('post'))
		{
			$this -> BudgetLineItem -> id = $id;
			$this -> BudgetLineItem -> saveField("comments", $this -> request -> data['Comment']['Comment']);
		}
		return true;
	}

}
?>