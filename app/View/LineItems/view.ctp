<?php
/**
 * @author Stephen Roca
 * @since 10/08/2012
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Line Item');

$this -> start('middle');

echo $this -> Html -> tableBegin(array('class' => 'listing'));

echo $this -> Html -> tableCells(array('Bill', $lineitem['Bill']['TITLE']));
echo $this -> Html -> tableCells(array('State', $lineitem['LineItem']['STATE']));
echo $this -> Html -> tableCells(array('Name', $lineitem['LineItem']['NAME']));
echo $this -> Html -> tableCells(array('Cost Per Unit', $lineitem['LineItem']['COST_PER_UNIT']));
echo $this -> Html -> tableCells(array('Quantity', $lineitem['LineItem']['QUANTITY']));
echo $this -> Html -> tableCells(array('Total Cost', $lineitem['LineItem']['TOTAL_COST']));
echo $this -> Html -> tableCells(array('Amount', $lineitem['LineItem']['AMOUNT']));
echo $this -> Html -> tableCells(array('Account', $lineitem['LineItem']['ACCOUNT']));

echo $this -> Html -> tableEnd();

$this -> end();

?>