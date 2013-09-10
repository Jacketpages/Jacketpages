<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */
 
$this -> extend('/Common/common');
$this -> assign('title',"FY $fiscalYear Budget");
$this -> start('middle');
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableCells(array('Total Amount Requested',''));
echo $this -> Html -> tableCells(array('Target Amount',''));
echo $this -> Html -> tableCells(array('Difference',''));
echo $this -> Html -> tableEnd();
$this -> end();
?>
