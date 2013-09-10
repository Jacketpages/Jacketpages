<?php
/**
 * @author Stephen Roca
 * @since 9/4/2013
 */
echo $this -> Html -> script('budgetlineitems/multi_enter');
?>
<div id="accordion">
	<?php
	for ($i = 0; $i < count($category_names); $i++)
	{
		echo $this -> Html -> link($category_names[$i] . " " . $category_descriptions[$i], '#');
		echo $this -> element('budgetLineItems/line_item_enter', array('num' => $i));
	}
?>
</div>
<script>
	$(function()
	{
		$("#accordion").accordion();
	}); 
</script>
