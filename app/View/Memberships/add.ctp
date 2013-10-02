<?php

$this -> extend('/Common/common');
$this -> assign('title', 'Add Membership');

$this -> Html -> addCrumb($orgName, '/organizations/view/'.$orgId);
$this -> Html -> addCrumb('Roster', '/memberships/index/'.$orgId);
$this -> Html -> addCrumb('Add Membership', $this -> here);

$this -> start('middle');

echo $this -> Form -> create('Membership');
echo $this -> Form -> hidden('id');
echo $this -> Form -> hidden('org_id', array('value' => $orgId));
echo $this -> Form -> hidden('user_id', array('id' => 'user_id'));
echo $this -> Form -> input('user_info', array('label' => 'Name or GT ID -- You must select from suggestions when they appear', 'id' => 'userName'));
echo $this -> Form -> input('role', array(
	'label' => 'Role',
	'options' => array(
		'Officer' => 'Officer',
		'Member' => 'Member',
		'President' => 'President',
		'Treasurer' => 'Treasurer',
		'Advisor' => 'Advisor'
	)
));
echo $this -> Form -> input('title', array('label' => 'Title (default to Role name)'));
echo $this -> Form -> input('status', array(
	'label' => 'Status',
	'options' => array(
		'Active',	
		'Pending'
	)
));
echo $this -> Form -> input('room_reserver', array(
	'label' => 'Room Reserver',
	'options' => array(
		'No' => 'No',
		'Yes' => 'Yes'
	)
));
echo $this -> Form -> input('start_date', array(
	'label' => 'Start Date',
	'type' => 'text',
	'id' => 'start_date'
));
echo $this -> Form -> input('end_date', array(
	'label' => 'End Date',
	'type' => 'text',
	'id' => 'end_date'
));
echo $this -> Form -> input('dues_paid', array(
	'label' => 'Dues Paid',
	'type' => 'text',
	'id' => 'dues_paid'
));
echo $this -> Form -> end('Submit');
?>

<script type="text/javascript">
	$(function() {
		$("#start_date").datepicker();
		$("#end_date").datepicker();
		$("#dues_paid").datepicker();
	}); 
	
	
	$(document).ready(function() {
	$( "#userName" ).autocomplete({
		minLength: 2,
		source: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'lookupByName'), true); ?>',
		focus: function( event, ui ) {
			$( "#userName" ).val( ui.item.name );
			return false;
		},
		select: function( event, ui ) {
			$( "#userName" ).val( ui.item.name );
			$( "#user_id" ).val( ui.item.id );
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

<?php
$this->end();
?>
