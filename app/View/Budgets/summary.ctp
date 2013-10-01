<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

$this -> extend('/Common/common');
$this -> assign('title', "FY $fiscalYear Budget Submission Summary");

$pageNames = array(
	'Past Organization Information',
	'Budget Line Items',
	'Revenue Generated From Fundraising',
	'Non-Student Activity Fee Expenses',
	'Member Contributions'
);
$this -> start('middle');
$i = 1;
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableHeaders(array('','Page','Total',''));
foreach ($pageNames as $pageName)
{
	echo $this -> Html -> tableCells(array($i,$pageName,'','check'));
	$i++;
}
echo $this -> Html -> tableEnd();
echo "Last updated by blank on blank.";
$this -> end();
?>
