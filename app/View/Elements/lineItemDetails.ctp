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
		'State');
	if($showEditAndDeleteButtons)
	{
		$tableHeaders[] = '';
		$tableHeaders[] = '';
	}
	$tableHeaders[] = '';
	echo $this -> Html -> tableHeaders( $tableHeaders
	);
	foreach ($lineitems as $lineitem)
	{
		$tableCells = array(
			$lineitem['LineItem']['line_number'],
			$this -> Html -> link($lineitem['LineItem']['name'],array('controller' => 'LineItems', 'action' => 'view', $lineitem['LineItem']['id'])),
			$lineitem['LineItem']['cost_per_unit'],
			$lineitem['LineItem']['quantity'],
			$lineitem['LineItem']['total_cost'],
			$lineitem['LineItem']['line_number'],
			$lineitem['LineItem']['account'],
			$lineitem['LineItem']['state']
		);
		if($showEditAndDeleteButtons)
		{
			$tableCells[] = $this -> Html -> link("Edit", array('controller' => 'LineItems', 'action' => 'edit', $lineitem['LineItem']['id']));
			$tableCells[] = $this -> Html -> link("Delete", array('controller' => 'LineItems', 'action' => 'delete',$lineitem['LineItem']['id']));
		}
		if(!$lineitem['LineItem']['struck'])
		{
			$tableCells[] = $this -> Html -> link("Strike", array('controller' => 'LineItems', 'action' => 'strikeLineItem',$lineitem['LineItem']['id']));
		}
		echo $this -> Html -> tableCells( $tableCells);
	}
	echo $this -> Html -> tableEnd();
	echo $this -> Html -> tag('h1', 'Amounts');
	echo $this -> Html -> tableBegin(array('class' => 'listing'));

	
	if ($showAll)
	{
		foreach ($states as $state)
		{
			echo $this -> Html -> tableCells(array($state['LineItem']['state'] . ':',
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
	echo "There are no line items for this state.";
	echo $this -> Form -> create('LineItem', array('action' => 'copy/758/Submitted/' . $form_state, 'style' => 'display: inline;'));
	$input = $this -> Form -> input('LineItem.state',array('options' => $eligibleStates, 'label' => false, 'style' => "width: 21%;",'div'=> array('id' => 'inlineInput')));
	echo "Copy line items from " . $input . " state.";
	echo $this -> Form -> end('Copy');
}
?>