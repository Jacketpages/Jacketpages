<?php
/**
 * @author Stephen Roca
 * @since 07/02/2012
 */
class Group extends AppModel
{
	public $actsAs = array('Acl' => array('requester'));

	function parentNode()
	{
		return null;
	}

}
?>