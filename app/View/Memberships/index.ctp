<?php
/**
 * @author Stephen Roca
 * @since 8/30/2013
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Roster');
$this -> start('middle');

echo $this -> Html -> tag('h1', 'Officers');
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableHeaders(array(
	'Officer (Edit)',
	'Role',
	'Title',
	''
));
foreach ($officers as $officer)
{
	echo $this -> Html -> tableCells(array(
		$this -> Html -> link($officer['Membership']['name'], array(
			'controller' => 'memberships',
			'action' => 'edit',
			$officer['Membership']['id'],
			$orgId
		)),
		$officer['Membership']['role'],
		$officer['Membership']['title'],
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