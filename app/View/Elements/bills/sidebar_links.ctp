<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

$sidebar = array();
//@formatter:off
if (($bill['Submitter']['id'] == $this -> Session -> read('User.id') && $bill['Bill']['status'] == $CREATED) 
		|| ($this -> Session -> read('Sga.id') != null && $bill['Bill']['status'] == $AWAITING_AUTHOR && $this -> Session -> read('Sga.id') == $bill['Authors']['grad_auth_id'])
		|| ($this -> Session -> read('Sga.id') != null && $bill['Bill']['status'] == $AWAITING_AUTHOR && $this -> Session -> read('Sga.id') == $bill['Authors']['undr_auth_id'])
		|| $admin)//@formatter:on
{
	$sidebar[] = $this -> Html -> link(__('Update Details', true), array(
		'action' => "general_info",
		$bill['Bill']['id']
	));
}
if (($bill['Submitter']['id'] == $this -> Session -> read('User.id') && $bill['Bill']['status'] == $CREATED) 
		|| ($this -> Session -> read('Sga.id') != null && $bill['Bill']['status'] == $AWAITING_AUTHOR && $this -> Session -> read('Sga.id') == $bill['Authors']['grad_auth_id'])
		|| ($this -> Session -> read('Sga.id') != null && $bill['Bill']['status'] == $AWAITING_AUTHOR && $this -> Session -> read('Sga.id') == $bill['Authors']['undr_auth_id'])
		|| $bill['Bill']['status'] == $AUTHORED && $sga_exec
		|| $bill['Bill']['status'] == $AGENDA && $sga_exec
		|| $admin)//@formatter:on
{
	if($bill['Bill']['type'] != 'Resolution'){
		$sidebar[] = $this -> Html -> link('Update Line Items', array(
			'controller' => 'line_items',
			'action' => 'index',
			$bill['Bill']['id'],
			'Submitted'
		));
	}
}

if ($bill['Bill']['status'] == $CREATED && $bill['Submitter']['id'] == $this -> Session -> read('User.id')
		|| $admin)
{
	$sidebar[] = $this -> Html -> link(__('Submit Bill', true), array(
		'action' => 'submit',
		$bill['Bill']['id']
	));
}
if ($bill['Bill']['status'] == $AUTHORED && $sga_exec
		|| $admin)
{
	$sidebar[] = $this -> Html -> link(__('Place on Agenda', true), array(
		'action' => 'putOnAgenda',
		$bill['Bill']['id']
	));
}
//Mark as Passed Button
if ($bill['Bill']['status'] == $AGENDA && $sga_exec
    || $admin)
{
    $sidebar[] = $this -> Html -> link(__('Mark as Passed', true), array(
        'action' => 'markAsPassed',
        $bill['Bill']['id']
    ));
}
if ($bill['Bill']['status'] == $AGENDA && $sga_exec
		|| $admin)
{
	if (strcmp($bill['Bill']['category'], 'Graduate') == 0
	 	|| strcmp($bill['Bill']['category'], 'Joint') == 0
		|| $admin)
		$sidebar[] = $this -> Html -> link(__('GSS Votes', true), array(
			'action' => 'votes',
			$bill['Bill']['id'],
			'gss_id',
			$bill['GSS']['id']
		));
	if (strcmp($bill['Bill']['category'], 'Joint') == 0
		|| strcmp($bill['Bill']['category'], 'Undergraduate') == 0
		|| $admin)
		$sidebar[] = $this -> Html -> link(__('UHR Votes', true), array(
			'action' => 'votes',
			$bill['Bill']['id'],
			'uhr_id',
			$bill['UHR']['id']
		));
}
if ($bill['Bill']['status'] == $CONFERENCE && $sga_exec
		|| $admin)
{
	if (strcmp($bill['Bill']['category'], 'Joint') == 0
		|| strcmp($bill['Bill']['category'], 'Graduate') == 0
		|| $admin)
		$sidebar[] = $this -> Html -> link(__('Conference GSS Votes', true), array(
			'action' => 'votes',
			$bill['Bill']['id'],
			'gcc_id',
			$bill['GCC']['id']
		));
	if (strcmp($bill['Bill']['category'], 'Joint') == 0
		|| strcmp($bill['Bill']['category'], 'Undergraduate') == 0
		|| $admin)
		$sidebar[] = $this -> Html -> link(__('Conference UHR Votes', true), array(
			'action' => 'votes',
			$bill['Bill']['id'],
			'ucc_id',
			$bill['UCC']['id']
		));
}
if ($bill['Bill']['status'] < $AGENDA && ($sga_exec || $this -> Session -> read('User.id') == $bill['Submitter']['id'])
		|| $admin)
{
	$sidebar[] = $this -> Html -> link('Delete Bill', array(
		'action' => 'delete',
		$bill['Bill']['id']
	), array('style' => 'color:red'),
	__('Are you sure you want to delete this bill?'));
}
if ($sidebar != null)
{
	echo $this -> Html -> nestedList($sidebar, array());
}
