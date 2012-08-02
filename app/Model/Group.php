<?php
class Group extends AppModel
{
	public $actsAs = array('Acl' => array('requester'));

	function parentNode()
	{
		return null;
	}

}
?>