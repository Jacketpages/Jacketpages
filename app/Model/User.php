<?php
/**
 * @author Stephen Roca
 * @since 3/22/2012
 */
class User extends AppModel
{
	public $name = 'User';
	public $virtualFields = array('NAME' => 'CONCAT(FIRST_NAME, " ", LAST_NAME)');
	public $actsAs = array('Acl' => array('requester'));

	public function parentNode()
	{
		if (!$this -> id && empty($this -> data))
		{
			return null;
		}
		$data = $this -> data;
		if (empty($this -> data))
		{
			$data = $this -> read();
		}
		if (!$data['User']['GROUP_ID'])
		{
			return null;
		}
		else
		{
			return array('Group' => array('id' => $data['User']['GROUP_ID']));
		}
	}

	public function bindNode($user)
	{
		return array(
			'model' => 'Group',
			'foreign_key' => $user['User']['GROUP_ID']
		);
	}

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
	// var $order;
}
?>