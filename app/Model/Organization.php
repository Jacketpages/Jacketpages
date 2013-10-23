<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 */
class Organization extends AppModel
{
	public $order = 'Organization.name';
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'contact_id'
		),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category'
		)
	);
}
?>
