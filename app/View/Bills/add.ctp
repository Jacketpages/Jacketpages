<?php
/**
 * @author Stephen Roca
 * @since 08/02/2012
 */
$this -> extend('/Common/common');
echo $this -> Html -> addCrumb('All Bills', '/bills');
$this -> start('sidebar');
echo $this -> Html -> nestedList(array($this -> Html -> link('View All Bills', array('action' => 'index'))), array(), array('id' => 'underline'));
$this -> end();
$this -> assign('title', 'Create New Bill');
$this -> start('middle');
echo $this -> Form -> create();
echo $this -> Form -> input('TITLE', array('label' => 'Title'));
echo $this -> Form -> input('DESCRIPTION', array('label' => 'Description'));
echo $this -> Form -> input('FUNDRAISING', array('label' => 'Fundraising - Please describe related fundraising efforts'));
echo $this -> Form -> input('TYPE', array(
	'label' => 'Type',
	'options' => array(
		'Finance Request' => 'Finance Request',
		'Resolution' => 'Resolution'
	)
));
echo $this -> Form -> input('CATEGORY', array(
	'label' => 'Category',
	'options' => array(
		'Joint' => 'Joint',
		'Graduate' => 'Graduate',
		'Undergraduate' => 'Undergraduate'
	)
));
echo $this -> Form -> input('STATUS', array(
	'label' => 'Status',
	'options' => array(
		'Awaiting Author' => 'Awaiting Author',
		'Authored' => 'Authored',
		'Agenda' => 'Agenda',
		'Passed' => 'Passed',
		'Failed' => 'Failed',
		'Archived' => 'Archived'
	)
));
echo $this -> Form -> input('ORG_ID', array(
	'label' => 'Organization',
	'options' => $organizations,
	'default' => 'Select Organization'
));
echo $this -> Form -> input('underAuthor_id', array(
	'div' => 'underAuthor_id',
	'label' => 'Undergraduate Author',
	'options' => $underAuthors
));
echo $this -> Form -> input('gradAuthor_id', array(
	'div' => 'gradAuthor_id',
	'label' => 'Graduate Author',
	'options' => $gradAuthors
));
echo $this -> Form -> end('Submit');
$this -> end();
?>
