<div id="accordion">
	<h3><a href="#">Filters</a></h3>
	<div>
		<div style="float: left; width: 50%;">
			<ul>
				Status
				<?php
				echo $this -> Html -> nestedList(array(
					$this -> Form -> input('On Agenda', array(
						'type' => 'checkbox',
						'label' => array(
							'text' => 'On Agenda',
							'id' => 'checkboxlist'
						),
						'id' => 'checkboxlist',
						'checked' => $this -> Session -> read($this -> name . '.On Agenda')
					)),
					$this -> Form -> input('Authored', array(
						'type' => 'checkbox',
						'label' => array(
							'text' => 'Authored',
							'id' => 'checkboxlist'
						),
						'id' => 'checkboxlist',
						'checked' => $this -> Session -> read($this -> name . '.Authored')
					)),
					$this -> Form -> input('Awaiting Author', array(
						'type' => 'checkbox',
						'label' => array(
							'text' => 'Awaiting Author',
							'id' => 'checkboxlist'
						),
						'id' => 'checkboxlist',
						'checked' => $this -> Session -> read($this -> name . '.Awaiting Author')
					)),
					$this -> Form -> input('Passed', array(
						'type' => 'checkbox',
						'label' => array(
							'text' => 'Passed',
							'id' => 'checkboxlist'
						),
						'id' => 'checkboxlist',
						'checked' => $this -> Session -> read($this -> name . '.Passed')
					)),
					$this -> Form -> input('Failed', array(
						'type' => 'checkbox',
						'label' => array(
							'text' => 'Failed',
							'id' => 'checkboxlist'
						),
						'id' => 'checkboxlist',
						'checked' => $this -> Session -> read($this -> name . '.Failed')
					)),
					$this -> Form -> input('Archived', array(
						'type' => 'checkbox',
						'label' => array(
							'text' => 'Archived',
							'id' => 'checkboxlist'
						),
						'id' => 'checkboxlist',
						'checked' => $this -> Session -> read($this -> name . '.Archived')
					))
				), array(), array('id' => 'filter'));
				?>
			</ul>
		</div>
		<div style="float: right; width: 50%;">
			Category
			<?php

			echo $this -> Html -> nestedList(array(
				$this -> Form -> input('Joint', array(
					'type' => 'checkbox',
					'label' => array(
						'text' => 'Joint',
						'id' => 'checkboxlist'
					),
					'id' => 'checkboxlist',
					'checked' => $this -> Session -> read($this -> name . '.Joint')
				)),
				$this -> Form -> input('Conference', array(
					'type' => 'checkbox',
					'label' => array(
						'text' => 'Conference',
						'id' => 'checkboxlist'
					),
					'id' => 'checkboxlist',
					'checked' => $this -> Session -> read($this -> name . '.Conference')
				)),
				$this -> Form -> input('Undergraduate', array(
					'type' => 'checkbox',
					'label' => array(
						'text' => 'Undergraduate',
						'id' => 'checkboxlist'
					),
					'id' => 'checkboxlist',
					'checked' => $this -> Session -> read($this -> name . '.Undergraduate')
				)),
				$this -> Form -> input('Graduate', array(
					'type' => 'checkbox',
					'label' => array(
						'text' => 'Graduate',
						'id' => 'checkboxlist'
					),
					'id' => 'checkboxlist',
					'checked' => $this -> Session -> read($this -> name . '.Graduate')
				))), array(), array('id' => 'filter')
			);
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
			active: false
		});
		
	}); 
</script>