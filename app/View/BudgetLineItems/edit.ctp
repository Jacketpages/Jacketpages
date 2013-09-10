<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */
 
$this -> extend('/Common/common');
$this -> assign('title',"FY $fiscalYear Budget Line Items");
$this -> start('middle');
echo $this -> element('budgetLineItems/multi_enter');
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableCells(array('Total Expenditures','stuff','stuff'));
echo $this -> Html -> tableEnd();
$this -> end();
?>
