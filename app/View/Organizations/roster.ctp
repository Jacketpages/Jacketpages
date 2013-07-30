<?php
$this -> extend('/Common/common');
$this -> assign('title', 'Roster');
$this -> start('middle');

echo $this -> Html -> tag('h1', 'Officers');
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableHeaders(array(
	'Officer (Edit)',
	'Role',
	$this -> Html -> link('Remove', array('controller' => 'membership', 'action' => 'delete'))
));
foreach ($officers as $officer)
{
	echo $this -> Html -> tableCells(array(
		$officer['Membership']['name'],
		$officer['Membership']['role']
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
	echo $this -> Html -> tableCells(array($member['Membership']['name']));

}
echo $this -> Html -> tableEnd();

echo $this -> Html -> tag('h1', 'Pending Members');
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableHeaders(array(
	'Pending Member (Edit)',
	$this -> Html -> link('Accept', array('controller' => 'membership', 'action' => 'accept'))
));
foreach ($pending_members as $pending_member)
{
	echo $this -> Html -> tableCells(array($pending_member['Membership']['name']));

}
echo $this -> Html -> tableEnd();
$this -> end();
?>