<?php
/**
 * @author Stephen Roca
 * @since 08/24/2012
 */
class LineItem extends AppModel
{
	public $belongsTo = array(
		'Bill' => array(
			'className' => 'Bill',
			'foreignKey' => 'bill_id'
		)
	);
}

?>
	