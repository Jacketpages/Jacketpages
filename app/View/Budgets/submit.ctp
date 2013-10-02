<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

$this -> extend('/Common/budgets');
$this -> assign('title', "FY $fiscalYear Budget Application for $orgName (Tier $tier)");
$this -> start('middle');

echo $this -> Form -> create();
echo $this -> Form -> hidden('id'); 
echo $this -> Form -> hidden('fiscal_year', array('value' => $fiscalYear));
echo $this -> Form -> hidden('org_id', array('value' => $organization['Organization']['id']));
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableCells(array(array(
	array('President', array('width'=>'100px')),
	$president['User']['name'],
	array('Email', array('width'=>'70px')),
	$this->Text->autoLinkEmails($president['User']['email'])
)));
echo $this -> Html -> tableCells(array(
	'Treasurer',
	$treasurer['User']['name'],
	'Email',
	$this->Text->autoLinkEmails($treasurer['User']['email'])
));
echo $this -> Html -> tableCells(array(
	'Advisor',
	$advisor['User']['name'],
	'Email',
	$this->Text->autoLinkEmails($advisor['User']['email'])
));
echo $this -> Html -> tableEnd();

echo $this -> Html -> tableBegin(array(
	'class' => 'listing',
	'id' => 'halftable'
));
echo $this -> Html -> tableHeaders(array(
	'Membership Info',
	''
));
echo $this -> Html -> tableCells(array(
	'Total Years of Active Membership',
	$yearsActive
));
echo $this -> Html -> tableCells(array(
	'Meetings Frequency',
	$organization['Organization']['meeting_frequency']
));
echo $this -> Html -> tableCells(array(
	'Average Attendance per Meeting*',
	$this -> Form -> input('average_attendance', array('type' => 'text','label' => false))
));
echo $this -> Html -> tableCells(array(
	'Are Summer Meetings Held?*',
	$this -> Form -> input('summer_meetings', array('type' => 'text','label' => false))
));

echo $this -> Html -> tableCells(array(
	'# of Members',
	$member_count
));
echo $this -> Html -> tableCells(array(
	'# of Faculty Members*',
	$this -> Form -> input('faculty_member_count', array('type' => 'text','label' => false))
));
echo $this -> Html -> tableCells(array(
	'# of Non-GT Members*',
	$this -> Form -> input('non_gt_member_count', array('type' => 'text','label' => false))
));
echo $this -> Html -> tableEnd();
echo $this -> Form -> submit('Save', array('style' => 'float:left;'));
echo $this -> Form -> submit('Save and Continue', array('name' => "data[redirect]"));

$this -> end();
?>
