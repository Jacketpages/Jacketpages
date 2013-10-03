<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

$this -> extend('/Common/budgets');
$this -> assign('title', "FY 20$fiscalYear Budget Application for $orgName (Tier $tier)");
$this -> Html -> addCrumb('Organization Information', $this->here);
$this -> start('middle');

echo $this -> Form -> create();
echo $this -> Form -> hidden('id'); 
echo $this -> Form -> hidden('treasurer_id', array('value' => isset($treasurer['User']['id']) ? $treasurer['User']['id'] : '')); 
echo $this -> Form -> hidden('advisor_id',array('value' => isset($advisor['User']['id']) ? $advisor['User']['id'] : '')); 
echo $this -> Form -> hidden('president_id',array('value' => isset($president['User']['id']) ? $president['User']['id'] : ''));
echo $this -> Form -> hidden('member_count',array('value' => $member_count)); 
echo $this -> Form -> hidden('fiscal_year', array('value' => '20' . $fiscalYear));
echo $this -> Form -> hidden('org_id', array('value' => $organization['Organization']['id']));
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableCells(array(array(
	array('President', array('width'=>'100px')),
	(isset($president['User']['name'])) ? $president['User']['name']: 'Missing',
	array('Email', array('width'=>'70px')),
	$this->Text->autoLinkEmails((isset($president['User']['email'])) ? $president['User']['email']: 'Missing')
)));
echo $this -> Html -> tableCells(array(
	'Treasurer',
	(isset($treasurer['User']['name'])) ? $treasurer['User']['name']: 'Missing',
	'Email',
	$this->Text->autoLinkEmails((isset($treasurer['User']['email'])) ? $treasurer['User']['email']: 'Missing')
));
echo $this -> Html -> tableCells(array(
	'Advisor',
	(isset($advisor['User']['name'])) ? $advisor['User']['name']: 'Missing',
	'Email',
	$this->Text->autoLinkEmails((isset($advisor['User']['email'])) ? $advisor['User']['email']: 'Missing')
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
