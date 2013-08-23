<div id="accordion">
	<a href="#">Filters</a>
	<div>
		<div style="float: left; width: 45%;">
			<ul>
				<?php
				echo $this -> Form -> input('from', array(
				'label' => 'From Status',
					'options' => array(
						1 => 'Created',
						2 => 'Awaiting Author',
						3 => 'Authored',
						4 => 'Agenda',
						5 => 'Conference',
						6 => 'Passed',
						7 => 'Failed',
						8 => 'Tabled'
					),
					'selected' => $this -> Session -> read('Bill.from')
				));
				echo $this -> Form -> input('to', array(
				'label' => 'To Status',
					'options' => array(
						1 => 'Created',
						2 => 'Awaiting Author',
						3 => 'Authored',
						4 => 'Agenda',
						5 => 'Conference',
						6 => 'Passed',
						7 => 'Failed',
						8 => 'Tabled'
					),
					'selected' => ($this -> Session -> read('Bill.to') == null ? 7 : $this -> Session -> read('Bill.to'))
				));
				?>
			</ul>
		</div>
		<div style="float: right; width: 45%;">
			<?php
			echo $this -> Form -> input('category', array(
				'options' => array(
					'All' => 'All',
					'Joint' => 'Joint',
					'Undergraduate' => 'Undergraduate',
					'Graduate' => 'Graduate',
				),
				'selected' => $this -> Session -> read('Category')
			));

			echo $this -> Form -> end();
			?>
		</div>
	</div>
</div>
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