<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

$this -> extend('/Common/budgets');
$this -> assign('title', "FY 20$fiscalYear Budget Submission Summary");
$this -> Html -> addCrumb('Budget Submission Summary', $this->here);
$pageNames = array(
	'Organization Information',
	'Budget Line Items',
	'Revenue Generated From Fundraising',
	'Non-Student Activity Fee Expenses',
	'Assets minus Liabilities',
	'Member Contributions'
);
$this -> start('middle');
$i = 1;
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableHeaders(array('','Page','Totals','Status'));
foreach ($pageNames as $key => $pageName)
{
	echo $this -> Html -> tableCells(array($i,$pageName,$totals[$key], $state['BudgetSubmitState']['state_' . $i] ? "Satisfied" : "Pending Review"));
	$i++;
}
echo $this -> Html -> tableEnd();
echo "Last updated by $last_updated_by on $last_updated.";
echo $this -> Form -> create('',array('onsubmit' => 'return finalBudgetSubmit()'));
if(!$budgetSubmitted)
echo $this -> Form -> submit();
echo '<div id="notification">';
echo "Once you have completed your application, please use the Submit button to release it to SGA. No further changes can be made after submitting.";
echo '</div>';
?>
<script>
	function finalBudgetSubmit()
	{
		return confirm('Changes can not be made after submission. Are you sure you want to submit this budget?');
	}
</script>
<?php
$this -> end();
?>
