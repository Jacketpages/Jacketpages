<?php
$this -> extend('/Common/common');
$this -> assign('title', 'Roster');
$this -> Html -> addCrumb('All Organizations', '/organizations');
$this -> Html -> addCrumb('My Organizations', '/organizations/my_orgs/'.$this -> Session -> read('User.id'));
$this -> Html -> addCrumb($orgName, '/organizations/view/'.$orgId);
$this -> Html -> addCrumb('Roster', $this->here);

$this -> start('middle');

echo $this -> Html -> tag('h1', 'Officers');
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableHeaders(array(
	'Officer (Edit)',
	'Role',
	''
));
foreach ($officers as $officer)
{
	echo $this -> Html -> tableCells(array(
		$officer['Membership']['name'],
		$officer['Membership']['role'],
		$this -> Html -> link('Remove', array(
			'controller' => 'memberships',
			'action' => 'delete',
			$officer['Membership']['id'],
			$orgId
		))
	));

}
echo $this -> Html -> tableEnd();

echo $this -> Html -> tag('h1', 'Members');
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableHeaders(array(
	'Member (Edit)',
	''
));
foreach ($members as $member)
{
	echo $this -> Html -> tableCells(array(
		$member['Membership']['name'],
		$this -> Html -> link('Remove', array(
			'controller' => 'memberships',
			'action' => 'delete',
			$member['Membership']['id'],
			$orgId
		))
	));

}
echo $this -> Html -> tableEnd();

echo $this -> Html -> tag('h1', 'Pending Members');
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableHeaders(array(
	'Pending Member (Edit)',
	''
));
foreach ($pending_members as $pending_member)
{
	echo $this -> Html -> tableCells(array(
		$pending_member['Membership']['name'],
		$this -> Html -> link('Accept', array(
			'controller' => 'memberships',
			'action' => 'accept',
			$pending_member['Membership']['id'],
			$orgId
		))
	));

}
echo $this -> Html -> tableEnd();
$this -> end();
?>