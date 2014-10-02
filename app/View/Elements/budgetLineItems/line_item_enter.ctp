<?php
/**
 * @author Stephen Roca
 * @since 8/30/2013
 */

echo $this -> Html -> div();
//, array('onsubmit' => 'return validateForm()'));
$tableId = 'BudgetLineItemsTable' . $num;
echo $this -> Html -> tableBegin(array(
	'class' => 'listing',
	'id' => $tableId
));

echo $this -> Html -> tableHeaders(array(
	array('Name' => array('width' => '350px')),
	'FY ' . ($fiscalYear - 1) . ' Requested',
	'FY ' . ($fiscalYear - 1) . ' Allocated',
	"FY $fiscalYear Requested",
	'Difference',
	array('' => array('width' => '42px')),
	array('' => array('width' => '42px')),
	array('' => array('width' => '42px')),
	array('' => array('width' => '42px'))
));
if (!isset($budgetLineItems) || count($budgetLineItems) == 0)
{
	$budgetLineItems[] = array(
		'BudgetLineItem' => array(
			'id' => null,
			'name' => null,
			'amount' => '',
			'py_req' => 0,
			'py_alloc' => 0
		)
	);
}
foreach ($budgetLineItems as $key => $budgetLineItem)
{
	$hiddenFields = $this -> Form -> hidden($category . '.' . $key . '.BudgetLineItem.id', array(
			'value' => $budgetLineItems[$key]['BudgetLineItem']['id'],
			'id' => $num . 'BudgetLineItemId' . $key
		));
	if (isset($budgetLineItems[$key]['BudgetLineItem']['alloc_parent_id']))
	{
		$hiddenFields .= $this -> Form -> hidden($category . '.' . $key . '.BudgetLineItem.alloc_parent_id', array(
			'value' => $budgetLineItems[$key]['BudgetLineItem']['alloc_parent_id'],
			'id' => $num . 'BudgetLineItemAllocParentId' . $key
		));
		
	}
	if(isset($budgetLineItems[$key]['BudgetLineItem']['req_parent_id']))
	{
		$hiddenFields .= $this -> Form -> hidden($category . '.' . $key . '.BudgetLineItem.req_parent_id', array(
			'value' => $budgetLineItems[$key]['BudgetLineItem']['req_parent_id'],
			'id' => $num . 'BudgetLineItemReqParentId' . $key
		));
	}
	echo $this -> Html -> tableCells(array(
		 $hiddenFields . $this -> Form -> text($category . '.' . $key . '.BudgetLineItem.name', array(
			'label' => false,
			'value' => $budgetLineItems[$key]['BudgetLineItem']['name'],
			'id' => $num . 'BudgetLineItemName' . $key
		)),
		array($budgetLineItems[$key]['BudgetLineItem']['py_req'],
			array('id' => $num . '_py_req_' . $key)
		),
		array($budgetLineItems[$key]['BudgetLineItem']['py_alloc'],
			array('id' => $num . '_py_alloc_' . $key)
		),
		$this -> Form -> text($category . '.' . $key . '.BudgetLineItem.amount', array(
			'label' => false,
			'value' => $budgetLineItems[$key]['BudgetLineItem']['amount'],
			'id' => $num . 'BudgetLineItemAmount' . $key,
			'onchange' => "updateTotal('BudgetLineItemAmount', 'requested'); updateDiff();"
		)),
		array(
			'$0.00',
			array('id' => $num . 'difference' . $key)
		),
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
