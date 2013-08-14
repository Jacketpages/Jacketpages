<?php
/**
 * @author Stephen Roca
 * @since 8/14/2013
 */

$sgasubList = array($this -> Html -> link('View SGA Members', array(
		'controller' => 'sga_people',
		'action' => 'index'
	)));
if ($sga_user)
{
	$sgasubList[] = $this -> Html -> link('View Budgets', array(
		'controller' => 'budgets',
		'action' => 'index',
	));

	$sgasubList[] = $this -> Html -> link('View Bills on Agenda', array(
		'controller' => 'bills',
		'action' => 'onAgenda',
	));
}

$sgaList = array($this -> Html -> link('Student Government', '#') => $sgasubList);

echo $this -> Html -> nestedList($sgaList);

?>
