<?php
/**
 * @author Stephen Roca
 * @since 08/24/2012
 */
class LineItem extends AppModel
{
	public $belongsTo = array('Bill' => array(
			'className' => 'Bill',
			'foreignKey' => 'bill_id'
		));

	public $validate = array(
		'cost_per_unit' => array(
			'rule' => array(
				'money',
				'left'
			),
			'message' => 'That is not a valid monetary amount'
		),
		'quantity' => array(
			'rule' => array(
				'money',
				'left'
			),
			'message' => 'That is not a valid monetary amount'
		),
		'total_cost' => array(
			'rule' => array(
				'money',
				'left'
			),
			'message' => 'That is not a valid monetary amount'
		),
		'Amount Requested' => array(
			'rule' => array(
				'money',
				'left'
			),
			'message' => 'That is not a valid monetary amount'
		),	);
}
?>
