<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */
 
class BudgetLineItemsController extends AppController
{
	public function edit($org_id)
	{
		$this -> loadModel('LineItemCategory');
		$categories = $this -> LineItemCategory -> find('all');
		$this -> set('category_names', Hash::extract($categories,'{n}.LineItemCategory.name'));
		$this -> set('category_descriptions', Hash::extract($categories,'{n}.LineItemCategory.description'));
		$this -> set('budgetLineItems', array());
	}
}
?>
	