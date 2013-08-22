<?php
/**
 * @author Stephen Roca
 * @since 10/29/2012
 */
$this -> start('css');
echo $this -> Html -> css('lineitems');
$this -> end();
$this -> start('script');
echo $this -> Html -> script('lineitems/lineitemvalidation');
echo $this -> Html -> script('validation/validation');
$this -> end();
$this -> extend('/Common/common');
$this -> start('sidebar');
echo $this -> Html -> nestedList(array($this -> Html -> link('Travel Calculator', array('action' => 'travel_calculator'))), array(), array('id' => 'underline'));
$this -> end();

$this -> assign('title', 'Line Items - ' . $titleState);
$this -> start('middle');

echo $this -> element('multi_enter_line_items');

echo $this -> Form -> submit('Submit', array(
	'formnovalidate',
	'onclick' => 'openToolTips()'
));
$this -> end();
?>