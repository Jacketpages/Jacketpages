<?php
/**
 * @author Stephen Roca
 * @since 3/22/2012
 */
class User extends AppModel
{
	public $name = 'User';
	public $virtualFields = array('name' => 'CONCAT(first_name, " ", last_name)');

	public $belongsTo = array(
		'LOCAL_ADDR' => array(
			'className' => 'Location',
			'foreignKey' => 'local_addr'
		),
		'HOME_ADDR' => array(
			'className' => 'Location',
			'foreignKey' => 'home_addr'
		)
	);
	public $validate = array(
		'gt_user_name' => array('rule' => 'notEmpty'),
		'first_name' => array('rule' => 'notEmpty'),
		'last_name' => array('rule' => 'notEmpty'),
		'email' => array('rule' => 'email')
	);
}
?>