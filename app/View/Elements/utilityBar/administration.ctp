<?php
/**
 * @author Stephen Roca
 * @since 8/14/2013
 */
if ($admin)
{
	$adminList = array($this -> Html -> link('Administration', '#') => array(
			$this -> Html -> link('Administer All Bills', array(
				'controller' => 'bills',
				'action' => 'index'
			)),
			$this -> Html -> link('Administer SGA Members', array(
				'controller' => 'sga_people',
				'action' => 'index'
			)),
			$this -> Html -> link('Administer Users', array(
				'controller' => 'users',
				'action' => 'index'
			)),
			$this -> Html -> link('Administer Organizations', array(
				'controller' => 'organizations',
				'action' => 'index',
			)),
			$this -> Html -> link('Login as Other User', array(
				'controller' => 'users',
				'action' => 'loginAsOtherUser'
			)),
			// $this -> Html -> link('Submit Bill as Other User', array(
				// 'controller' => 'bills',
				// 'action' => 'add'
			// )),
			$this -> Html -> link('Post Message', '/Messages/message')
		));

	echo $this -> Html -> nestedList($adminList);
}
