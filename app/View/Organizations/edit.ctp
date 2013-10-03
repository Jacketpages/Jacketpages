<?php
/**
 * @author Stephen Roca
 * @since 06/21/2012
 */
// TODO grab the javascript from the admin_edit.ctp from JP and implement it
// here.
$this -> extend('/Common/common');
$this -> assign('title', 'Edit Organization');
$this -> Html -> addCrumb('All Organizations', '/organizations');
$this -> Html -> addCrumb('My Organizations', '/organizations/my_orgs/' . $this -> Session -> read('User.id'));
$this -> Html -> addCrumb($organization['Organization']['name'], '/organizations/view/' . $organization['Organization']['id']);
$this -> Html -> addCrumb('Edit Organization', $this -> here);
$this -> start('middle');
echo $this -> Form -> create('Organization');
echo $this -> Form -> hidden('id');
echo $this -> Form -> input('name', array('label' => 'Name'));
echo $this -> Form -> input('status', array(
	'label' => 'Status',
	'options' => array(
		'Active' => 'Active/Good Standing',
		'Inactive' => 'Inactive',
		'Under Review' => 'Under Review',
		'Pending' => 'Pending'
	)
));
echo $this -> Form -> input('category', array('options' => array(
		'1' => 'CPC Sorority',
		'2' => 'Cultural/Diversity',
		'3' => 'Departmental Sponsored',
		'4' => 'Departments',
		'5' => 'Governing Boards',
		'6' => 'Honor Society',
		'7' => 'IFC Fraternity',
		'8' => 'Institute Recognized',
		'9' => 'MGC Chapter',
		'10' => 'None',
		'11' => 'NPHC Chapter',
		'12' => 'Production/Performance/Publication',
		'13' => 'Professional/Departmental',
		'14' => 'Recreational/Sports/Leisure',
		'15' => 'Religious/Spiritual',
		'16' => 'Residence Hall Association',
		'17' => 'Service/Political/Educational',
		'18' => 'Student Government',
		'19' => 'Umbrella',
		'20' => 'Other'
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
echo $this -> Form -> input('description', array(
	'label' => 'Description',
	'type' => 'textarea'
));
echo $this -> Form -> input('website', array('label' => 'Website'));
echo $this -> Form -> input('meeting_info', array('label' => 'Meeting Information'));
echo $this -> Form -> input('meeting_frequency', array('label' => 'Meeting Frequency'));
echo $this -> Form -> input('annual_events', array('label' => 'Annual Events'));
echo $this -> Form -> input('dues', array('label' => 'Dues', 'id' => 'dues'));
?>
<div id='date'>
	<?php
	if ($lace)
	{
		echo $this -> Form -> input('aadipf_date', array(
			'type' => 'text',
			'minYear' => date("Y") - 1,
			'maxYear' => date("Y"),
			'dateFormat' => 'MDY',
			'label' => 'Acknowledgement of Alcohol & Illegal Drug Policy Form Date',
			'id' => 'forms'
		));
	}
	?>
</div>
</fieldset>
<?php echo $this -> Form -> end(__('Submit', true)); ?>
<script>
	$(function(){	
		$("#forms").datepicker( { dateFormat: "yy-mm-dd" } );		
	});
	

	$( "#userName" ).autocomplete({
		minLength: 2,
		source: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'lookupByName'), false); ?>',
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
</script>
<?php
$this -> end();
?>
