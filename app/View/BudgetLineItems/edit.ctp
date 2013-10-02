<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

$this -> extend('/Common/budgets');
$this -> assign('title', "FY $fiscalYear Budget Line Items");
$this -> Html -> addCrumb('Budget Line Items', $this->here);
$this -> start('middle');
echo $this -> Html -> para('', 'Itemize all costs, except equipment purchases that will last 
for more than three years. List tentative dates of activities and identify costs associated 
with the different budgeted accounts listed on the cover sheet in the budget request section, 
i.e., Personnel Services, OS&E, and  Travel. Itemize everything and include the frequency of 
each expense. Be as specific as possible about for your own sake; large and ambiguously titled 
amounts look suspicious and are more likely to be cut! For travel expenses, please include 
the round-trip mileage from http://maps.google.com/
');
echo $this -> element('budgetLineItems/multi_enter');
echo "</br>";
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableCells(array(
	'FY ' . ($fiscalYear - 1) . ' Requested:',
	array(
		'$0.00',
		array('id' => 'old_requested')
	),
	'FY ' . ($fiscalYear - 1) . ' Allocated:',
	array(
		'$0.00',
		array('id' => 'allocated')
	),
	"FY $fiscalYear Requested:",
	array(
		'$0.00',
		array('id' => 'requested')
	)
));
echo $this -> Html -> tableEnd();
echo $this -> Form -> submit('Save', array('style' => 'float:left;'));
echo $this -> Form -> submit('Save and Continue', array('name' => "data[redirect]"));
?>
<script>
		// @formatter:off
	function updateDiff()	
	{
		for(var i = 0; i < <?php echo $cat_count; ?>;i++)
		{
			var table = document.getElementById('BudgetLineItemsTable' + i);
			for (var j = 0; j < table.rows.length - 1; j++)
			{
				var requested = document.getElementById(i + 'BudgetLineItemAmount' + j).value;
				var allocated = document.getElementById(i + 'OldAllocationAmount' + j).value;
				document.getElementById(i + 'difference' + j).innerHTML = '$' + parseFloat(requested - allocated).toFixed(2);
			}
		}
	}
		
	function updateTotal(id_name, total_id)
	{
	var total = 0;
	for(var i = 0; i < <?php echo $cat_count; ?>;i++)
		{
			var table = document.getElementById('BudgetLineItemsTable' + i);
			for (var j = 0; j < table.rows.length - 1; j++)
			{
				var value = document.getElementById(i + id_name + j).value;
				if (value != '')
					total = total + parseFloat(value);
			}
		}
		var stuff = parseFloat(total).toFixed(2);
		document.getElementById(total_id).innerHTML = '$' + stuff;
		}
		updateDiff();
		updateTotal('OldRequestedAmount','old_requested');
		updateTotal('OldAllocationAmount','allocated');
		updateTotal('BudgetLineItemAmount','requested');
		// @formatter:on
</script>
<?php
$this -> end();
?>
