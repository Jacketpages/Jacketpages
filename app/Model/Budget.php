<?php
class Budget extends AppModel
{
	public $recursive = -1;
	public $belongsTo = array('Organization' => array(
			'className' => 'Organization',
			'foreignKey' => 'org_id'
		),
		'Previous_Budget' => array(
			'className' => 'Budget',
			'foreignKey' => 'parent_id'
		));

	public $hasMany = array(
		'Requested' => array(
			'className' => 'BudgetLineItem',
			'dependent' => true,
			'conditions' => array('state' => 'Submitted'),
			'order' => 'Requested.line_number asc'
		),'Allocated'  => array(
			'className' => 'BudgetLineItem',
			'dependent' => true,
			'conditions' => array('state' => 'Final'),
			'order' => 'Allocated.line_number asc'
		)
		
	);
}
?>