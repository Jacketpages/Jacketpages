<?php
/**
 * @author Stephen Roca
 * @since 8/24/2012
 */

$this -> extend('/Common/common');
$this -> assign('title', 'My Organizations and Positions');
$this -> Html -> addCrumb('All Organizations', '/organizations');
$this -> Html -> addCrumb('My Organizations', $this->here);
$this -> start('middle');
echo $this -> Html -> tag('h1', 'Executive Positions');
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		'Organization',
		'Role',
		'Start Date',
		'End Date'
	));
	foreach ($memberships as $membership)
	{
		if ($membership['Membership']['role'] != 'Member')
		{
		echo $this -> Html -> tableCells(array(
			$this->Html->link($membership['Organization']['name'], array('action'=>'view', $membership['Organization']['id'])),
			$membership['Membership']['role'],
			$membership['Membership']['start_date'],
			$membership['Membership']['end_date']
		));
	}}
	echo $this -> Html -> tableEnd();

echo $this -> Html -> tag('h1', 'General Affilitations');
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		'Organization',
		'Role',
		'Start Date',
		'End Date'
	));
	foreach ($memberships as $membership)
	{
		if ($membership['Membership']['role'] == 'Member')
		{
		echo $this -> Html -> tableCells(array(
			$this->Html->link($membership['Organization']['name'], array('action'=>'view', $membership['Organization']['id'])),
			$membership['Membership']['role'],
			$membership['Membership']['start_date'],
			$membership['Membership']['end_date']
		));
	}}
	echo $this -> Html -> tableEnd();

$this -> end();
?>