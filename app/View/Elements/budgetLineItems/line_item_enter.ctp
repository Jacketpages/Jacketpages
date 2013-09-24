<?php
/**
 * @author Stephen Roca
 * @since 8/30/2013
 */

echo $this -> Html -> div();
echo $this -> Form -> create();
//, array('onsubmit' => 'return validateForm()'));
$tableId = 'BudgetLineItemsTable' . $num;
echo $this -> Html -> tableBegin(array(
	'class' => 'listing',
	'id' => $tableId
));

echo $this -> Html -> tableHeaders(array(
	'Name',
	'FY ' . ($fiscalYear - 1) . ' Requested',
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
			'id' => null,
			'name' => null,
			'amount' => ''
		),
		'OldRequested' => array(
			'id' => null,
			'name' => null,
			'amount' => ''
		),
		'OldAllocation' => array(
			'id' => null,
			'name' => null,
			'amount' => ''
		));
}
foreach ($budgetLineItems as $key => $budgetLineItem)
{
	echo $this -> Html -> tableCells(array(
		$this -> Form -> hidden($category . '.' . $key . '.BudgetLineItem.id', array(
			'value' => $budgetLineItems[$key]['BudgetLineItem']['id'],
			'id' => $num . 'BudgetLineItemId' . $key
		)) . $this -> Form -> text($category . '.' . $key . '.BudgetLineItem.name', array(
			'label' => false,
			'value' => $budgetLineItems[$key]['BudgetLineItem']['name'],
			'id' => $num . 'BudgetLineItemName' . $key
		)),$this -> Form -> hidden($category . '.' . $key . '.OldRequested.id', array(
			'value' => $budgetLineItems[$key]['OldRequested']['id'],
			'id' => $num . 'OldRequestedId' . $key
		)) .
		$this -> Form -> text($category . '.' . $key . '.OldRequested.amount', array(
			'label' => false,
			'value' => $budgetLineItems[$key]['OldRequested']['amount'],
			'id' => $num . 'OldRequestedAmount' . $key,
		)),$this -> Form -> hidden($category . '.' . $key . '.OldAllocation.id', array(
			'value' => $budgetLineItems[$key]['OldAllocation']['id'],
			'id' => $num . 'OldAllocationId' . $key
		)) .
		$this -> Form -> text($category . '.' . $key . '.OldAllocation.amount', array(
			'label' => false,
			'value' => $budgetLineItems[$key]['BudgetLineItem']['amount'],
			'id' => $num . 'OldAllocationAmount' . $key,
		)),
		$this -> Form -> text($category . '.' . $key . '.BudgetLineItem.amount', array(
			'label' => false,
			'value' => $budgetLineItems[$key]['BudgetLineItem']['amount'],
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
			'onclick' => "addRow('$tableId' ,  $key, $num)",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('minus_sign.png'), array(
			'type' => 'button',
			'onclick' => "deleteRow('$tableId' , $key, $num)",
			'escape' => false
		)),
	), array('id' => 'BudgetLineItem'));
}
echo $this -> Html -> tableEnd();
echo $this -> Html -> useTag('tagend', 'div');
?>
