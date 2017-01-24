<?php
/**
 * @author Stephen Roca
 * @since 06/26/2012
 */
?>
<<!--div id="alphabet">
	<div id="leftHalf">
		<?php
/*		echo $this -> Form -> create();
		echo $this -> Form -> input('keyword', array(
			'label' => array(
				'text' => 'Search',
				'style' => 'display:inline'
			),
			'id' => 'search',
			'default' => $this -> Session -> read('Search.keyword'),
			'width' => '80%'
		));
		if ($endForm)
		{
		 echo $this -> Form -> end();			
		}
		*/?>
	</div>
	<div id="rightHalf">
		<ul>
			<?php
/*			// TODO Clean up this whole alphabet thing. Is there an easier way?
			// set up alphabet
			$alpha = range('A', 'Z');
			for ($i = 0; $i < count($alpha); $i++)
			{
				echo "<li>\n";
					echo $this -> Html -> link($alpha[$i], array(
						'controller' => strtolower($this -> params['controller']),
						'action' => $action,
						strtolower($alpha[$i])
					));
				echo "&nbsp";
				echo "</li>\n";
			}
			echo "<li>\n";
			echo $this -> Html -> link('ALL', array(
				'controller' => strtolower($this -> params['controller']),
				'action' => $action
			));
			*/?>
			</li>
		</ul>
	</div>
</div>-->