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
	
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array('Grand Totals','PY Req',
			'PY Alloc',
			'CY Req',
			'JFC',
			'UHRC',
			'GSSC',
			'UHR',
			'GSS',
			'CONF',
			'Final'));
	echo $this -> Html -> tableCells(
		array('Amounts',
		$this -> Number -> currency(array_sum(Hash::extract($budgets, '{n}.Previous_Budget.Requested.{n}.amount'))),
		$this -> Number -> currency(array_sum(Hash::extract($budgets, '{n}.Previous_Budget.Allocated.{n}.amount'))),
		$this -> Number -> currency(array_sum(Hash::extract($budgets, '{n}.Requested.{n}.amount'))),
		$this -> Number -> currency(array_sum(Hash::extract($budgets, '{n}.JFC.{n}.amount'))),
		$this -> Number -> currency(array_sum(Hash::extract($budgets, '{n}.UHRC.{n}.amount'))),
		$this -> Number -> currency(array_sum(Hash::extract($budgets, '{n}.GSSC.{n}.amount'))),
		$this -> Number -> currency(array_sum(Hash::extract($budgets, '{n}.UHR.{n}.amount'))),
		$this -> Number -> currency(array_sum(Hash::extract($budgets, '{n}.GSS.{n}.amount'))),
		$this -> Number -> currency(array_sum(Hash::extract($budgets, '{n}.CONF.{n}.amount'))),
		$this -> Number -> currency(array_sum(Hash::extract($budgets, '{n}.Final.{n}.amount')))
	)
	);
	echo $this -> Html -> tableEnd();
}

if(count($budgets) == 1)
{
?>
<script>
	$(function() {
		$('div[id*=-accordion-]').accordion({
			collapsible : true,
			heightStyle: "content"
		});
	});
</script>
<?php	
}
?>

