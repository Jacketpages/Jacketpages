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
$sidebar[] = '<a>Organization&nbsp;Category<br /><br />
<div id=\'category\'>'.
$this -> Form -> create().
$this -> Form -> input('category', array(
		'label' => false,
		'default' => $this -> Session -> read('Search.category'),
		'options' => array(
			'' => 'All',
			'CPC Sorority' => 'CPC Sorority',
			'Cultural/Diversity' => 'Cultural/Diversity',
			'Departmental Sponsored' => 'Departmental Sponsored',
			'Departments' => 'Departments',
			'Governing Boards' => 'Governing Boards',
			'Honor Society' => 'Honor Society',
			'IFC Fraternity' => 'IFC Fraternity',
			'Institute Recognized' => 'Institute Recognized',
			'MGC Chapter' => 'MGC Chapter',
			'None' => 'None',
			'NPHC Chapter' => 'NPHC Chapter',
			'Production/Performance/Publication' => 'Production/Performance/Publication',
			'Professional/Departmental' => 'Professional/Departmental',
			'Recreational/Sports/Leisure' => 'Recreational/Sports/Leisure',
			'Religious/Spiritual' => 'Religious/Spiritual',
			'Residence Hall Association' => 'Residence Hall Association',
			'Service/Political/Educational' => 'Service/Political/Educational',
			'Student Government' => 'Student Government',
			'Umbrella' => 'Umbrella',
			'Other' => 'Other'
		)
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
		source : avaliableTags
	});
	});
</script>
<?php
$this -> end();
?>
