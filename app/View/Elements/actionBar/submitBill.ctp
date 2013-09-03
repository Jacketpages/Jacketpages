<?php

// submit bill link
echo $this->Html->link('Submit Bill',
	array(
		'controller' => 'bills',
		'action' => 'add'
	),
	array(
		'class' => 'active'
	)
);

?>