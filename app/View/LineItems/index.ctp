<?php
/**
 * @author Stephen Roca
 * @since 10/29/2012
 */
$this -> start('css');
echo $this -> Html -> css('lineitems');
$this -> end();
$this -> start('script');
echo $this -> Html -> script('lineitems');
$this -> end();
$this -> extend('/Common/common');
$this -> assign('title', 'LineItems');
$this -> start('middle');

echo $this -> Html -> tableBegin(array(
	'class' => 'listing',
	'id' => 'LineItemsTable'
));
echo $this -> Html -> tableHeaders(array(
	'#',
	'State',
	'Name',
	'Cost Per Unit',
	'Quantity',
	'Total Cost',
	'Amount',
	'',
	'',
	'',
	''
));
echo $this -> Form -> create();
foreach ($lineitems as $key => $lineitem)
{
	echo $this -> Html -> tableCells(array(
		$this -> Form -> text('LineItem.line_number', array(
			'readonly' => true,
			'label' => '',
			'value' => $key + 1,
			'id' => 'LineItemLineNumber' . ($key + 1)
		)),
		$this -> Form -> text('LineItem.state', array(
			'label' => '',
			'value' => $lineitem['LineItem']['state'],
			'id' => 'LineItemState' . ($key + 1)
		)),
		$this -> Form -> text('LineItem.name', array(
			'label' => '',
			'value' => $lineitem['LineItem']['name'],
			'id' => 'LineItemName' . ($key + 1)
		)),
		$this -> Form -> text('LineItem.cost_per_unit', array(
			'label' => '',
			'value' => $lineitem['LineItem']['cost_per_unit'],
			'id' => 'LineItemCostPerUnit' . ($key + 1)
		)),
		$this -> Form -> text('LineItem.quantity', array(
			'label' => '',
			'value' => $lineitem['LineItem']['quantity'],
			'id' => 'LineItemQuantity' . ($key + 1)
		)),
		$this -> Form -> text('LineItem.total_cost', array(
			'label' => '',
			'value' => $lineitem['LineItem']['total_cost'],
			'id' => 'LineItemTotalCost' . ($key + 1)
		)),
		$this -> Form -> text('LineItem.amount', array(
			'label' => '',
			'value' => $lineitem['LineItem']['amount'],
			'id' => 'LineItemAmount' . ($key + 1)
		)),
		$this -> Form -> button($this -> Html -> image('up.gif'), array(
			'type' => 'button',
			'onclick' => "moveUp(" . ($key + 1) . ")",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('down.gif'), array(
			'type' => 'button',
			'onclick' => "moveDown(" . ($key + 1) . ")",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('plus_sign.gif'), array(
			'type' => 'button',
			'onclick' => "addRow(" . ($key + 1) . ")",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('minus_sign.png'), array(
			'type' => 'button',
			'onclick' => "deleteRow(" . ($key + 1) . ")",
			'escape' => false
		)),
	), array('id' => 'LineItem'));
}
echo $this -> Html -> tableEnd();

echo $this -> Form -> end('Submit');
echo $this -> Form -> button('Copy to JFC', array(
	'controller' => 'lineitems',
	'action' => 'copy'
));
$this -> end();
?>