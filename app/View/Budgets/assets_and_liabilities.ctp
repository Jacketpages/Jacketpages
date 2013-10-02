<?php
/**
 * @author Stephen Roca
 * @since 9/10/2013
 */

$this -> extend('/Common/budgets');
$this -> assign('title', 'Current Assets and Liabilities');
$this -> Html -> addCrumb('Assets and Liabilities', $this->here);
$this -> start('middle');
echo $this -> Html -> para('', 'Any bank account balances should be included here and be specific. Please indicate if asset has been tagged by GT Inventory.');
echo $this -> Form -> create();
echo $this -> element('/budgets/assets');
echo $this -> element('/budgets/liabilities');


?>

<script>
	$(function()
	{
		$("#assets_accordion").accordion(
		{
			heightStyle : "content",
			collapsible : true
		});
		$("#liabilities_accordion").accordion(
		{
			heightStyle : "content",
			collapsible : true
		});
	}); 
</script>
<?php

echo $this -> Form -> submit('Save', array('style' => 'float:left;'));
echo $this -> Form -> submit('Save and Continue');
$this -> end();
?>
