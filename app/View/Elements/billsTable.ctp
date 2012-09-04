<div id="accordion">
	<h3><a href="#">Filters</a></h3>
	<div>
		<p>
			Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate.
		</p>
	</div>
</div>
<table class="listing">
	<?php
	echo $this -> Html -> tableheaders(array(
		$this -> Paginator -> sort('TITLE', 'Title'),
		$this -> Paginator -> sort('NUMBER', 'Number'),
		$this -> Paginator -> sort('CATEGORY', 'Category'),
		$this -> Paginator -> sort('Status.NAME', 'Status'),
		$this -> Paginator -> sort('SUBMIT_DATE', 'Submit Date')
	), array('class' => 'links'));
	foreach ($bills as $bill)
	{
		echo $this -> Html -> tableCells(array(
			$this -> Html -> link($bill['Bill']['TITLE'], array(
				'controller' => 'bills',
				'action' => 'view',
				$bill['Bill']['ID']
			)),
			$bill['Bill']['NUMBER'],
			$bill['Bill']['CATEGORY'],
			$bill['Status']['NAME'],
			$bill['Bill']['SUBMIT_DATE']
		));
	}
	?>
</table>
<script>
	$(function() {
		$("#accordion").accordion({
			collapsible : true
		});
	}); 
</script>