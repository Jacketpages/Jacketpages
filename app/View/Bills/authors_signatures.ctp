<?php
/**
 * @author Stephen Roca
 * @since 12/8/2012
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
echo $this -> Form -> input('id', array('type' => 'hidden'));
echo $this -> Form -> input('Bill.auth_id', array('type' => 'hidden'));
echo $this -> Form -> input('Authors.ID', array('type' => 'hidden'));
if ($bill['Authors']['undr_auth_id'] == $this -> Session -> read('Sga.id'))
{
	echo $this -> Form -> input('Authors.undr_auth_appr', array('options' => array(0 => 'Not Approved', 1 => 'Approved'), 'label' => 'Undergraduate Approval - '. $UnderAuthor['User']['name']));
}

if ($bill['Authors']['grad_auth_id'] == $this -> Session -> read('Sga.id'))
{
	echo $this -> Form -> input('Authors.grad_auth_appr', array('options' => array(0 => 'Not Approved', 1 => 'Approved'),'label' => 'Graduate Approval - ' . $GradAuthor['User']['name']));
}
echo $this -> Form -> end('Submit');
$this -> end();
?>