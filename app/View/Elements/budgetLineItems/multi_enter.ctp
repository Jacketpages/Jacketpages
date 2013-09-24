<?php
/**
 * @author Stephen Roca
 * @since 9/4/2013
 */
echo $this -> Html -> script('budgetlineitems/multi_enter');
?>
<?php
for ($i = 0; $i < count($category_names); $i++)
{
	echo $this -> Html -> div(false, null, array('id' => 'accordion' . $i));
	echo $this -> Html -> link($category_names[$i] . " " . $category_descriptions[$i], '#');
	if (isset($budgetLineItems[$category_names[$i]]))
		echo $this -> element('budgetLineItems/line_item_enter', array(
			'num' => $i,
			'category' => $category_names[$i],
			'budgetLineItems' => $budgetLineItems[$category_names[$i]]
		));
	else
		echo $this -> element('budgetLineItems/line_item_enter', array(
			'num' => $i,
			'category' => $category_names[$i]
		));
	echo $this -> Html -> useTag('tagend', 'div');
}
?>
<script>
		$(function() {
	for(i = 0; i < <?php echo count($category_names) ?>
		; i++)
		$("#accordion" + i).accordion(
		{
			heightStyle : "content",
			collapsible : true
		});
		});
</script>
