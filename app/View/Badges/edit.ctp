<?php

$this -> extend('/Common/common');

$this -> assign('title', 'Edit \''.$badge['Badge']['name'].'\' Badge');

$this -> Html -> addCrumb('Badges', '/badges');
$this -> Html -> addCrumb('Edit Badge', '/badges/edit/'.$badge['Badge']['id']);

$this -> start('sidebar');
$sidebar = array();
$sidebar[] = $this -> Html -> link('View All Badges', array('action' => '/'));
$sidebar[] = $this -> Html -> link('Award This Badge', array('action' => 'award', $badge['Badge']['id']));
$sidebar[] = $this -> Html -> link('Edit A Badge', array('action' => 'edit'));
$sidebar[] = $this -> Html -> link('Add New Badge', array('action' => 'add'));
echo $this -> Html -> nestedList($sidebar);
$this -> end();

$this -> start('middle');

// show the badge
echo $this->Html->div('badgeButton',
	$this->element('badges/display', array('badge' => $badge['Badge'])),
	array('div' => array(
		'style' => 'margin:auto'
	))
);

echo '<hr />';

$allowCustomViewStyle = $this->elementExists('badges/custom_'.Inflector::slug($badge['Badge']['name']));

// edit form
echo $this->Form->create('Badge', array('type' => 'file'));
echo $this->Form->input('name');
echo $this->Form->input('icon', array(
	'type' => 'file'
));
if(!$allowCustomViewStyle){
	echo $this->Form->input('view_style', array(
		'options' => array('default'=>'default', 'custom' => 'custom (contact IT to request a custom badge style)'),
		'disabled' => array('custom')
	));
} else {
	// allow custom
	echo $this->Form->input('view_style', array(
		'options' => array('default'=>'default', 'custom' => 'custom')
	));
}
echo $this->Form->input('description', array('type' => 'textarea'));
echo $this->Form->hidden('icon_path');
echo $this->Form->submit('Save', array(
	'name' => 'submit',
	'div' => array(
		'style' => 'display: inline-block;'
	)
));
echo $this->Form->submit('Delete', array(
	'name' => 'submit',
	'onclick' => 'return confirm(\'Are you sure you want to delete this badge?\')',
	'div' => array(
		'style' => 'display: inline-block;'
	)
));
echo $this->Form->end();

$this -> end();

?>
