<?php
/**
 * @author Stephen Roca
 * @since 08/02/2012
 */
 
$this -> start('script');
echo $this -> Html -> script('validation/validation');
echo $this -> Html -> script('bills/billvalidation');
$this -> end();
$this -> extend('/Common/common');
echo $this -> Html -> addCrumb('All Bills', '/bills');
$this -> start('sidebar');
echo $this -> Html -> nestedList(array($this -> Html -> link('View All Bills', array('action' => 'index'))), array(), array('id' => 'underline'));
$this -> end();
$this -> assign('title', 'Create New Bill');
$this -> start('middle');
echo $this -> Form -> create('Bill', array('onsubmit' => 'return validateForm()'));
echo $this -> Form -> input('title', array('label' => 'Title'));
echo $this -> Form -> input('description', array('label' => 'Description'));
echo $this -> Form -> input('fundraising', array('label' => 'Fundraising - Please describe related fundraising efforts'));
echo $this -> Form -> input('type', array(
	'label' => 'Type',
	'options' => array(
		'Finance Request' => 'Finance Request',
		'Resolution' => 'Resolution'
	)
));
echo $this -> Form -> input('category', array(
	'label' => 'Category',
	'type' => 'select',
	'options' => array(
		'Joint' => 'Joint',
		'Graduate' => 'Graduate',
		'Undergraduate' => 'Undergraduate'
	)
));
echo $this -> Form -> input('org_id', array(
	'label' => 'Organization',
	'options' => $organizations,
	'default' => 'Select Organization'
));
echo $this -> Form -> input('Authors.undr_auth_id', array(
	'div' => 'underAuthor_id',
	'label' => 'Undergraduate Author',
	'options' => $underAuthors
));
echo $this -> Form -> input('Authors.grad_auth_id', array(
	'div' => 'gradAuthor_id',
	'label' => 'Graduate Author',
	'options' => $gradAuthors
));
echo $this -> Html -> tag('h1', 'Add Line Items');
echo $this -> element('multi_enter_line_items');
echo $this -> Form -> submit('Submit', array('formnovalidate','onclick' => 'openToolTips()'));
$this -> end();

?>