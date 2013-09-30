<?php
/**
 * @author Stephen Roca
 * @since 9/9/2013
 */
echo $this -> Html -> script('budgets/fundraising_multi_enter');

$names = array(
	'Executed in FY ' . ($fiscalYear - 1),
	"Expected in FY $fiscalYear",
	'Planned for FY ' . ($fiscalYear + 1)
);
foreach ($names as $key => $name)
{
	echo $this -> Html -> div(null, null, array('id' => 'accordion' . $key));
	echo $this -> Html -> link($name, '#');
	$arr = explode(' ', trim($name));
	echo $this -> element('budgets/fundraising_multi', array(
		'num' => $arr[0],
		'othernum' => $key,
		'fundraisings' => $fundraisers[$arr[0]]
	));
	echo $this -> Html -> useTag('tagend', 'div');
}
?>
<script>
	$(function()
	{
		for ( i = 0; i < 3; i++)
			$("#accordion" + i).accordion(
			{
				heightStyle : "content",
				collapsible : true
			});
	}); 
</script>