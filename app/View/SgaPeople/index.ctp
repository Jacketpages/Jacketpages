<?php
/**
 * @author Stephen Roca
 * @since 06/22/2012
 */
$this -> Html -> addCrumb('SGA Records', '/sga_people');
$this -> Paginator -> options(array(
	'update' => '#forupdate',
	'indicator' => '#indicator',
	'evalScripts' => true,
	'before' => $this -> Js -> get('#listing') -> effect('fadeOut', array('buffer' => false)),
	'complete' => $this -> Js -> get('#listing') -> effect('fadeIn', array('buffer' => false)),
));
$this -> extend("/Common/list");
$this -> assign('title', 'SGA Records');

if($sga_exec){
	$this -> start('sidebar');
	echo $this -> Html -> nestedList(array(
		$this -> Html -> link('Add SGA Member', array('action' => 'add'))));
	$this -> end();
}

$this -> start('search');
?>

<div id="alphabet">
	<div id="leftHalf">
		<?php
		echo $this -> Form -> create();
		echo $this -> Form -> input('keyword', array(
			'label' => array(
				'text' => 'Search',
				'style' => 'display:inline'
			),
			'id' => 'search',
			'default' => $this -> Session -> read('Search.keyword'),
			'width' => '80%'
		));
		echo $this -> Form -> end();
		?>
	</div>
	<div id="rightHalf">
		<ul>
			<?php
			// TODO Clean up this whole alphabet thing. Is there an easier way?
			// set up alphabet
			$alpha = range('A', 'Z');
			for ($i = 0; $i < count($alpha); $i++)
			{
				echo "<li>\n";
				echo $this -> Html -> link($alpha[$i], array(
					'controller' => strtolower($this -> params['controller']),
					'action' => 'index',
					strtolower($alpha[$i])
				));
				echo "&nbsp";
				echo "</li>\n";
			}
			echo "<li>\n";
			echo $this -> Html -> link('ALL', array(
				'controller' => strtolower($this -> params['controller']),
				'action' => 'index'
			));
			?>
			</li>
		</ul>
	</div>
</div>
<?php
$this -> end();
$this -> start('listing');
?>
<div id='forupdate'>
	<table class='listing'>
		<?php
		$headers = array(
			$this -> Paginator -> sort('name', 'Name'),
			$this -> Paginator -> sort('house', 'House'),
			$this -> Paginator -> sort('department', 'Department')
		);
		if ($sga_exec)
		{
			$headers[] = $this -> Paginator -> sort('status', 'Status');
			$headers[] = "";
			//$headers[] = "";
		}
		// Print out all of the table headers
		echo $this -> Html -> tableHeaders($headers, array('class' => 'links'));
		foreach ($sgapeople as $sgaperson)
		{
			$cells = array(
				$this -> Html -> link($sgaperson['User']['name'], array(
					'controller' => 'users',
					'action' => 'view',
					$sgaperson['SgaPerson']['user_id']
				)),
				$sgaperson['SgaPerson']['house'],
				$sgaperson['SgaPerson']['department']
			);
			if ($sga_exec)
			{
				$cells[] = $sgaperson['SgaPerson']['status'];
				$cells[] = $this -> Html -> link('Edit', array(
					'action' => 'edit',
					$sgaperson['SgaPerson']['id']
				));
				/*
				if($admin){
					$cells[] = $this -> Html -> link(__('Delete', true), array(
						'action' => 'delete',
						$sgaperson['SgaPerson']['id']
					), null, sprintf(__('Are you sure you want to delete %s?', true), $sgaperson['User']['name']));
				}
				*/
			}
			echo $this -> Html -> tableCells($cells);
		}
		?>
	</table>
	<?php
	echo $this -> element('paging');
	?>
</div>
<?php
$this -> end();
?>