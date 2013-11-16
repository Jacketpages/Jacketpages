<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

class BudgetLineItem extends AppModel
{
	public $belongsTo = array(
		'LineItemCategory' => array(
			'className' => 'LineItemCategory',
			'foreignKey' => 'category'
		),
		'Budget' => array(
			'className' => 'Budget',
			'foreignKey' => 'budget_id'
		)
	);
	public $order = "line_number asc";
}
?>