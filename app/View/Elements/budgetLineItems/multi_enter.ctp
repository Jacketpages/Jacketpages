<?php
/**
 * @author Stephen Roca
 * @since 8/30/2013
 */
echo $this -> Html -> div();

echo $this -> Form -> create('BudgetBudgetLineItem');
//, array('onsubmit' => 'return validateForm()'));
echo $this -> Html -> tableBegin(array(
	'class' => 'listing',
	'id' => 'BudgetBudgetLineItemsTable'
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
			'id' => 'BudgetLineItemId' . $key
		)).
		$this -> Form -> text($key . '.BudgetLineItem.name', array(
			'label' => false,
			'value' => $budgetLineItems['BudgetLineItem']['name'],
			'id' => 'BudgetLineItemName' . $key
		)),
		'',
		$this -> Form -> text($key . '.BudgetLineItem.amount', array(
			'label' => false,
			'value' => $budgetLineItems['BudgetLineItem']['amount'],
			'id' => 'BudgetLineItemAmount' . $key,
		)),
		'',
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
	), array('id' => 'BudgetLineItem'));
}
echo $this -> Html -> tableEnd();
echo $this -> Html -> useTag('tagend', 'div');
?>
