<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

class BudgetLineItemsController extends AppController
{
	public function edit($org_id)
	{
		$this -> loadModel('Budget');
		$this -> loadModel('LineItemCategory');
		if ($this -> request -> is('post'))
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
				for ($i = 0; $i < count($data[$category_name]); $i++)
				{
					$data[$category_name][$i]['OldAllocation']['name'] = $data[$category_name][$i]['BudgetLineItem']['name'];
					$data[$category_name][$i]['OldRequested']['name'] = $data[$category_name][$i]['BudgetLineItem']['name'];
					$data[$category_name][$i]['OldAllocation']['amount'] = $data[$category_name][$i]['BudgetLineItem']['amount'];
					$data[$category_name][$i]['OldRequested']['amount'] = $data[$category_name][$i]['BudgetLineItem']['amount'];
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
					$this -> BudgetLineItem -> create();
					if (strcmp($data[$category_name][$i]['OldAllocation']['name'], ''))
					{
						$j++;
						if ($this -> BudgetLineItem -> save($data[$category_name][$i]['OldAllocation']))
						{
						}
					}
					if (strcmp($data[$category_name][$i]['OldRequested']['name'], ''))
					{
						if ($this -> BudgetLineItem -> save($data[$category_name][$i]['OldRequested']))
						{
						}
					}
					if (strcmp($data[$category_name][$i]['BudgetLineItem']['name'], ''))
					{
						if ($this -> BudgetLineItem -> save($data[$category_name][$i]['BudgetLineItem']))
						{
						}
					}
				}
			}
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

	}


private function oldBudgetExists($org_id)
{
return count($this -> Budget -> findByOrgIdAndFiscalYear($org_id, '20' . $this -> getFiscalYear() + 1));
}

}
?>
