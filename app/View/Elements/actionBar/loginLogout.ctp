<?php

$loginLogout = '';
if($general && !$gt_member)
{
	$loginLogout = $this->Html->link('Login',
		array(
			'controller' => 'users',
			'action' => 'login'
		),
		array(
			'class' => 'active'
		)
	);
}
if($gt_member)
{
	$loginLogout = $this->Html->link('Logout',
		array(
			'controller' => 'users',
			'action' => 'logout'
		),
		array(
			'class' => 'active'
		)
	);
}

echo $loginLogout;

?>