<?php

echo $this -> Html -> tag('h1', $budget['Organization']['name'] . " Budget");
echo $this -> Html -> div('', null, array('id' => "org-accordion-0"));
echo $this -> Html -> tag('h3', $budget['Organization']['name']);
{
	echo $this -> Html -> div();
	{
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
				echo $this -> Html -> div('', null, array('id' => "bli-accordion-0$category"));
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
					$this -> Form -> input('JFC.amount',array('label' => false)),
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
					$this -> Form -> input('JFC.amount',array('label' => false)),
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
	}
	echo $this -> Html -> useTag('tagend', 'div');
}
echo $this -> Html -> useTag('tagend', 'div');
