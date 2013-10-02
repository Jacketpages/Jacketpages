<?php
/**
 * @author Stephen Roca
 * @since 10/08/2012
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Line Item');
$this -> Html -> addCrumb('View Bill', '/bills/view/'.$lineitem['Bill']['id']);
$this -> Html -> addCrumb('View Line Item ', $this->here);
$this -> start('middle');

echo $this -> Html -> tableBegin(array('class' => 'listing'));

echo $this -> Html -> tableCells(array('Bill', $lineitem['Bill']['title']));
echo $this -> Html -> tableCells(array('State', $lineitem['LineItem']['state']));
echo $this -> Html -> tableCells(array('Name', $lineitem['LineItem']['name']));
echo $this -> Html -> tableCells(array('Cost Per Unit', $lineitem['LineItem']['cost_per_unit']));
echo $this -> Html -> tableCells(array('Quantity', $lineitem['LineItem']['quantity']));
echo $this -> Html -> tableCells(array('Total Cost', $lineitem['LineItem']['total_cost']));
echo $this -> Html -> tableCells(array('Amount Requested', $lineitem['LineItem']['amount']));
echo $this -> Html -> tableCells(array('Account', $lineitem['LineItem']['account']));
echo $this -> Html -> tableCells(array('Parent State', (isset($parent['LineItem']['state'])) ? $parent['LineItem']['state'] : 'N/A'));
echo $this -> Html -> tableCells(array('Parent Line Number', (isset($parent['LineItem']['line_number'])) ? $parent['LineItem']['line_number'] : 'N/A'));

echo $this -> Html -> tableEnd();

$this -> end();

?>