<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 * 
 * @param $organization - The selected organization's information
 * @param $president - The president of the selected organization
 * @param $treasurer - The treasurer of the selected organization
 * @param $advisor - The advisor of the selected organization
 * @param $officers - The remaining officers of the selected organization
 * @param $members - The members of the selected organization
 * @param $pending_members - The pending members of the selected organization
 */
 
// TODO sort out the officers issues
// TODO fill in budgets, bills, etc.
// TODO rethink the sidebar links
 
// Define which view this view extends.
$this -> extend('/Common/common');

// Define any crumbs for this view.
echo $this -> Html -> addCrumb('All Organizations', '/organizations');
echo $this -> Html -> addCrumb($organization['Organization']['NAME'], '/organizations/view/' . $organization['Organization']['ID']);

// Define any extra sidebar links for this view
$this -> start('sidebar');
echo $this -> Html -> nestedList(array(
	$this -> Html -> link(__('Edit Information', true), array(
		'action' => 'edit',
		$organization['Organization']['ID']
	)),
	$this -> Html -> link(__('Edit Logo', true), array(
		'action' => 'addlogo',
		$organization['Organization']['ID'],
		'officer' => false,
		'admin' => false,
		'owner' => false
	)),
	$this -> Html -> link(__('Edit Officers/Roster', true), array(
		'controller' => 'organizations',
		'action' => 'roster',
		$organization['Organization']['ID'],
		'admin' => false
	)),
	$this -> Html -> link(__('View/Add Documents', true), array(
		'controller' => 'charters',
		'action' => 'index',
		$organization['Organization']['ID'],
		'admin' => false
	)),
	$this -> Html -> link(__('Join Organization', true), array(
		'action' => 'join',
		$organization['Organization']['ID'],
		'admin' => false,
		'owner' => false,
		'officer' => false
	), null, sprintf(__('Are you sure you want to join %s?', true), $organization['Organization']['NAME'])),
	$this -> Html -> link(__('Delete Organization', true), array(
		'action' => 'delete',
		$organization['Organization']['ID']
	), null, sprintf(__('Are you sure you want to delete %s?', true), $organization['Organization']['NAME']))
), array(), array('id' => 'underline'));
$this -> end();

// Define the main information for this view.
$this -> assign('title', $organization['Organization']['NAME']);
$this -> start('middle');
echo $this -> Html -> tag('h3', 'Officers:');
echo $this -> Html -> tableBegin(array(
	'class' => 'listing',
	'id' => 'halftable'
));
echo $this -> Html -> tableCells(array(
	$president['Membership']['NAME'],
	$president['Membership']['TITLE']
));
if ($treasurer['Membership']['NAME'])
{
	echo $this -> Html -> tableCells(array(
		$treasurer['Membership']['NAME'],
		$treasurer['Membership']['TITLE']
	));
}
if ($advisor['Membership']['NAME'])
{
	echo $this -> Html -> tableCells(array(
		$advisor['Membership']['NAME'],
		$advisor['Membership']['TITLE']
	));
}
foreach ($officers as $officer)
{
	echo $this -> Html -> tableCells(array(
		$officer['Membership']['NAME'],
		$officer['Membership']['TITLE']
	));
}
echo $this -> Html -> tableEnd();

// Print out the Organization's description and other
// general information.
echo $this -> Html -> tag('h1', 'Description');
echo $this -> Html -> para('leftalign', $organization['Organization']['DESCRIPTION']);
echo $this -> Html -> nestedList(array(
	'Status: ' . $organization['Organization']['STATUS'],
	'Organization Contact: ' . $organization['User']['NAME'],
	'External Website: ' . $this -> Html -> link($organization['Organization']['WEBSITE']),
	'Meetings: ' . $organization['Organization']['MEETING_INFO']
));
echo $this -> Html -> tag('h1', 'Budgets');
echo $this -> Html -> tag('h1', 'Bills');

// Print out the members table information if it exists
echo $this -> Html -> tag('h1', 'Members');
if ($members)
{
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		'Member',
		'Email',
		'Phone'
	));
	foreach ($members as $member)
	{
		echo $this -> Html -> tableCells(array(
			$this -> Html -> link($member['Membership']['NAME'], array(
				'controller' => 'memberships',
				'action' => 'edit'
			)),
			$member['User']['EMAIL'],
			$member['User']['PHONE']
		));
	}
	echo $this -> Html -> tableEnd();
}
else
{
	echo $this -> Html -> para(null, "No members.");
}

// Print out the members table information if it exists
echo $this -> Html -> tag('h1', 'Pending Members');
if ($pending_members)
{
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		'Member',
		'Email',
		'Phone'
	));
	foreach ($pending_members as $pending_member)
	{
		echo $this -> Html -> tableCells(array(
			$pending_member['Membership']['NAME'],
			$pending_member['User']['EMAIL'],
			$pending_member['User']['PHONE']
		));
	}
	echo $this -> Html -> tableEnd();
}
else
{
	echo $this -> Html -> para(null, 'No pending members.');
}
$this -> end();
?>