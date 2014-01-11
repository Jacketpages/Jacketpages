<?php
if (count($budgets) == 1)
{
	echo $this -> element('/budgets/sga_edit', array('budget' => $budgets[0], 'py_index' => 0));
}
else if(count($budgets) == 0)
{
	echo "There are no budgets for this combination of fiscal year, tier, or organization.";
}
else
{
	echo $this -> Html -> tag('h1', 'Line Items');
	foreach ($budgets as $i => $budget)
	{
		if (count($budget['Requested']) > 0)
		{
			echo $this -> Html -> div('', null, array('id' => "org-accordion-$i"));
			echo $this -> Html -> tag('h3', $budget['Organization']['name']);
			{
				echo $this -> Html -> div('', null, array('style' => "padding 0 0 0 0;"));
				{
					echo $this -> element('/budgets/line_items', array(
						'budget' => $budget,
						'i' => $i
					));
				}
				echo $this -> Html -> useTag('tagend', 'div');
			}
			echo $this -> Html -> useTag('tagend', 'div');
		}
	}
}
?>

<script>
		$(function() {
	for(i = 0; i < <?php echo count($budgets); ?>
		;
		i++)
		{
			$("#org-accordion-" + i).accordion(
			{
				collapsible : true,
				heightStyle: "content"
			});
			for ( j = 0; j < 5; j++)
			{
				$("#bli-accordion-" + i + "" + j).accordion(
				{
					collapsible : true,
					heightStyle: "content"
				});
			}
		}
		});
</script>

