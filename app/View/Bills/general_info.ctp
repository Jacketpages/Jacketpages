<?php
/**
 * @author Stephen Roca
 * @since 12/8/2012
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Update General Bill Information');
$this -> start('sidebar');
echo $this -> Html -> nestedList(
array($this -> Html -> link('View All Bills', array('action' => 'index'))), array(), array('id' => 'underline')
);
$this -> end();
$this -> start('middle');
echo $this -> Form -> create();
echo $this -> Form -> input('id', array('type' => 'hidden'));
echo $this -> Form -> input('title', array('label' => 'Title'));
echo $this -> Form -> input('description', array('label' => 'Description'));
echo $this -> Form -> input('number', array('label' => 'Number'));
echo $this -> Form -> input('fundraising', array('label' => 'Fundraising Efforts'));
echo $this -> Form -> input('type', array('label' => 'Type'));
echo $this -> Form -> input('category', array('label' => 'Category'));
echo $this -> Form -> input('org_id', array(
	'label' => 'Organization',
	'options' => $organizations,
	'default' => 'Select Organization'
));
echo $this -> Form -> end('Submit');
$this -> end();
?>