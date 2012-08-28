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
			$lineitem['LineItem']['NAME'],
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
	echo $this -> Html -> tableCells(array(
		'Prior Year',
		'',
		'Capital Outlay',
		'',
		'Total',
		''
	));
	echo $this -> Html -> tableEnd();
}
else
{
	echo "There are no line items for this state.";
}
?>