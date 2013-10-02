<?php
$this -> extend('/Common/common');
$this -> assign('title', 'Roster');
$this -> Html -> addCrumb('All Organizations', '/organizations');
$this -> Html -> addCrumb('My Organizations', '/organizations/my_orgs/'.$this -> Session -> read('User.id'));
$this -> Html -> addCrumb($orgName, '/organizations/view/'.$orgId);
$this -> Html -> addCrumb('Roster', $this->here);

$this -> start('middle');

echo $this -> Html -> tag('h1', 'Officers');
if($officers)
{
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		'Officer (Click to Edit)',
		'Role',
		'Email',
		'Phone',
		''
	));
	foreach ($officers as $officer)
	{
		echo $this -> Html -> tableCells(array(
			$this -> Html -> link($officer['Membership']['name'], array(
				'controller' => 'memberships',
				'action' => 'edit',
				$officer['Membership']['id']		
			)),
			$officer['Membership']['role'],
			$this->Text->autoLinkEmails($officer['User']['email']),
			$officer['User']['phone'],
			$this -> Html -> link('Remove', array(
				'controller' => 'memberships',
				'action' => 'delete',
				$officer['Membership']['id'],
				$orgId
			))
		));
	
	}
	echo $this -> Html -> tableEnd();
} else
{
	echo $this -> Html -> para(null, "No officers.");
}

echo $this -> Html -> tag('h1', 'Members');
if($members)
{
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		'Name (Click to Edit)',
		'Email',
		'Phone',
		''
	));
	foreach ($members as $member)
	{
		echo $this -> Html -> tableCells(array(
			$this -> Html -> link($member['Membership']['name'], array(
				'controller' => 'memberships',
				'action' => 'edit',
				$member['Membership']['id']		
			)),
			$this->Text->autoLinkEmails($member['User']['email']),
			$member['User']['phone'],
			$this -> Html -> link('Remove', array(
				'controller' => 'memberships',
				'action' => 'delete',
				$member['Membership']['id'],
				$orgId
			))
		));
	
	}
	echo $this -> Html -> tableEnd();
} else
{
	echo $this -> Html -> para(null, "No members.");	
}

echo $this -> Html -> tag('h1', 'Pending Members');
if($pending_members)
{
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		'Name (Click to Edit)',
		'Email',
		'Phone',
		''
	));
	foreach ($pending_members as $pending_member)
	{
		echo $this -> Html -> tableCells(array(
			$this -> Html -> link($pending_member['Membership']['name'], array(
				'controller' => 'memberships',
				'action' => 'edit',
				$pending_member['Membership']['id']		
			)),
			$this->Text->autoLinkEmails($pending_member['User']['email']),
			$pending_member['User']['phone'],
			$this -> Html -> link('Accept', array(
				'controller' => 'memberships',
				'action' => 'accept',
				$pending_member['Membership']['id'],
				$orgId
			))
		));
	
	}
	echo $this -> Html -> tableEnd();
} else
{
	echo $this -> Html -> para(null, 'No pending members.');	
}
$this -> end();
?>