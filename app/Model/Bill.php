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
		)
	);
}
?>
