<?php
if ($lineitems != null)
{
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		'#',
		'Name',
		'Cost/Unit',
		'Qty',
		'TC',
		'AR',
		'Account',
		'State',
		'',
		''
	));
	foreach ($lineitems as $lineitem)
	{
		echo $this -> Html -> tableCells(array(
			$lineitem['LineItem']['LINE_NUMBER'],
			$this -> Html -> link($lineitem['LineItem']['NAME'], array('controller' => 'LineItems', 'action' => 'view', $lineitem['LineItem']['ID'])),
			$lineitem['LineItem']['COST_PER_UNIT'],
			$lineitem['LineItem']['QUANTITY'],
			$lineitem['LineItem']['TOTAL_COST'],
			$lineitem['LineItem']['LINE_NUMBER'],
			$lineitem['LineItem']['ACCOUNT'],
			$lineitem['LineItem']['STATE']
		));
	}
	echo $this -> Html -> tableEnd();
	echo $this -> Html -> tag('h1', 'Amounts');
	echo $this -> Html -> tableBegin(array('class' => 'listing'));

	
	if ($showAll)
	{
		foreach ($states as $state)
		{
			echo $this -> Html -> tableCells(array($state['LineItem']['STATE'] . ':',
				'Prior Year',
				$this -> Number -> currency($totals['PY_' . strtoupper($state['LineItem']['STATE'])]),
				'Capital Outlay',
				$this -> Number -> currency($totals['CO_' . strtoupper($state['LineItem']['STATE'])]),
				'Total',
				$this -> Number -> currency($totals['TOTAL_' . strtoupper($state['LineItem']['STATE'])])
			));
		}
	}
	else
	{

		echo $this -> Html -> tableCells(array(
			'Prior Year',
			$this -> Number -> currency($totals['PY_' . strtoupper($lineitems[0]['LineItem']['STATE'])]),
			'Capital Outlay',
			$this -> Number -> currency($totals['CO_' . strtoupper($lineitems[0]['LineItem']['STATE'])]),
			'Total',
			$this -> Number -> currency($totals['TOTAL_' . strtoupper($lineitems[0]['LineItem']['STATE'])])
		));
	}
	echo $this -> Html -> tableEnd();
}
else
{
	echo "There are no line items for this state.";
}
?>