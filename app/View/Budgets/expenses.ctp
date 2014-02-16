<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */
$this -> extend('/Common/budgets');
$this -> start('script');
echo $this -> Html -> script('budgets/expenses_multi_enter');
$this -> end();
$this -> assign('title', 'Non-Student Activity Fee Expenses');
$this -> Html -> addCrumb($organization['Organization']['name'], '/organizations/view/' . $organization['Organization']['id']);
$this -> Html -> addCrumb('Expenses', $this->here);
$this -> start('middle');
echo $this -> Html -> para('', 'Itemize all expenses paid for within funds from sources other than student activity fees. 
This includes dues, revenue from fundraisers, revenue from other activities, and funds from other sources. 
Do not include items purchased by individual members that will remain with them after leaving the 
organization, i.e., swimsuits, etc. This section allows JFC to see how much the individual organization 
is willing to commit to its goals and will greatly facilitate the budget process.
');
echo $this -> Form -> create('Expense');
echo $this -> Html -> tableBegin(array(
	'class' => 'listing',
	'id' => 'ExpensesTable'
));
echo $this -> Html -> tableHeaders(array(
	array('Item' => array('width' => '500px')),
	'Expense',
	'',
	'',
	'',
	''
));
if (count($expenses) == 0)
{
	$expenses = array( array('Expense' => array(
				'id' => '',
				'item' => '',
				'amount' => ''
			)));
}
foreach ($expenses as $key => $expense)
{
	echo $this -> Html -> tableCells(array(
		$this -> Form -> input("$key.Expense.id", array(
			'id' => 'ExpenseId' . $key,
			'type' => 'hidden',
			'value' => $expense['Expense']['id']
		))  . $this -> Form -> input("$key.Expense.item", array(
			'label' => false,
			'id' => 'Item' . $key,
			'value' => $expense['Expense']['item']
		)),
		$this -> Form -> input("$key.Expense.amount", array(
			'label' => false,
			'id' => 'Amount' . $key,
			'value' => $expense['Expense']['amount'],
			'onchange' => 'updateTotal()'
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
echo $this -> Html -> tableCells(array(array(
	array('Total', array('align'=>'right')),
	array('$0.00',array('id' => 'total')),
	'',
	'',
	'',
	''
)), array(), array('id' => 'TotalRow'));
echo $this -> Html -> tableEnd();
echo $this -> Form -> submit('Save', array('style' => 'float:left;'));
echo $this -> Form -> submit('Save and Continue', array('name' => "data[redirect]"));
echo "<script>updateTotal();</script>";
$this -> end();
?>
