<?php

// submit bill link
if ($gt_member)
{
	echo $this -> Html -> link('Submit Bill', array(
		'controller' => 'bills',
		'action' => 'add'
	), array('class' => 'active'));
}
?>