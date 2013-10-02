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
		$this->Session->setFlash('Please select your organization to create a budget.');
		$this->redirect(array('controller' => 'organizations', 'action' => 'my_orgs', $this -> Session -> read('User.id')));
	}

	public function edit($org_id = null)
	{
		if($org_id == null){
			$this->Session->setFlash('Please select your organization to create a budget.');
			$this->redirect(array('controller' => 'organizations', 'action' => 'my_orgs', $this -> Session -> read('User.id')));
		}
	
		$this -> loadModel('Budget');
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
				$newOldrIds = Hash::extract($data, "$category_name.{n}.OldRequested.id");
				$newOldaIds = Hash::extract($data, "$category_name.{n}.OldAllocation.id");

				$oldBliIds = Hash::extract($this -> BudgetLineItem -> findAllByBudgetIdAndCategory($budget_id, $deletion_cat_id), '{n}.BudgetLineItem.id');
				$oldOldrIds = Hash::extract($this -> BudgetLineItem -> findAllByBudgetIdAndCategoryAndState($old_budget_id, $deletion_cat_id, 'Submitted'), '{n}.BudgetLineItem.id');
				$oldOldaIds = Hash::extract($this -> BudgetLineItem -> findAllByBudgetIdAndCategoryAndState($old_budget_id, $deletion_cat_id, 'Final'), '{n}.BudgetLineItem.id');
				$deletion_ids = array_merge(array_diff($oldBliIds, $newBliIds), array_diff($oldOldrIds, $newOldrIds), array_diff($oldOldaIds, $newOldaIds));
				foreach ($deletion_ids as $id)
					$this -> BudgetLineItem -> delete($id);
				for ($i = 0; $i < count($data[$category_name]); $i++)
				{
					$data[$category_name][$i]['OldAllocation']['name'] = $data[$category_name][$i]['BudgetLineItem']['name'];
					$data[$category_name][$i]['OldRequested']['name'] = $data[$category_name][$i]['BudgetLineItem']['name'];
					$data[$category_name][$i]['OldRequested']['state'] = 'Submitted';
					$data[$category_name][$i]['OldAllocation']['state'] = 'Final';
					$data[$category_name][$i]['BudgetLineItem']['state'] = 'Submitted';
					$data[$category_name][$i]['BudgetLineItem']['line_number'] = $j;
					$data[$category_name][$i]['OldAllocation']['line_number'] = $j;
					$data[$category_name][$i]['OldRequested']['line_number'] = $j;
					$data[$category_name][$i]['BudgetLineItem']['budget_id'] = $budget_id;
					$data[$category_name][$i]['OldAllocation']['budget_id'] = $old_budget_id;
					$data[$category_name][$i]['OldRequested']['budget_id'] = $old_budget_id;
					$this -> loadModel('LineItemCategory');
					$category_id = $this -> LineItemCategory -> field('id', array('name' => $category_name));
					$data[$category_name][$i]['BudgetLineItem']['category'] = $category_id;
					$data[$category_name][$i]['OldAllocation']['category'] = $category_id;
					$data[$category_name][$i]['OldRequested']['category'] = $category_id;
					if (strcmp($data[$category_name][$i]['OldAllocation']['name'], ''))
					{
						$j++;
						$this -> BudgetLineItem -> create();
						if ($this -> BudgetLineItem -> save($data[$category_name][$i]['OldAllocation']))
						{
						}
					}
					if (strcmp($data[$category_name][$i]['OldRequested']['name'], ''))
					{
						$this -> BudgetLineItem -> create();
						if ($this -> BudgetLineItem -> save($data[$category_name][$i]['OldRequested']))
						{
						}
					}
					if (strcmp($data[$category_name][$i]['BudgetLineItem']['name'], ''))
					{
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
			}
			if (strcmp($this -> request -> data['redirect'], 'Save and Continue') == 0)
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
		$this -> set('budgetSubmitted', $this -> Budget -> find('count', array('conditions' => array('id' => $budgetId))));
		if ($oldBudgetId)
		{
			$oldAllocatedLineItems = $this -> BudgetLineItem -> findAllByBudgetIdAndState($oldBudgetId, 'Final', array(), array('line_number asc'));
			$oldRequestedLineItems = $this -> BudgetLineItem -> findAllByBudgetIdAndState($oldBudgetId, 'Submitted', array(), array('line_number asc'));
			$currentLineItems = $this -> BudgetLineItem -> findAllByBudgetId($budgetId, array(), array('line_number asc'));

			$budgetLineItems = array();
			foreach ($category_names as $category_name)
			{
				$budgetLineItems[$category_name] = array();
			}
			for ($i = 0; $i < count($oldAllocatedLineItems); $i++)
			{
				array_push($budgetLineItems[$oldAllocatedLineItems[$i]['LineItemCategory']['name']], array(
					'BudgetLineItem' => $currentLineItems[$i]['BudgetLineItem'],
					'OldAllocation' => $oldAllocatedLineItems[$i]['BudgetLineItem'],
					'OldRequested' => $oldRequestedLineItems[$i]['BudgetLineItem']
				));
			}
			$this -> set('budgetLineItems', $budgetLineItems);
		}
		$this -> set('cat_count', count($category_names));
	}

	private function oldBudgetExists($org_id)
	{
		return count($this -> Budget -> findByOrgIdAndFiscalYear($org_id, '20' . $this -> getFiscalYear() + 1));
	}

}
?>
