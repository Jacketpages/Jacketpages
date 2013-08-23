<?php
/**
 * @author Stephen Roca
 * @since 8/14/2013
 */
$orgsubList = array($this -> Html -> link('View All Organizations', array(
		'controller' => 'organizations',
		'action' => 'index'
	)));

if ($gt_member)
{
	$orgsubList[] = $this -> Html -> link('View My Organizations', array(
		'controller' => 'organizations',
		'action' => 'my_orgs',
		$this -> Session -> read('User.id')
	));

	$orgsubList[] = $this -> Html -> link('View Inactive Organizations', array(
		'controller' => 'organizations',
		'action' => 'inactive_orgs'
	));
}
$orgList = array($this -> Html -> link('Organizations', '#') => $orgsubList);

echo $this -> Html -> nestedList($orgList);
