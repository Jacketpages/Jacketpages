<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 */

class SgaPerson extends AppModel
{
	public $virtualFields = array('NAME_DEPARTMENT' => 'CONCAT(CONCAT(User.FIRST_NAME, " ", User.LAST_NAME), " -- ", DEPARTMENT)');
	public $order = 'User.FIRST_NAME';
	public $belongsTo = array('User' => array(
			'className' => 'User',
			'foreignKey' => 'USER_ID'
		));
}
?>