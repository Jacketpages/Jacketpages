<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */
$this -> Paginator -> options(array(
	'update' => '#forupdate',
	'indicator' => '#indicator',
	'evalScripts' => true,
	'before' => $this -> Js -> get('#listing') -> effect('fadeOut', array('buffer' => false)),
	'complete' => $this -> Js -> get('#listing') -> effect('fadeIn', array('buffer' => false)),
));
$this -> extend('/Common/list');
$this -> assign('title', 'Budgets');
$this -> start('sidebar');
echo $this -> Html -> nestedList(array($this -> Html -> link(__('Create Budget', true), array(
		'controller' => 'budgets',
		'action' => 'submit',
		$orgId
	))), array(), array('id'=>'underline'));

$this -> end();
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
	<?php
	echo $this -> element('budgets\budgetstable', array('budgets' => $budgets));
	echo $this -> element('paging');
?></div>
<?php
$this -> end();
?>
?>
