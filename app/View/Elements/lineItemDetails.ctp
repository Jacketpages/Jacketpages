<?php
/**
 * @author Stephen Roca
 * @since ???
 *
 *
 * @param $lineitems
 * @param $showAll
 * @param $states
 **/
$showEditAndDeleteButtons = 1;

if ($lineitems != null)
{
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	$tableHeaders = array(
		'#',
		'Name',
		'Cost/Unit',
		'Qty',
		'TC',
		'AR',
		'Account',
		''
	);
	if ($showAll)
	{
		$tableHeaders[7] = 'State';
	}
	if (!$showAll && $showEditAndDeleteButtons && !isset($first) && $form_state != 'Final' && $bill['Bill']['status'] < 6)
	{
		$tableHeaders[] = '';
	}
	if (!$showAll && !isset($first))
		$tableHeaders[] = '';
	echo $this -> Html -> tableHeaders($tableHeaders);
	foreach ($lineitems as $lineitem)
	{
		$tableCells = array(
			$lineitem['LineItem']['line_number'],
			$this -> Html -> link($lineitem['LineItem']['name'], array(
				'controller' => 'LineItems',
				'action' => 'view',
				$lineitem['LineItem']['id']
			)),
			$lineitem['LineItem']['cost_per_unit'],
			$lineitem['LineItem']['quantity'],
			$lineitem['LineItem']['total_cost'],
			$lineitem['LineItem']['amount'],
			$lineitem['LineItem']['account']
		);
		if ($showAll)
		{
			$tableCells[] = $lineitem['LineItem']['state'];
			echo $this -> Html -> tableCells($tableCells);
		}
		else
		{
			// MRE hack fix to permissions
			if ($sga_exec && $lineitem['LineItem']['state'] != 'Final' && $bill['Bill']['status'] < 6)
			{
					$tableCells[] = $this -> Html -> link("Edit/Delete", array(
						'controller' => 'LineItems',
						'action' => 'edit',
						$bill['Bill']['id'],
						$lineitem['LineItem']['state']
					));
				if (!$lineitem['LineItem']['struck'] && !in_array($lineitem['LineItem']['state'], array(
					'Submitted',
					'Final'
				)))
				{
					$tableCells[] = $this -> Html -> link("Strike", array(
						'controller' => 'LineItems',
						'action' => 'strikeLineItem',
						$lineitem['LineItem']['id']
					));
					echo $this -> Html -> tableCells($tableCells);
				}
				else if (!in_array($lineitem['LineItem']['state'], array(
					'Submitted',
					'Final'
				)))
				{
					$tableCells[] = $this -> Html -> link("Unstrike", array(
						'controller' => 'LineItems',
						'action' => 'unstrikeLineItem',
						$lineitem['LineItem']['id']
					));
					echo $this -> Html -> tableCells($tableCells, array('id' => 'struck'), array('id' => 'struck'));
				}
				else
				{
					echo $this -> Html -> tableCells($tableCells);
				}
			}
			else
			{
				echo $this -> Html -> tableCells($tableCells);
			}
		}
	}
	echo $this -> Html -> tableEnd();
	echo $this -> Html -> tag('h1', 'Amounts');
	echo $this -> Html -> tableBegin(array('class' => 'listing'));

	if ($showAll)
	{
		foreach ($states as $state)
		{
			echo $this -> Html -> tableCells(array(
				$state['LineItem']['state'] . ':',
				'Prior Year',
				$this -> Number -> currency($totals['PY_' . strtoupper($state['LineItem']['state'])]),
				'Capital Outlay',
				$this -> Number -> currency($totals['CO_' . strtoupper($state['LineItem']['state'])]),
				'Total',
				$this -> Number -> currency($totals['TOTAL_' . strtoupper($state['LineItem']['state'])])
			));
		}
	}
	else
	{

		echo $this -> Html -> tableCells(array(
			'Prior Year',
			$this -> Number -> currency($totals['PY_' . strtoupper($lineitems[0]['LineItem']['state'])]),
			'Capital Outlay',
			$this -> Number -> currency($totals['CO_' . strtoupper($lineitems[0]['LineItem']['state'])]),
			'Total',
			$this -> Number -> currency($totals['TOTAL_' . strtoupper($lineitems[0]['LineItem']['state'])])
		));
	}
	echo $this -> Html -> tableEnd();
}
else
{
	echo "There are no line items for this state.<br/>";
	if ($bill['Bill']['status'] > 2 && $sga_exec && $bill['Bill']['status'] < 6)
	{
		echo $this -> Form -> create('LineItem', array(
			'action' => ('copy/' . $bill['Bill']['id'] . '/' . $form_state),
			'style' => 'display: inline;'
		));
		$input = $this -> Form -> input('LineItem.state', array(
			'options' => $eligibleStates,
			'label' => false,
			'style' => "width: 21%;",
			'div' => array('id' => 'inlineInput')
		));
		if (strcmp($form_state, "Final"))
		{
			echo "Copy line items from $input state or " . $this -> Html -> link("add new line items.", array(
				'controller' => 'LineItems',
				'action' => 'index',
				$bill['Bill']['id'],
				$form_state
			));

		}
		else
		{
			echo "Copy line items from $input state.";
		}
		echo $this -> Form -> end('Copy');
	}
}
?>