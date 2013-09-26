<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */
 
$this -> extend('/Common/common');
$this -> assign('title','Non-Student Activity Fee Expenses');
$this -> start('middle');

echo $this -> Html -> tableBegin(array('class'=> 'listing'));
echo $this -> Html -> tableHeaders(array('Item','Expense'));
echo $this -> Html -> tableCells(array($this -> Form -> input('item'),$this -> Form -> input('expense')));
echo $this -> Html -> tableCells(array('Total',$this -> Form -> input('total',array('label' => false))));
echo $this -> Html -> tableEnd();
$this -> end();
?>
