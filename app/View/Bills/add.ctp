<?php
/**
 * @author Stephen Roca
 * @since 08/02/2012
 */

$this -> start('script');
echo $this -> Html -> script('bills/submitBill');
echo $this -> Html -> script('validation/validation');
echo $this -> Html -> script('bills/billvalidation');
$this -> end();
$this -> extend('/Common/common');
$this -> Html -> addCrumb('All Bills', '/bills');
$this -> Html -> addCrumb('Create New Bill', '/bills/add');
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
	),
	'onChange' => 'hideLineItems()'
));
echo $this -> Form -> input('category', array(
	'label' => 'Category',
	'type' => 'select',
	'id' => 'categoryChoice',
	'onChange' => 'hideAuthors()',
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
echo $this -> Html -> div('multi_enter_line_items', $this -> Html -> tag('h1', 'Add Line Items') . $this -> element('multi_enter_line_items'));
echo $this -> Form -> submit('Save and Continue', array(
	'formnovalidate',
	'onclick' => 'openToolTips()'
));
$this -> end();
?>
