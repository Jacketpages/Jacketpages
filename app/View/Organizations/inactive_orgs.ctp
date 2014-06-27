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
$this -> extend('/Common/list');
$this -> assign('title', 'Inactive Organizations');
$this -> Html -> addCrumb('All Organizations', '/organizations');
$this -> Html -> addCrumb('Inactive Organizations', $this->here);
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
$sidebar[] = $this -> Html -> link('Silver Leaf Certified Organizations', array('action' => 'silverleaf'));
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
?>
<?php
$this -> end();

$this -> start('search');
echo $this -> element('search', array('action' => 'inactive_orgs', 'endForm' => 1));
 $this -> end();
	$this -> start('listing');
?>
<div id='forupdate'>
	<?php
	echo $this -> element('organizationTable', array(
		'organizations' => $organizations		
	));
	echo $this -> element('paging');
	// Implement Ajax for this page.
	echo $this -> Js -> writeBuffer();
	?>
</div>
</div>
<script type="text/javascript">
	$(function() {
var avaliableTags = <?php echo json_encode($names_to_autocomplete)?>
	;
	$("#search").autocomplete({
		source : avaliableTags,
		select: function(event, ui){
			window.location.href = '<?php echo $this->Html->url(array('controller' => 'organizations', 'action' => 'view'), true);?>/'+ui.item.id;
		}
	});
	});
</script>
<?php
$this -> end();
?>
