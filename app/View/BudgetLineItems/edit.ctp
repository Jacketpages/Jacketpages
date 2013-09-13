<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */
 
$this -> extend('/Common/common');
$this -> assign('title',"FY $fiscalYear Budget Line Items");
$this -> start('middle');
echo $this -> Html -> para('','Itemize all costs, except equipment purchases that will last 
for more than three years. List tentative dates of activities and identify costs associated 
with the different budgeted accounts listed on the cover sheet in the budget request section, 
i.e., Personnel Services, OS&E, and  Travel. Itemize everything and include the frequency of 
each expense. Be as specific as possible about for your own sake; large and ambiguously titled 
amounts look suspicious and are more likely to be cut! For travel expenses, please include 
the round-trip mileage from http://maps.google.com/
');
echo $this -> element('budgetLineItems/multi_enter');
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableCells(array('Total Expenditures','stuff','stuff'));
echo $this -> Html -> tableEnd();
$this -> end();
?>
