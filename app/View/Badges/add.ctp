<?php

$this -> extend('/Common/common');

$this -> assign('title', 'Add New Badge');

$this -> Html -> addCrumb('Badges', '/badges');
$this -> Html -> addCrumb('Add New Badge', '/badges/add');

$this -> start('sidebar');
$sidebar = array();
$sidebar[] = $this -> Html -> link('View All Badges', array('action' => '/'));
$sidebar[] = $this -> Html -> link('Award A Badge', array('action' => 'award'));
$sidebar[] = $this -> Html -> link('Edit A Badge', array('action' => 'edit'));
$sidebar[] = $this -> Html -> link('Add New Badge', array('action' => 'add'));
echo $this -> Html -> nestedList($sidebar);
$this -> end();


$this -> start('middle');

echo $this->Form->create('Badge', array('type' => 'file'));
echo $this->Form->input('name');
echo $this->Form->input('icon', array('type' => 'file'));
echo $this->Form->input('view_style',
	array('options' => array('default'=>'default', 'custom' => 'custom (contact IT after adding to request a custom badge style)'),
	      'disabled' => array('custom'))
);
echo $this->Form->input('description', array('type' => 'textarea'));
echo $this->Form->submit('Add New Badge');
echo $this->Form->end();

$this -> end();

?>
