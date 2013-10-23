<?php

echo $this -> extend('/Common/list');

$this -> assign('title',"FY 20$fiscalYear Budgets");
$this -> start('listing');

echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableheaders(array(
		$this -> Paginator -> sort('Organization.name', 'Organization Name'),
		$this -> Paginator -> sort('Budget.state', 'State'),
		$this -> Paginator -> sort('Budget.last_mod_date', 'Submit Date')
	), array('class' => 'links'));
foreach($budgets as $budget)
{
	echo $this -> Html -> tableCells(array(
			$this -> Html -> link($budget['Organization']['name'], array(
				'controller' => 'budgets',
				'action' => 'view',
				$budget['Budget']['id']
			)),
			$budget['Budget']['state'],
			$budget['Budget']['last_mod_date']
		));
}
echo $this -> Html -> tableEnd();
echo $this -> element('paging');
$this -> end();
