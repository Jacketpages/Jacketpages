<?php
/**
 * @author Stephen Roca
 * @since 12/8/2012
 */
 $this -> start('script');
echo $this -> Html -> script('bills/submitBill');
echo $this -> Html -> script('validation/validation');
echo $this -> Html -> script('bills/billvalidation');
echo "<script>$(document).ready(function() {hideAuthors() });</script>";
$this -> end();
$this -> extend('/Common/common');
$this -> assign('title', 'Update General Bill Information');
$this -> Html -> addCrumb('All Bills', '/bills');
$this -> Html -> addCrumb('View Bill', '/bills/view/'.$bill['Bill']['id']);
$this -> Html -> addCrumb('Update General Bill Information', $this->here);
$this -> start('middle');
echo $this -> HTML -> div("", "Note: Details cannot be edited if an author has signed the bill. Joint bills require two authors.", array('style'=>'margin-bottom:10px;'));
echo $this -> Form -> create();

if (($bill['Submitter']['id'] == $this -> Session -> read('User.id') && $bill['Bill']['status'] == $CREATED) 
		|| ($this -> Session -> read('Sga.id') != null && $bill['Bill']['status'] == $AWAITING_AUTHOR && $this -> Session -> read('Sga.id') == $bill['Authors']['grad_auth_id'])
		|| ($this -> Session -> read('Sga.id') != null && $bill['Bill']['status'] == $AWAITING_AUTHOR && $this -> Session -> read('Sga.id') == $bill['Authors']['undr_auth_id'])
		|| $sga_exec)
{
	echo $this -> Form -> input('id', array('type' => 'hidden'));
	echo $this -> Form -> input('title', array('label' => 'Title'));
	echo $this -> Form -> input('description', array('label' => 'Description'));	
	echo $this -> Form -> input('fundraising', array('label' => 'Fundraising Efforts'));
	//echo $this -> Form -> input('type', array('label' => 'Type'));
	//echo $this -> Form -> input('category', array('label' => 'Category'));
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
	echo $this -> Form -> input('Authors.id', array('type' => 'hidden'));
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
}
if($sga_admin)
{
	echo $this -> Form -> input('status', array(
			'label' => 'Status',
			'options' => array(
				1 => 'Created',
				2 => 'Awaiting Author',
				3 => 'Authored',
				4 => 'Agenda',
				5 => 'Passed',
				6 => 'Failed',
				7 => 'Conference'
			),
			'default' => $bill['Bill']['status']
		));
	echo $this -> Form -> input('number', array('label' => 'Number'));
}
else if ($bill['Bill']['status'] >= $AUTHORED && $sga_exec)
{
	echo $this -> Form -> input('status', array(
			'label' => 'Status',
			'options' => array(
				3 => 'Authored',
				4 => 'Agenda',
				5 => 'Passed',
				6 => 'Failed',
				7 => 'Conference'
			),
			'default' => $bill['Bill']['status']
		));
	echo $this -> Form -> input('number', array('label' => 'Number'));
}

echo $this -> Form -> end('Submit');
$this -> end();
?>