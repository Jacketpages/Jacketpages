<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 */
class Membership extends AppModel
{
	public $name = 'Membership';
	public $virtualFields = array('name' => 'CONCAT(User.first_name, " ", User.last_name)');
	public $belongsTo = array(
		'Organization' => array(
			'className' => 'Organization',
			'foreignKey' => 'org_id'
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);
}
?>