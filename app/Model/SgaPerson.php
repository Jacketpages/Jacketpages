<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 */

class SgaPerson extends AppModel
{
	public $virtualFields = array('name_department' => 'CONCAT(CONCAT(first_name, " ", last_name), " -- ", department)');
	public $order = 'User.first_name';
	public $belongsTo = array('User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		));
}
?>