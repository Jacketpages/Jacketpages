<?php
/**
 * @author Michael Ellis
 * @since 10/02/2013
 */
 $this -> Paginator -> options(array(
	'update' => '#forupdate',
	'indicator' => '#indicator',
	'evalScripts' => true,
	'before' => $this -> Js -> get('#listing') -> effect('fadeOut', array('buffer' => false)),
	'complete' => $this -> Js -> get('#listing') -> effect('fadeIn', array('buffer' => false)),
));
$this -> Html -> addCrumb($org_name, '/organizations/view/'.$org_id);
$this -> Html -> addCrumb('Finance Ledger', $this -> here);
$this -> extend("/Common/list");
$this -> start('sidebar');
$this -> end();
$this -> assign("title", "Finance Ledger");
$this -> start('listing');
echo $this -> Html -> tag('h1', 'Bills');?>
<table class="listing">
	<?php
	echo $this -> Html -> tableheaders(array(
		$this -> Paginator -> sort('title', 'Title'),
		$this -> Paginator -> sort('number', 'Number'),
		$this -> Paginator -> sort('category', 'Category'),
		$this -> Paginator -> sort('Status.name', 'Status'),
		$this -> Paginator -> sort('submit_date', 'Submit Date')
	), array('class' => 'links'));
	foreach ($bills as $bill)
	{
		echo $this -> Html -> tableCells(array(
			$this -> Html -> link($bill['Bill']['title'], array(
				'controller' => 'bills',
				'action' => 'view',
				$bill['Bill']['id']
			)),
			$bill['Bill']['number'],
			$bill['Bill']['category'],
			$bill['Status']['name'],
			$bill['Bill']['submit_date']
		));
	}
	?>
</table>
<?php
echo $this -> element('paging');
//echo $this -> Html -> tag('h1', 'Budgets');
$this -> end();
?>
