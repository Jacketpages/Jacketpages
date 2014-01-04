<?php
/**
 * IMPORTANT NOTICE:
 * The iteration in this page assumes that the line numbers for the line items
 * don't change when the category changes.
 * If this fact is changed then this page will break!
 */
$subtotals = array(
	0,
	0,
	0,
	0,
	0,
	0,
	0,
	0,
	0,
	0
);
$totals = $subtotals;
$category = 0;
foreach ($budget['Requested'] as $k => $budgetLineItem)
{
	//debug($budgetLineItem);
	if ($category != $budgetLineItem['category'])
	{
		if ($category > 0)
		{
			echo $this -> Html -> tableCells(array(
				'',
				'Subtotals',
				$this -> Number -> currency($subtotals[0]),
				$this -> Number -> currency($subtotals[1]),
				$this -> Number -> currency($subtotals[2]),
				$this -> Number -> currency($subtotals[3]),
				$this -> Number -> currency($subtotals[4]),
				$this -> Number -> currency($subtotals[5]),
				$this -> Number -> currency($subtotals[6]),
				$this -> Number -> currency($subtotals[7]),
				$this -> Number -> currency($subtotals[8]),
				$this -> Number -> currency($subtotals[9])
			));
			echo $this -> Html -> tableEnd();
			echo $this -> Html -> useTag('tagend', 'div');
			echo $this -> Html -> useTag('tagend', 'div');
			for ($index = 0; $index < count($subtotals); $index++)
			{
				$totals[$index] += $subtotals[$index];
				$subtotals[$index] = 0;
			}
		}
		echo $this -> Html -> div('', null, array(
			'id' => "bli-accordion-$i$category",
			'style' => 'padding: 0 0 0 0;'
		));
		echo $this -> Html -> tag('h3', $budgetLineItem['LineItemCategory']['name']);
		echo $this -> Html -> div('', null, array('style' => 'padding: 0 0 0 0;'));
		echo $this -> Html -> tableBegin(array('class' => 'listing'));
		echo $this -> Html -> tableHeaders(array(
			'#',
			array('Name' => array('style' => 'width:350px;')),
			'PY Req',
			'PY Alloc',
			'CY Req',
			'JFC',
			'UHRC',
			'GSSC',
			'UHR',
			'GSS',
			'CONF',
			'Final'
		));

	}
	echo $this -> Html -> tableCells(array(
		$budgetLineItem['line_number'],
		$this -> Form -> input("BudgetLineItem.$k.name", array(
			'label' => false,
			'div' => false,
			'readonly' => 'readonly',
			'type' => 'textarea',
			'style' => 'background-color:transparent; border:none;resize:none;',
			'rows' => (int) ceil(strlen($budgetLineItem['name']) / 48),
			'value' => $budgetLineItem['name'],
			'title' => $budgetLineItem['name']
		)) . $this -> Form -> input("BudgetLineItem.$k.category", array(
			'type' => 'hidden',
			'value' => $budgetLineItem['category']
		)) . $this -> Form -> input("BudgetLineItem.$k.id", array(
			'type' => 'hidden',
			'value' => isset($budget[$state][$k]) ? $budget[$state][$k]['id'] : ''
		)),
		$this -> Number -> currency($budget['Previous_Budget']['Requested'][$k]['amount']),
		$this -> Number -> currency($budget['Previous_Budget']['Allocated'][$k]['amount']),
		$this -> Number -> currency($budgetLineItem['amount']),
		(isset($budget['JFC'][$k]) ? $this -> Number -> currency($budget['JFC'][$k]['amount']) : $this -> Number -> currency(0)),
		(isset($budget['UHRC'][$k]) ? $this -> Number -> currency($budget['UHRC'][$k]['amount']) : $this -> Number -> currency(0)),
		(isset($budget['GSSC'][$k]) ? $this -> Number -> currency($budget['GSSC'][$k]['amount']) : $this -> Number -> currency(0)),
		(isset($budget['UHR'][$k]) ? $this -> Number -> currency($budget['UHR'][$k]['amount']) : $this -> Number -> currency(0)),
		(isset($budget['GSS'][$k]) ? $this -> Number -> currency($budget['GSS'][$k]['amount']) : $this -> Number -> currency(0)),
		(isset($budget['CONF'][$k]) ? $this -> Number -> currency($budget['CONF'][$k]['amount']) : $this -> Number -> currency(0)),
		(isset($budget['Final'][$k]) ? $this -> Number -> currency($budget['Final'][$k]['amount']) : $this -> Number -> currency(0))
	));
	$subtotals[0] += $budget['Previous_Budget']['Requested'][$k]['amount'];
	$subtotals[1] += $budget['Previous_Budget']['Allocated'][$k]['amount'];
	$subtotals[2] += $budgetLineItem['amount'];
	$subtotals[3] += (isset($budget['JFC'][$k]) ? $budget['JFC'][$k]['amount'] : 0);
	$subtotals[4] += (isset($budget['UHRC'][$k]) ? $budget['UHRC'][$k]['amount'] : 0);
	$subtotals[5] += (isset($budget['GSSC'][$k]) ? $budget['GSSC'][$k]['amount'] : 0);
	$subtotals[6] += (isset($budget['UHR'][$k]) ? $budget['UHR'][$k]['amount'] : 0);
	$subtotals[7] += (isset($budget['GSS'][$k]) ? $budget['GSS'][$k]['amount'] : 0);
	$subtotals[8] += (isset($budget['CONF'][$k]) ? $budget['CONF'][$k]['amount'] : 0);
	$subtotals[9] += (isset($budget['Final'][$k]) ? $budget['Final'][$k]['amount'] : 0);
	$category = $budgetLineItem['category'];

}
for ($idx = 0; $idx < count($subtotals); $idx++)
	$totals[$idx] += $subtotals[$idx];
echo $this -> Html -> tableCells(array(
	'',
	'Subtotals',
	$this -> Number -> currency($subtotals[0]),
	$this -> Number -> currency($subtotals[1]),
	$this -> Number -> currency($subtotals[2]),
	$this -> Number -> currency($subtotals[3]),
	$this -> Number -> currency($subtotals[4]),
	$this -> Number -> currency($subtotals[5]),
	$this -> Number -> currency($subtotals[6]),
	$this -> Number -> currency($subtotals[7]),
	$this -> Number -> currency($subtotals[8]),
	$this -> Number -> currency($subtotals[9])
));
echo $this -> Html -> tableCells(array(
	'',
	'Totals',
	$this -> Number -> currency($totals[0]),
	$this -> Number -> currency($totals[1]),
	$this -> Number -> currency($totals[2]),
	$this -> Number -> currency($totals[3]),
	$this -> Number -> currency($totals[4]),
	$this -> Number -> currency($totals[5]),
	$this -> Number -> currency($totals[6]),
	$this -> Number -> currency($totals[7]),
	$this -> Number -> currency($totals[8]),
	$this -> Number -> currency($totals[9])
));
echo $this -> Html -> tableEnd();

echo $this -> Html -> useTag('tagend', 'div');
echo $this -> Html -> useTag('tagend', 'div');
