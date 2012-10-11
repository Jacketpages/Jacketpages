<?php
/**
 * @author Stephen Roca
 * @since 3/22/2012
 */
class User extends AppModel
{
	public $name = 'User';
	public $virtualFields = array('NAME' => 'CONCAT(FIRST_NAME, " ", LAST_NAME)');

	public $belongsTo = array(
		'LOCAL_ADDR' => array(
			'className' => 'Location',
			'foreignKey' => 'LOCAL_ADDR'
		),
		'HOME_ADDR' => array(
			'className' => 'Location',
			'foreignKey' => 'HOME_ADDR'
		)
	);
	public $validate = array(
		'GT_USER_NAME' => array('rule' => 'notEmpty'),
		'FIRST_NAME' => array('rule' => 'notEmpty'),
		'LAST_NAME' => array('rule' => 'notEmpty'),
		'EMAIL' => array('rule' => 'email')
	);
}
?>