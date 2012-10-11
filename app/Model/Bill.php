<?php
/**
 * @author Stephen Roca
 * @since 07/02/2012
 */
class Bill extends AppModel
{
	public $belongsTo = array(
		'Status' => array(
			'className' => 'BillStatus',
			'foreignKey' => 'STATUS'
		),
		'Submitter' => array(
			'className' => 'User',
			'foreignKey' => 'SUBMITTER'
		),
		'GSS' => array('className' => 'BillVote', 'foreignKey' => 'GSS_ID'),
		'UHR' => array('className' => 'BillVote', 'foreignKey' => 'UHR_ID'),
		'GCC' => array('className' => 'BillVote', 'foreignKey' => 'GCC_ID'),
		'UCC' => array('className' => 'BillVote', 'foreignKey' => 'UCC_ID'),
		'Authors' => array('className' => 'BillAuthor', 'foreignKey' => 'AUTH_ID')
	);
	
	public $validate = array(
		'TITLE' => array(
			'declared' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Must be numbers and letters and cannot be blank.',
	),
	),
		'DESCRIPTION' => array(
			'declared' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Must be numbers and letters and cannot be blank.',
	),
	),
		'ORG_ID' => array(
			'declared' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'You must enter an organization.',
	),
	),
		'TYPE' => array(
			'rule' => array('inList', array('Resolution','Finance Request', 'Budget')),
			'message' => 'Invalid type',
	),
		'CATEGORY' => array(
			'rule' => array('inList', array('Joint', 'Undergraduate', 'Graduate', 'Conference')),
	)
	);
}
?>
