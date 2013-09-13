<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */
$this -> extend('/Common/common');
$this -> start('script');
echo $this -> Html -> script('budgets/expenses_multi_enter');
$this -> end();
$this -> assign('title', 'Non-Student Activity Fee Expenses');
$this -> start('middle');
echo $this -> Html -> para('', 'Itemize all expenses paid for within funds from sources other than student activity fees. 
This includes dues, revenue from fundraisers, revenue from other activities, and funds from other sources. 
Do not include items purchased by individual members that will remain with them after leaving the 
organization, i.e., swimsuits, etc. This section allows JFC to see how much the individual organization 
is willing to commit to its goals and will greatly facilitate the budget process.
');
echo $this -> Html -> tableBegin(array(
	'class' => 'listing',
	'id' => 'ExpensesTable'
));
echo $this -> Html -> tableHeaders(array(
	'Item',
	'Expense',
	'',
	'',
	'',
	''
));
if (!isset($expenses))
{
	$expenses = array( array('Expense' => array(
				'item',
				'expense'
			)));
}
foreach ($expenses as $key => $expense)
{
	echo $this -> Html -> tableCells(array(
		$this -> Form -> input('id', array('id' => 'ExpenseId' . $key,'type' => 'hidden')) . $this -> Form -> input('item', array(
			'label' => false,
			'id' => 'Item' . $key
		)),
		$this -> Form -> input('expense', array(
			'label' => false,
			'id' => 'Amount' . $key
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
	));
}
echo $this -> Html -> tableCells(array(
	'Total',
	$this -> Form -> input('total', array('label' => false)),
	'',
	'',
	'',
	''
),array(),array('id' => 'TotalRow'));
echo $this -> Html -> tableEnd();
$this -> end();
?>
