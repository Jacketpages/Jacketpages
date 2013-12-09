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
		),
		'JFC'  => array(
			'className' => 'BudgetLineItem',
			'dependent' => true,
			'conditions' => array('state' => 'JFC'),
			'order' => 'JFC.line_number asc'
		),
		'UHRC'  => array(
			'className' => 'BudgetLineItem',
			'dependent' => true,
			'conditions' => array('state' => 'UHRC'),
			'order' => 'UHRC.line_number asc'
		),
		'GSSC'  => array(
			'className' => 'BudgetLineItem',
			'dependent' => true,
			'conditions' => array('state' => 'GSSC'),
			'order' => 'GSSC.line_number asc'
		),
		'UHR'  => array(
			'className' => 'BudgetLineItem',
			'dependent' => true,
			'conditions' => array('state' => 'UHR'),
			'order' => 'UHR.line_number asc'
		),
		'GSS'  => array(
			'className' => 'BudgetLineItem',
			'dependent' => true,
			'conditions' => array('state' => 'GSS'),
			'order' => 'GSS.line_number asc'
		),
		'CONF'  => array(
			'className' => 'BudgetLineItem',
			'dependent' => true,
			'conditions' => array('state' => 'CONF'),
			'order' => 'CONF.line_number asc'
		),
		'Final'  => array(
			'className' => 'BudgetLineItem',
			'dependent' => true,
			'conditions' => array('state' => 'Final'),
			'order' => 'Final.line_number asc'
		)
		
	);
}
?>