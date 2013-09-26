<?php
/**
 * @author Stephen Roca
 * @since 9/9/2013
 */
echo $this -> Html -> script('budgets/fundraising_multi_enter');
?>
<div id="accordion">
<?php
$names = array('Executed in FY ' . ($fiscalYear - 1), "Expected in FY $fiscalYear", 'Planned for FY ' . ($fiscalYear + 1));
	foreach($names as $name)
	{
		echo $this -> Html -> link($name, '#');
		echo $this -> element('budgetLineItems/line_item_enter', array('num' => 0));
	}
?>
</div>
<script>
	$(function()
	{
		$("#accordion").accordion();
	}); 
</script>