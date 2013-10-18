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
<script>
	$(function() {
		$("#accordion").accordion({
			collapsible : true,
			active : false
		});

	}); 
</script>