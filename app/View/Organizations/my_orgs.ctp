<?php
/**
 * @author Stephen Roca
 * @since 8/24/2012
 */

$this -> extend('/Common/common');
$this -> assign('title', 'My Organizations and Positions');
$this -> start('middle');
echo $this -> Html -> tag('h1', 'Executive Positions');
echo $this -> Html -> tag('h1', 'General Affilitations');
if (count($memberships) > 0)
{
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		'Organization',
		'Role',
		'Start Date',
		'End Date'
	));
	foreach ($memberships as $membership)
	{
		echo $this -> Html -> tableCells(array(
			$membership['Organization']['name'],
			$membership['Membership']['role'],
			$membership['Membership']['start_date'],
			$membership['Membership']['end_date']
		));
	}
	echo $this -> Html -> tableEnd();
}
$this -> end();
?>