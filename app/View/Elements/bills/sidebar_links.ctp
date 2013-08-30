<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

$updateBillAction = 'general_info';
if ($this -> Session -> read('Sga.id') != null)
{
	$updateBillAction = 'edit_index';
}
$sidebar = array();
//@formatter:off
if (($bill['Submitter']['id'] == $this -> Session -> read('User.id') && $bill['Bill']['status'] < $AUTHORED) 
		|| $this -> Session -> read('Sga.id') != null)//@formatter:on
{
	$sidebar[] = $this -> Html -> link(__('Update Bill', true), array(
		'action' => "general_info",
		$bill['Bill']['id']
	));
}

if ($bill['Bill']['status'] == $CREATED)
{
	$sidebar[] = $this -> Html -> link('Update Line Items', array(
		'controller' => 'line_items',
		'action' => 'index',
		$bill['Bill']['id'],
		'Submitted'
	));
	$sidebar[] = $this -> Html -> link(__('Submit Bill', true), array(
		'action' => 'submit',
		$bill['Bill']['id']
	));
}
else if ($bill['Bill']['status'] == $AUTHORED && $sga_exec)
{
	$sidebar[] = $this -> Html -> link(__('Place on Agenda', true), array(
		'action' => 'putOnAgenda',
		$bill['Bill']['id']
	));
}
else if ($bill['Bill']['status'] == $AGENDA && $sga_exec)
{
	$sidebar[] = $this -> Html -> link(__('GSS Votes', true), array(
		'action' => 'votes',
		$bill['Bill']['id'],
		'gss_id',
		$bill['GSS']['id']
	));
	$sidebar[] = $this -> Html -> link(__('UHR Votes', true), array(
		'action' => 'votes',
		$bill['Bill']['id'],
		'uhr_id',
		$bill['UHR']['id']
	));
}
if ($bill['Bill']['status'] == $CONFERENCE && $sga_exec)
{
	$sidebar[] = $this -> Html -> link(__('Conference GSS Votes', true), array(
		'action' => 'votes',
		$bill['Bill']['id'],
		'gcc_id',
		$bill['GCC']['id']
	));
	$sidebar[] = $this -> Html -> link(__('Conference UHR Votes', true), array(
		'action' => 'votes',
		$bill['Bill']['id'],
		'ucc_id',
		$bill['UCC']['id']
	));
}
if ($bill['Bill']['status'] < $AGENDA && ($sga_exec || $this -> Session -> read('User.id') == $bill['Submitter']['id']))
{
	$sidebar[] = $this -> Html -> link('Delete Bill', array(
		'action' => 'delete',
		$bill['Bill']['id']
	), array('style' => 'color:red'));
}
if ($sidebar != null)
{
	echo $this -> Html -> nestedList($sidebar, array(), array('id' => 'underline'));
}
