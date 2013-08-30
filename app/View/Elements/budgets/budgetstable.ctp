<?php
/**
 * @author Stephen Roca
 * @since 8/30/2013
 */
echo $this -> Html -> tableBegin(array('class'=> 'listing'));
echo $this -> Html -> tableheaders(array(
	$this -> Paginator -> sort('title', 'Title'),
	$this -> Paginator -> sort('number', 'Number'),
	$this -> Paginator -> sort('category', 'Category'),
	$this -> Paginator -> sort('Status.name', 'Status'),
	$this -> Paginator -> sort('submit_date', 'Submit Date')
), array('class' => 'links'));
foreach ($budgets as $budget)
{

}
echo $this -> Html -> tableEnd();
?>
