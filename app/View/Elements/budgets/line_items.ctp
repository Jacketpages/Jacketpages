<?php
/**
 * IMPORTANT NOTICE:
 * The iteration in this page assumes that the line numbers for the line items
 * don't change when the category changes.
 * If this fact is changed then this page will break!
 */
$category = 0;
foreach ($budget['Requested'] as $k => $budgetLineItem)
{
	//debug($budgetLineItem);
	if ($category != $budgetLineItem['category'])
	{
		if ($category > 0)
		{
			echo $this -> Html -> tableEnd();
			echo $this -> Html -> useTag('tagend', 'div');
			echo $this -> Html -> useTag('tagend', 'div');
		}
		echo $this -> Html -> div('', null, array('id' => "bli-accordion-$i$category"));
		echo $this -> Html -> tag('h3', $budgetLineItem['LineItemCategory']['name']);
		echo $this -> Html -> div();
		echo $this -> Html -> tableBegin(array('class' => 'listing'));
		echo $this -> Html -> tableHeaders(array(
			'#',
			'Name',
			'PY Requested',
			'PY Allocated',
			'CY Requested',
			'JFC',
			'UHR',
			'GSS',
			'UHRC',
			'GSSC',
			'CONF',
			'Final'
		));
		echo $this -> Html -> tableCells(array(
			$budgetLineItem['line_number'],
			$budgetLineItem['name'],
			$this -> Number -> currency($budget['Previous_Budget']['Requested'][$k]['amount']),
			$this -> Number -> currency($budget['Previous_Budget']['Allocated'][$k]['amount']),
			$this -> Number -> currency($budgetLineItem['amount']),
			$this -> Number -> currency(0),
			$this -> Number -> currency(0),
			$this -> Number -> currency(0),
			$this -> Number -> currency(0),
			$this -> Number -> currency(0),
			$this -> Number -> currency(0),
			$this -> Number -> currency(0),
		));
		$category = $budgetLineItem['category'];

	}
	else
	{
		echo $this -> Html -> tableCells(array(
			$budgetLineItem['line_number'],
			$budgetLineItem['name'],
			$this -> Number -> currency($budget['Previous_Budget']['Requested'][$k]['amount']),
			$this -> Number -> currency($budget['Previous_Budget']['Allocated'][$k]['amount']),
			$this -> Number -> currency($budgetLineItem['amount']),
			$this -> Number -> currency(0),
			$this -> Number -> currency(0),
			$this -> Number -> currency(0),
			$this -> Number -> currency(0),
			$this -> Number -> currency(0),
			$this -> Number -> currency(0),
			$this -> Number -> currency(0),
		));
	}
}
echo $this -> Html -> tableEnd();
echo $this -> Html -> useTag('tagend', 'div');
echo $this -> Html -> useTag('tagend', 'div');
