<?php
/**
 * @author Stephen Roca
 * @since 12/8/2012
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Update General Bill Information');
$this -> Html -> addCrumb('All Bills', '/bills');
$this -> Html -> addCrumb('View Bill', '/bills/view/'.$bill['Bill']['id']);
$this -> Html -> addCrumb('Update General Bill Information', $this->here);
$this -> start('middle');
echo $this -> HTML -> div("", "Note: details cannot be edited if an author has signed the bill.", array('style'=>'margin-bottom:10px;'));
echo $this -> Form -> create();

if ($bill['Bill']['status'] < $AGENDA && !$admin)
{
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
	if ($this -> Session -> read("Sga.id") != null && $bill['Bill']['status'] >= $AGENDA)
	{
		echo $this -> Form -> input('status', array(
			'label' => 'Status',
			'options' => array(
				4 => 'Agenda',
				5 => 'Passed',
				6 => 'Failed',
				7 => 'Conference'
			),
			'default' => 'Select Organization'
		));
	}
}
else
{
	echo $this -> Form -> input('number', array('label' => 'Number'));
	if ($this -> Session -> read("Sga.id") != null && $bill['Bill']['status'] >= $AGENDA)
	{
		echo $this -> Form -> input('status', array(
			'label' => 'Status',
			'options' => array(
				4 => 'Agenda',
				5 => 'Passed',
				6 => 'Failed',
				7 => 'Conference'
			),
			'default' => 'Select Organization'
		));
	}
}
echo $this -> Form -> end('Submit');
$this -> end();
?>