<?php
/**
 * @author Stephen Roca
 * @since 5/2/2013
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Update Voting Information');
$this -> start('sidebar');
echo $this -> Html -> nestedList(
array($this -> Html -> link('View All Bills', array('action' => 'index'))), array()
);
$this -> end();
$this -> start('middle');
if($sga_exec)
{echo $this -> Form -> create('BillVotes');
echo $this -> Form -> input('id', array('type' => 'hidden'));
echo $this -> Form -> input('yeas', array('label' => 'Yeas'));
echo $this -> Form -> input('nays', array('label' => 'Nays'));
echo $this -> Form -> input('abstains', array('label' => 'Abstains'));
echo $this -> Form -> input('comments', array('label' => 'Comments:','type' => 'textarea'));
echo $this -> Form -> submit('Submit');
$this -> end();}
?>