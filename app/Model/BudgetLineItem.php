<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */
 
class BudgetLineItem extends AppModel
{
	public $belongsTo = array('LineItemCategory' => array(
			'className' => 'LineItemCategory',
			'foreignKey' => 'category'
		));
}
?>
