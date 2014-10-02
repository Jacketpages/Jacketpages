<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

class BudgetLineItemsController extends AppController
{
	public function index()
	{
		// no index
		$this -> Session -> setFlash('Please select your organization to create a budget.');
		$this -> redirect(array(
			'controller' => 'organizations',
			'action' => 'my_orgs',
			$this -> Session -> read('User.id')
		));
	}

	public function edit($org_id = null)
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
		$this -> loadModel('Budget');
		if (!($this -> isOfficer($org_id) || $this -> isSGAExec()))
			$this -> redirect($this -> referer());
		//can only get here if budget has been started
		$budgetId = $this -> Budget -> field('id', array(
			'org_id' => $org_id,
			'fiscal_year' => '20' . $this -> getFiscalYear() + 2
		));
		if (!$budgetId && !$this -> isSGAExec())
			$this -> redirect(array(
				'controller' => 'budgets',
				'action' => 'summary',
				$org_id
			));
		$this -> loadModel('LineItemCategory');
		if ($this -> request -> is('post') || $this -> request -> is('put'))
		{
			$data = $this -> request -> data;
			$categories = $this -> LineItemCategory -> find('all');
			$category_names = Hash::extract($categories, '{n}.LineItemCategory.name');
			if (!$this -> oldBudgetExists($org_id))
			{
				$this -> Budget -> set('org_id', $org_id);
				$this -> Budget -> set('fiscal_year', '20' . $this -> getFiscalYear() + 1);
				if ($this -> Budget -> save())
				{

				}
			}
			$oldBudget = $this -> Budget -> findByOrgIdAndFiscalYear($org_id, '20' . $this -> getFiscalYear() + 1);
			$old_budget_id = $oldBudget['Budget']['id'];
			$budget = $this -> Budget -> findByOrgIdAndFiscalYear($org_id, '20' . $this -> getFiscalYear() + 2);
			$budget_id = $budget['Budget']['id'];
			// Start saving line items
			$j = 1;

			foreach ($category_names as $category_name)
			{
				$deletion_cat_id = $this -> LineItemCategory -> field('id', array('name' => $category_name));
				$newBliIds = Hash::extract($data, "$category_name.{n}.BudgetLineItem.id");
				$oldBliIds = Hash::extract($this -> BudgetLineItem -> findAllByBudgetIdAndCategory($budget_id, $deletion_cat_id), '{n}.BudgetLineItem.id');
				$deletion_ids = array_merge(array_diff($oldBliIds, $newBliIds));
				foreach ($deletion_ids as $id)
					$this -> BudgetLineItem -> delete($id);
				for ($i = 0; $i < count($data[$category_name]); $i++)
				{
					$data[$category_name][$i]['BudgetLineItem']['state'] = 'Submitted';
					$data[$category_name][$i]['BudgetLineItem']['line_number'] = $j;
					$data[$category_name][$i]['BudgetLineItem']['budget_id'] = $budget_id;
					$this -> loadModel('LineItemCategory');
					$category_id = $this -> LineItemCategory -> field('id', array('name' => $category_name));
					$data[$category_name][$i]['BudgetLineItem']['category'] = $category_id;

					// 0 is false
					// -1 or 1 is true
					if (strcmp($data[$category_name][$i]['BudgetLineItem']['name'], ''))
					{
						if(strcmp($data[$category_name][$i]['BudgetLineItem']['amount'], '') == 0)
						{
							$data[$category_name][$i]['BudgetLineItem']['amount'] = 0;
						}
						$j++;
						$this -> BudgetLineItem -> create();
						if ($this -> BudgetLineItem -> save($data[$category_name][$i]['BudgetLineItem']))
						{
						}
					}
				}
				$this -> loadModel('BudgetSubmitState');
				$this -> BudgetSubmitState -> save(array('BudgetSubmitState' => array(
						'id' => $budget_id,
						'state_2' => 1
					)));
				$this -> Budget -> id = $budget_id;
				$this -> Budget -> saveField('last_mod_by', $this -> Session -> read('User.id'));
				$this -> Budget -> saveField('last_mod_date', date('Y-m-d H:i:s'));

			}
			if (isset($this -> request -> data['redirect']) && strcmp($this -> request -> data['redirect'], 'Save and Continue') == 0)
				$this -> redirect(array(
					'controller' => 'budgets',
					'action' => 'fundraising',
					$org_id
				));
		}
		$categories = $this -> LineItemCategory -> find('all');
		$category_names = Hash::extract($categories, '{n}.LineItemCategory.name');
		$this -> set('category_names', $category_names);
		$this -> set('category_descriptions', Hash::extract($categories, '{n}.LineItemCategory.description'));
		$oldBudgetId = $this -> Budget -> field('id', array(
			'org_id' => $org_id,
			'fiscal_year' => '20' . $this -> getFiscalYear() + 1
		));
		$budgetId = $this -> Budget -> field('id', array(
			'org_id' => $org_id,
			'fiscal_year' => '20' . $this -> getFiscalYear() + 2
		));
		$this -> set('org_id', $org_id);
		$this -> set('budgetSubmitted', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $budgetId,
				'state' => 'Submitted'
			))));
		$this -> set('budgetCreated', $this -> Budget -> find('count', array('conditions' => array(
				'id' => $budgetId,
				'state' => 'Created'
			))));
		if ($oldBudgetId)
		{
			$currentLineItems = $this -> BudgetLineItem -> findAllByBudgetIdAndState($budgetId, 'Submitted', array(), array('line_number asc'));

			if(count($currentLineItems) == 0)
			{
				$oldAllocatedLineItems = $this -> BudgetLineItem -> findAllByBudgetIdAndState($oldBudgetId, 'Final', array(), array('line_number asc'));
				$oldRequestedLineItems = $this -> BudgetLineItem -> findAllByBudgetIdAndState($oldBudgetId, 'Submitted', array(), array('line_number asc'));
				$currentLineItems = $oldAllocatedLineItems;
				for($k = 0; $k < count($currentLineItems); $k++)
				{
					$currentLineItems[$k]['BudgetLineItem']["py_req"] = $oldRequestedLineItems[$k]['BudgetLineItem']["amount"];
					$currentLineItems[$k]['BudgetLineItem']["py_alloc"] = $oldAllocatedLineItems[$k]['BudgetLineItem']["amount"];
					//$currentLineItems[$k]['BudgetLineItem']["amount"] = 0;
					$currentLineItems[$k]['BudgetLineItem']["alloc_parent_id"] = $oldAllocatedLineItems[$k]['BudgetLineItem']["id"];
					$currentLineItems[$k]['BudgetLineItem']["req_parent_id"] = $oldRequestedLineItems[$k]['BudgetLineItem']["id"];
					$currentLineItems[$k]['BudgetLineItem']["id"] = "";
				}
			}
			else 
			{
				for($q = 0; $q < count($currentLineItems); $q++)
				{
					$this -> BudgetLineItem -> id = $currentLineItems[$q]['BudgetLineItem']["req_parent_id"];
					$currentLineItems[$q]['BudgetLineItem']["py_req"] = $this -> BudgetLineItem -> field('amount');
					if($currentLineItems[$q]['BudgetLineItem']["py_req"] == null){
						$currentLineItems[$q]['BudgetLineItem']["py_req"] = 0;
					}
					unset($currentLineItems[$q]['BudgetLineItem']["req_parent_id"]);
					
					$this -> BudgetLineItem -> id = $currentLineItems[$q]['BudgetLineItem']["alloc_parent_id"];
					$currentLineItems[$q]['BudgetLineItem']["py_alloc"] = $this -> BudgetLineItem -> field('amount');
					if($currentLineItems[$q]['BudgetLineItem']["py_alloc"] == null){
						$currentLineItems[$q]['BudgetLineItem']["py_alloc"] = 0;
					}
					unset($currentLineItems[$q]['BudgetLineItem']["alloc_parent_id"]);
				}
			}

			$budgetLineItems = array();
			foreach ($category_names as $category_name)
			{
				$budgetLineItems[$category_name] = array();
			}
			for ($i = 0; $i < count($currentLineItems); $i++)
			{
				array_push($budgetLineItems[$currentLineItems[$i]['LineItemCategory']['name']], array(
					'BudgetLineItem' => $currentLineItems[$i]['BudgetLineItem']
				));
			}
			$this -> set('budgetLineItems', $budgetLineItems);
		}
		$this -> set('cat_count', count($category_names));
		
		// get the organization, for the breadcrump
		$this -> loadModel('Organization');
		$this -> Organization -> id = $org_id;
		$organization = $this -> Organization -> read();
		$this -> set('organization', $organization);
	}

	private function oldBudgetExists($org_id)
	{
		return count($this -> Budget -> findByOrgIdAndFiscalYear($org_id, '20' . $this -> getFiscalYear() + 1));
	}

}
?>