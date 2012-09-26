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
		'UCC' => array('className' => 'BillVote', 'foreignKey' => 'UCC_ID')
	);
}
?>
