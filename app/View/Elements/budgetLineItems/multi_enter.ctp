<?php
/**
 * @author Stephen Roca
 * @since 8/30/2013
 */

 
echo $this -> Html -> div();
debug('BudgetLineItemsTable' . $num);
echo $this -> Form -> create('BudgetBudgetLineItem');
//, array('onsubmit' => 'return validateForm()'));
$tableId = 'BudgetLineItemsTable' . $num;
echo $this -> Html -> tableBegin(array(
	'class' => 'listing',
	'id' => $tableId
));

echo $this -> Html -> tableHeaders(array(
	'Name',
	'FY ' . ($fiscalYear - 1) . ' Allocated',
	"FY $fiscalYear Requested",
	'Difference',
	'',
	'',
	'',
	''
));
if (!isset($budgetLineItems) || count($budgetLineItems) == 0)
{
	$budgetLineItems[] = array('BudgetLineItem' => array(
			'id' => '',
			'name' => null,
			'amount' => ''
		));
}
foreach ($budgetLineItems as $key => $budgetLineItems)
{
	echo $this -> Html -> tableCells(array(
		$this -> Form -> hidden($key . '.BudgetLineItem.id', array(
			'value' => $budgetLineItems['BudgetLineItem']['id'],
			'id' => $num . 'BudgetLineItemId' . $key
		)).
		$this -> Form -> text($key . '.BudgetLineItem.name', array(
			'label' => false,
			'value' => $budgetLineItems['BudgetLineItem']['name'],
			'id' => $num . 'BudgetLineItemName' . $key
		)),
		'',
		$this -> Form -> text($key . '.BudgetLineItem.amount', array(
			'label' => false,
			'value' => $budgetLineItems['BudgetLineItem']['amount'],
			'id' => $num . 'BudgetLineItemAmount' . $key,
		)),
		'',
		$this -> Form -> button($this -> Html -> image('up.gif'), array(
			'type' => 'button',
			'onclick' => "moveUp('$tableId' , $key)",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('down.gif'), array(
			'type' => 'button',
			'onclick' => "moveDown('$tableId' , $key)",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('plus_sign.gif'), array(
			'type' => 'button',
			'onclick' => "addRow('$tableId' , $key)",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('minus_sign.png'), array(
			'type' => 'button',
			'onclick' => "deleteRow('$tableId' , $key)",
			'escape' => false
		)),
	), array('id' => 'BudgetLineItem'));
}
echo $this -> Html -> tableEnd();
echo $this -> Html -> useTag('tagend', 'div');
?>
