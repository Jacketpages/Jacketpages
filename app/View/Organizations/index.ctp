<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 */
$this -> Paginator -> options(array(
	'update' => '#forupdate',
	'indicator' => '#indicator',
	'evalScripts' => true,
	'before' => $this -> Js -> get('#listing') -> effect('fadeOut', array('buffer' => false)),
	'complete' => $this -> Js -> get('#listing') -> effect('fadeIn', array('buffer' => false)),
));
echo $this -> Html -> addCrumb('All Organizations', '/organizations');
$this -> extend('/Common/list');
$this -> assign('title', 'Organizations');
$this -> start('sidebar');
$sidebar = array();
if ($orgCreatePerm)
{
	$sidebar[] = $this -> Html -> link('Create Organization', array('action' => 'add'));
}
if ($orgExportPerm)
{
	$sidebar[] = $this -> Html -> link('Export Organizations', array(
		'admin' => false,
		'action' => 'export'
	));
}
$sidebar[] = $this -> Html -> link('Silver Leaf Certified Organization', array('action' => 'silverleaf'));
$sidebar[] = '<a>Organization&nbsp;Category<br /><br />
<div id=\'category\'>'.
$this -> Form -> create().
$this -> Form -> select('Organization.category', $categories, array(
		'label' => false,
		'default' => $this -> Session -> read('Search.category'),
		'onchange' => 'submit()',
		'empty' => 'All'
	)).'</div></a>';

echo $this -> Html -> nestedList($sidebar);
$this -> end();

$this -> start('search');
?>

<div id="alphabet">
	<div id="leftHalf">
		<?php
		echo $this -> Form -> create();
		echo $this -> Form -> input('Organization.keyword', array(
			'label' => array(
				'text' => 'Search',
				'style' => 'display:inline'
			),
			'id' => 'search',
			'default' => $this -> Session -> read('Search.keyword'),
			'style' => 'display:inline'
		));
		echo $this -> Form -> end();
		?>
		<div id="div_choices" class="autocomplete"></div>
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
<?php $this -> end();
	$this -> start('listing');
?>
<div id='forupdate'>
	<?php
	echo $this -> element('organizationTable', array(
		'organizations' => $organizations,
		'admin' => $orgAdminView,
		'orgEditDeletePerm' => $orgEditDeletePerm
	));
	echo $this -> element('paging');
	// Implement Ajax for this page.
	echo $this -> Js -> writeBuffer();
	?>
</div>
<script type="text/javascript">
	$(function() {
var avaliableTags = <?php echo json_encode($names_to_autocomplete); ?>
	;
	$("#search").autocomplete(
	{
		source : avaliableTags
	});
	});
</script>
<?php
$this -> end();
?>
