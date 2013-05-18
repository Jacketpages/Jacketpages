<?php
/**
 * @author Stephen Roca
 * @since 5/2/2013
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Update Bill');
$this -> start('sidebar');
echo $this -> Html -> nestedList(
array($this -> Html -> link('View All Bills', array('action' => 'index'))), array(), array('id' => 'underline')
);
$this -> end();
$this -> start('middle');
echo $this -> Form -> create();


echo $this -> Form -> end('Submit');
$this -> end();
?>