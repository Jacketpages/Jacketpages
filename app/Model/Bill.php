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
			'foreignKey' => 'status'
		),
		'Submitter' => array(
			'className' => 'User',
			'foreignKey' => 'submitter'
		),
		'GSS' => array('className' => 'BillVote', 'foreignKey' => 'gss_id'),
		'UHR' => array('className' => 'BillVote', 'foreignKey' => 'uhr_id'),
		'GCC' => array('className' => 'BillVote', 'foreignKey' => 'gcc_id'),
		'UCC' => array('className' => 'BillVote', 'foreignKey' => 'ucc_id'),
		'Authors' => array('className' => 'BillAuthor', 'foreignKey' => 'auth_id'),
		'Organization' => array('className' => 'Organization', 'foreignKey' => 'org_id', 'fields' => 'name')
	);
	
	public $validate = array(
		'title' => array(
			'declared' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Must be numbers and letters and cannot be blank.',
	),
	),
		'description' => array(
			'declared' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Must be numbers and letters and cannot be blank.',
	),
	),
		'org_id' => array(
			'declared' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'You must enter an organization.',
	),
	),
		'type' => array(
			'rule' => array('inList', array('Resolution','Finance Request', 'Budget')),
			'message' => 'Invalid type',
	),
		'category' => array(
			'rule' => array('inList', array('Joint', 'Undergraduate', 'Graduate', 'Conference')),
	)
	);
}
?>
