<?php
echo $this -> Html -> tag('h1', $budget['Organization']['name'] . " Budget");
echo $this -> Html -> div('', null, array('id' => "org-accordion-0"));
echo $this -> Html -> tag('h3', $budget['Organization']['name']);
{
	echo $this -> Html -> div('', null, array('style' => "padding: 0 0 0 0;"));
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
				echo $this -> Html -> div('', null, array('id' => "bli-accordion-0$category", 'style' => 'padding: 0 0 0 0;'));
				echo $this -> Html -> tag('h3', $budgetLineItem['LineItemCategory']['name']);
				echo $this -> Html -> div('', null, array( 'style' => 'padding: 0 0 0 0;'));
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
						'rows' => (int) ceil(strlen($budgetLineItem['name'])/48),
						'value' => $budgetLineItem['name'],
						'title' => $budgetLineItem['name']
					)) . $this -> Form -> input("BudgetLineItem.$k.category", array(
						'type' => 'hidden',
						'value' => $budgetLineItem['category']
					)) . $this -> Form -> input("BudgetLineItem.$k.id", array(
						'type' => 'hidden',
						'value' => isset($budget[$state][$k]) ? $budget[$state][$k]['id']: ''
					)),
					$this -> Number -> currency($budget['Previous_Budget']['Requested'][$k]['amount']),
					$this -> Number -> currency($budget['Previous_Budget']['Allocated'][$k]['amount']),
					$this -> Number -> currency($budgetLineItem['amount']),
					strcmp($state, 'JFC') == 0 ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
						'label' => false,
						'value' => isset($budget['JFC'][$k]) ? $budget['JFC'][$k]['amount'] : 0
					)) : (isset($budget['JFC'][$k]) ? $this -> Number -> currency($budget['JFC'][$k]['amount']) : $this -> Number -> currency(0)),
					strcmp($state, 'UHRC') == 0 ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
						'label' => false,
						'value' => isset($budget['UHRC'][$k]) ? $budget['UHRC'][$k]['amount'] : 0
					)) : (isset($budget['UHRC'][$k]) ? $this -> Number -> currency($budget['UHRC'][$k]['amount']) : $this -> Number -> currency(0)),
					strcmp($state, 'GSSC') == 0 ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
						'label' => false,
						'value' => isset($budget['GSSC'][$k]) ? $budget['GSSC'][$k]['amount'] : 0
					)) : (isset($budget['GSSC'][$k]) ? $this -> Number -> currency($budget['GSSC'][$k]['amount']) : $this -> Number -> currency(0)),
					strcmp($state, 'UHR') == 0 ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
						'label' => false,
						'value' => isset($budget['UHR'][$k]) ? $budget['UHR'][$k]['amount'] : 0
					)) : (isset($budget['UHR'][$k]) ? $this -> Number -> currency($budget['UHR'][$k]['amount']) : $this -> Number -> currency(0)),
					strcmp($state, 'GSS') == 0 ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
						'label' => false,
						'value' => isset($budget['GSS'][$k]) ? $budget['GSS'][$k]['amount'] : 0
					)) : (isset($budget['GSS'][$k]) ? $this -> Number -> currency($budget['GSS'][$k]['amount']) : $this -> Number -> currency(0)),
					strcmp($state, 'CONF') == 0 ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
						'label' => false,
						'value' => isset($budget['CONF'][$k]) ? $budget['CONF'][$k]['amount'] : 0
					)) : (isset($budget['CONF'][$k]) ? $this -> Number -> currency($budget['CONF'][$k]['amount']) : $this -> Number -> currency(0)),
					strcmp($state, 'Final') == 0 ? $this -> Form -> input("BudgetLineItem.$k.amount", array(
						'label' => false,
						'value' => isset($budget['Final'][$k]) ? $budget['Final'][$k]['amount'] : 0
					)) : (isset($budget['Final'][$k]) ? $this -> Number -> currency($budget['Final'][$k]['amount']) : $this -> Number -> currency(0))
				));
				$category = $budgetLineItem['category'];
			
		}
		echo $this -> Html -> tableEnd();

		echo $this -> Html -> useTag('tagend', 'div');
		echo $this -> Html -> useTag('tagend', 'div');
	}
	echo $this -> Html -> useTag('tagend', 'div');
}
echo $this -> Html -> useTag('tagend', 'div');
