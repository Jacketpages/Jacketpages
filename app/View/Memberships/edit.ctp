<?php
/**
 * @author Stephen Roca
 * @since 08/08/2012
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Edit Membership');
$this -> start('middle');
echo $this -> Html -> tableBegin(array(
	'class' => 'listing',
	'id' => 'halftable'
));
echo $this -> Html -> tableCells(array('User'));
echo $this -> Html -> tableCells(array('Organization'));
echo $this -> Html -> tableEnd();
echo $this -> Form -> create('Membership', array( 'class' => 'membership'));
echo $this -> Form -> hidden('ID');
echo $this -> Form -> input('ROLE', array(
	'label' => 'Role',
	'options' => array(
		'Officer' => 'Officer',
		'Member' => 'Member',
		'President' => 'President',
		'Treasurer' => 'Treasurer',
		'Advisor' => 'Advisor'
	)
));
echo $this -> Form -> input('TITLE', array('label' => 'Title (default to Role name)'));
echo $this -> Form -> input('STATUS', array(
	'label' => 'Status',
	'options' => array(
		'Active',
		'Inactive',
		'Pending'
	)
));
echo $this -> Form -> input('ROOM_RESERVER', array(
	'label' => 'Room Reserver',
	'options' => array(
		'No' => 'No',
		'Yes' => 'Yes'
	)
));
echo $this -> Form -> input('START_DATE', array(
	'label' => 'Start Date',
	'type' => 'text',
	'id' => 'start_date'
));
echo $this -> Form -> input('DUES_PAID', array(
	'label' => 'Dues Paid',
	'type' => 'text',
	'id' => 'dues_paid'
));
echo $this -> Form -> end('Submit');
?>
<script>
	$(function() {
		$("#start_date").datepicker();
		$("#dues_paid").datepicker();
	}); 
</script>
<?php
$this -> end();
?>
