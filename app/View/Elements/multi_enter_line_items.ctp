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
	array('Name' => array('width' => '200px')),
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
			'amount' => '',
			'account' => '',
		));
}
foreach ($lineitems as $key => $lineitem)
{
	echo $this -> Html -> tableCells(array(
		$this -> Form -> hidden($key . '.LineItem.id', array(
			'value' => $lineitem['LineItem']['id'],
			'class' => 'LineItemId',
			'id' => 'LineItemId' . $key
		)) . $this -> Form -> hidden($key . '.LineItem.line_number', array(
			'value' => $key + 1,
			'class' => 'LineItemNumber',
			'id' => 'LineItemLineNumber' . $key
		)) . $this -> Form -> label($key . '.LineItem.number', $key + 1, array(
			'label' => false,
			'value' => $key + 1,
			'class' => 'LineItemNumber',
			'id' => 'LineNumber' . $key
		)),
		$this -> Form -> text($key . '.LineItem.name', array(
			'label' => false,
			'value' => $lineitem['LineItem']['name'],
			'class' => 'LineItemName',
			'id' => 'LineItemName' . $key
		)),
		$this -> Form -> text($key . '.LineItem.cost_per_unit', array(
			'label' => false,
			'value' => $lineitem['LineItem']['cost_per_unit'],
			'id' => 'LineItemCostPerUnit' . $key,
			'class' => 'LineItemCostPerUnit',
			'onchange' => "updateTCAndRqstd($key)"
		)),
		$this -> Form -> text($key . '.LineItem.quantity', array(
			'label' => false,
			'value' => $lineitem['LineItem']['quantity'],
			'id' => 'LineItemQuantity' . $key,
			'class' => 'LineItemQuantity',
			'onchange' => "updateTCAndRqstd($key)"
		)),
		$this -> Form -> text($key . '.LineItem.total_cost', array(
			'label' => false,
			'value' => $lineitem['LineItem']['total_cost'],
			'id' => 'LineItemTotalCost' . $key,
			'class' => 'LineItemTotalCost',
			'readonly'
		)),
		$this -> Form -> text($key . '.LineItem.amount', array(
			'label' => false,
			'value' => $lineitem['LineItem']['amount'],
			'id' => 'LineItemAmount' . $key,
			'class' => 'LineItemAmount',
			'readonly'
		)),
		$this -> Form -> input($key . '.LineItem.account', array(
			'id' => 'LineItemAccount' . $key,
			'label' => false,
			'options' => array(
				'PY' => array(
					'name' => 'Prior Year',
					'value' => 'PY',
					'title' => 'For non-capital expenses'
				),
				'CO' => array(
					'name' => 'Capital Outlay',
					'value' => 'CO',
					'title' => 'For items lasting three or more years'
				),
				'ULR' => array(
					'name' => 'Undergraduate Legislative Reserve',
					'value' => 'ULR',
					'title' => 'Reserved for Undergraduate SGA'
				),
				'GLR' => array(
					'name' => 'Graduate Legislative Reserve',
					'value' => 'GLR',
					'title' => 'Reserved for Graduate SGA'
				)
			),
			'onchange' => "updateTCAndRqstd($key)",
			'style' => 'width: 125px'
		)),
		$this -> Form -> button($this -> Html -> image('up.gif'), array(
			'type' => 'button',
			'class' => 'LineItemButton',
			'onclick' => "moveUp(" . $key . ")",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('down.gif'), array(
			'type' => 'button',
			'class' => 'LineItemButton',
			'onclick' => "moveDown(" . $key . ")",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('plus_sign.gif'), array(
			'type' => 'button',
			'class' => 'LineItemButton',
			'onclick' => "addRow(" . $key . ")",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('minus_sign.png'), array(
			'type' => 'button',
			'class' => 'LineItemButton',
			'onclick' => "deleteRow(" . $key . ")",
			'escape' => false
		)),
	));
}
echo $this -> Html -> tableEnd();
?>
