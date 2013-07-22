<?php
/**
 * @author Stephen Roca
 * @since 10/29/2012
 */
$this -> start('css');
echo $this -> Html -> css('lineitems');
$this -> end();
$this -> extend('/Common/common');
$this -> assign('title', 'LineItems');
$this -> start('middle');

echo $this -> element('multi_enter_line_items');

echo $this -> Form -> end('Submit');
echo $this -> Form -> button('Copy to JFC', array(
	'controller' => 'lineitems',
	'action' => 'copy'
));
$this -> end();
?>