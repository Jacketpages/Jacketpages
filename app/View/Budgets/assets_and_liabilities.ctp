<?php
/**
 * @author Stephen Roca
 * @since 9/10/2013
 */

$this -> extend('/Common/common');
$this -> assign('title', 'Current Assets and Liabilities');
$this -> start('middle');
echo $this -> Html -> para('', '(Note:  Any bank account balances should be included here and be specific.)');

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
$this -> end();
?>
