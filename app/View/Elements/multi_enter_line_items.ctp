<?php
/**
 * @author Stephen Roca
 * @since 7/22/2013
 */
$this -> start('script');
echo $this -> Html -> script('lineitems/lineitems');
$this -> end();
echo $this -> Form -> create('LineItem', array('onsubmit' => 'return validateForm()'));
echo $this -> Html -> tableBegin(array(
	'class' => 'listing',
	'id' => 'LineItemsTable'
));
echo $this -> Html -> tableHeaders(array(
	'#',
	'Name',
	'Cost (Each)',
	'Qty',
	'Total Cost',
	'Requested',
	'Account',
	'',
	'',
	'',
	''
));
if (!isset($lineitems) || count($lineitems) == 0)
{
	$lineitems[] = array('LineItem' => array(
			'id' => '',
			'name' => null,
			'cost_per_unit' => '',
			'quantity' => '',
			'total_cost' => '',
			'amount' => ''
		));
}
foreach ($lineitems as $key => $lineitem)
{
	echo $this -> Html -> tableCells(array(
		$this -> Form -> hidden($key . '.LineItem.id', array(
			'value' => $lineitem['LineItem']['id'],
			'id' => 'LineItemId' . $key
		)) . $this -> Form -> hidden($key . '.LineItem.line_number', array(
			'value' => $key + 1,
			'id' => 'LineItemLineNumber' . $key
		)) . $this -> Form -> label($key . '.LineItem.number', $key + 1, array(
			'label' => false,
			'value' => $key + 1,
			'id' => 'LineNumber' . $key
		)),
		$this -> Form -> text($key . '.LineItem.name', array(
			'label' => false,
			'value' => $lineitem['LineItem']['name'],
			'id' => 'LineItemName' . $key
		)),
		$this -> Form -> text($key . '.LineItem.cost_per_unit', array(
			'label' => false,
			'value' => $lineitem['LineItem']['cost_per_unit'],
			'id' => 'LineItemCostPerUnit' . $key,
			'onchange' => "updateTCAndRqstd($key)"
		)),
		$this -> Form -> text($key . '.LineItem.quantity', array(
			'label' => false,
			'value' => $lineitem['LineItem']['quantity'],
			'id' => 'LineItemQuantity' . $key,
			'onchange' => "updateTCAndRqstd($key)"
		)),
		$this -> Form -> text($key . '.LineItem.total_cost', array(
			'label' => false,
			'value' => $lineitem['LineItem']['total_cost'],
			'id' => 'LineItemTotalCost' . $key,
			'readonly'
		)),
		$this -> Form -> text($key . '.LineItem.amount', array(
			'label' => false,
			'value' => $lineitem['LineItem']['amount'],
			'id' => 'LineItemAmount' . $key,
			'readonly'
		)),
		$this -> Form -> input($key . '.LineItem.account', array(
			'id' => 'LineItemAccount' . $key,
			'label' => false,
			'options' => array(
				'PY' => array(
					'name' => 'Prior Year',
					'value' => 'PY',
					'title' => 'Prior year description.'
				),
				'CO' => array(
					'name' => 'Capital Outlay',
					'value' => 'CO',
					'title' => 'Capital Outlay description.'
				),
				'ULR' => array(
					'name' => 'Undergraduate Legislative Reserve',
					'value' => 'ULR',
					'title' => 'desc'
				),
				'GLR' => array(
					'name' => 'Graduate Legislative Reserve',
					'value' => 'GLR',
					'title' => 'White on rice on a paper plate in a snowstorm'
				)
			),
			'onchange' => "updateTCAndRqstd($key)"
		)),
		$this -> Form -> button($this -> Html -> image('up.gif'), array(
			'type' => 'button',
			'onclick' => "moveUp(" . $key . ")",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('down.gif'), array(
			'type' => 'button',
			'onclick' => "moveDown(" . $key . ")",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('plus_sign.gif'), array(
			'type' => 'button',
			'onclick' => "addRow(" . $key . ")",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('minus_sign.png'), array(
			'type' => 'button',
			'onclick' => "deleteRow(" . $key . ")",
			'escape' => false
		)),
	), array('id' => 'LineItem'));
}
echo $this -> Html -> tableEnd();
?>
