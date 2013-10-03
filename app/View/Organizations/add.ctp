<?php
/**
 * @author Stephen Roca
 * @since 8/19/2013
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Create Organization');
echo $this -> Html -> addCrumb('All Organizations', '/organizations');
echo $this -> Html -> addCrumb('Create Organization', '/organizations/add');
$this -> start('middle');
echo $this -> Form -> create();
echo $this -> Form -> input('id');
echo $this -> Form -> input('name');
echo $this -> Form -> input('status', array(
	'options' => array(
		'Active' => 'Active',
		'Inactive' => 'Inactive',
		'Frozen' => 'Frozen'
	),
	'default' => 'Active'
));
echo $this -> Form -> input('category', array('options' => array(
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
	)));
echo $this -> Form -> hidden('contact_id', array('id' => 'contact_id'));
echo $this -> Form -> input('User.name', array(
	'label' => 'Primary Contact Name -- You must select from suggestions when they appear',
	'id' => 'userName'
));
/*echo $this -> Form -> input('User.name', array(
 'label' => 'Primary Contact',
 'id' => 'primary_contact'
 ));
 echo $this -> Form -> input('User.email', array(
 'label' => 'Primary Contact Email',
 'id' => 'primary_email'
 ));*/
echo $this -> Form -> input('description');
echo $this -> Form -> input('website');
echo $this -> Form -> input('meeting_info');
echo $this -> Form -> input('meeting_frequency');
echo $this -> Form -> input('annual_events');
echo $this -> Form -> input('dues');
echo $this -> Form -> submit('Create');
?>
</script>

	$(document).ready(function() {
	$( "#userName" ).autocomplete({
	minLength: 2,
	source: '<?php echo $this -> Html -> url(array(
			'controller' => 'users',
			'action' => 'lookupByName'
		), true);?>',
		focus: function( event, ui ) {
		$( "#userName" ).val( ui.item.name );
		return false;
		},
		select: function( event, ui ) {
		$( "#userName" ).val( ui.item.name );
		$( "#contact_id" ).val( ui.item.id );
		return false;
		}
		})
		.data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.name + " (" + item.gt_user_name + ")</a>")
		.appendTo( ul );
		};
		});
</script>

<?php $this -> end();
?>
