<?php
/**
 * @author Stephen Roca
 * @since 08/08/2012
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Edit Membership');

$this -> Html -> addCrumb($membership['Organization']['name'], '/organizations/view/'.$membership['Organization']['id']);
$this -> Html -> addCrumb('Roster', '/memberships/index/'.$membership['Organization']['id']);
$this -> Html -> addCrumb('Edit Membership', $this -> here);

$this -> start('middle');
echo $this -> Html -> tableBegin(array(
	'class' => 'listing',
	'id' => 'halftable'
));
echo $this -> Html -> tableCells(array(
	'Name',
	$membership['User']['name']
));
echo $this -> Html -> tableCells(array(
	'Organization',
	$membership['Organization']['name']
));
echo $this -> Html -> tableEnd();

echo $this -> Form -> create('Membership');
echo $this -> Form -> hidden('id');
echo $this -> Form -> hidden('org_id');
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
		'Active' => 'Active',	
		'Pending' => 'Pending'
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

<script>
	$(function() {
		$("#start_date").datepicker( { dateFormat: "yy-mm-dd" } );
		$("#end_date").datepicker( { dateFormat: "yy-mm-dd" } );
		$("#dues_paid").datepicker( { dateFormat: "yy-mm-dd" } );
	}); 
</script>
<?php
$this -> end();
?>
