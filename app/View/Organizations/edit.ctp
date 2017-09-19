<?php
/**
 * @author Stephen Roca
 * @since 06/21/2012
 */
// TODO grab the javascript from the admin_edit.ctp from JP and implement it
// here.
$this -> extend('/Common/common');
$this -> assign('title', 'Edit Organization');
$this->Html->addCrumb('Organizations', '/organizations');
$this -> Html -> addCrumb($organization['Organization']['name'], '/organizations/view/' . $organization['Organization']['id']);
$this -> Html -> addCrumb('Edit Organization', $this -> here);
$this -> start('middle');
echo $this -> Form -> create('Organization');
echo $this -> Form -> hidden('id');
echo $this -> Form -> input('name', array('label' => 'Name'));
if ($lace)
{
	echo $this -> Form -> input('status', array(
		'label' => 'Status',
		'options' => array(
			'Active' => 'Active/Good Standing',
			'Inactive' => 'Inactive',
			'Under Review' => 'Under Review',
			'Pending' => 'Pending'
		)
	));
}
if ($sga_exec || $lace)
{
	echo $this -> Form -> input('tier', array(
		'label' => 'Tier',
		'options' => array(
			'1' => 'I',
			'2' => 'II',
			'3' => 'III'
		)
	));
}
/*echo $this -> Form -> input('category', array(
	'options' => $categories,
	'label' => 'Category'
));*/
/*echo $this -> Form -> hidden('contact_id', array('id' => 'contact_id'));
echo $this -> Form -> input('User.name', array(
	'label' => 'Primary Contact Name -- You must select from suggestions when they appear',
	'id' => 'userName'
));*/
/*echo $this -> Form -> input('User.name', array(
 'label' => 'Primary Contact',
 'id' => 'primary_contact'
 ));
 echo $this -> Form -> input('User.email', array(
 'label' => 'Primary Contact Email',
 'id' => 'primary_email'
 ));*/
/*echo $this -> Form -> input('description', array(
	'label' => 'Description',
	'type' => 'textarea'
));*/
echo $this->Form->input('website', array('label' => 'Orgsync Website'));
/*echo $this -> Form -> input('meeting_information', array('label' => 'Meeting Information'));
echo $this -> Form -> input('dues', array('label' => 'Dues', 'id' => 'dues'));*/
?>
<!--<div id='date'>
	<?php
/*	if ($lace)
	{
		echo $this -> Form -> input('alcohol_form', array(
			'type' => 'text',
			'minYear' => date("Y") - 1,
			'maxYear' => date("Y"),
			'dateFormat' => 'MDY',
			'label' => 'Acknowledgement of Alcohol & Illegal Drug Policy Form Date',
			'id' => 'forms1'
		));
		echo $this -> Form -> input('advisor_date', array(
			'type' => 'text',
			'minYear' => date("Y") - 1,
			'maxYear' => date("Y"),
			'dateFormat' => 'MDY',
			'label' => 'Roles and Responsibilities of Advisors Form Date',
			'id' => 'forms2'
		));
		echo $this -> Form -> input('constitution_date', array(
			'type' => 'text',
			'minYear' => date("Y") - 1,
			'maxYear' => date("Y"),
			'dateFormat' => 'MDY',
			'label' => 'Constitution Form Date',
			'id' => 'forms3'
		));
	}
	*/ ?>
</div>-->
</fieldset>
<?php echo $this -> Form -> end(__('Submit', true)); ?>
<script>
	$(function(){	
		$("#forms1").datepicker( { dateFormat: "yy-mm-dd" } );
		$("#forms2").datepicker( { dateFormat: "yy-mm-dd" } );
		$("#forms3").datepicker( { dateFormat: "yy-mm-dd" } );		
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
