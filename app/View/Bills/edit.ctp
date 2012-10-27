<?php
$this -> extend('/Common/common');
$this -> assign('title', 'Update Bill');
$this -> start('sidebar');
echo $this -> Html -> nestedList(
array($this -> Html -> link('View All Bills', array('action' => 'index'))), array(), array('id' => 'underline')
);
$this -> end();
$this -> start('middle');
debug($this -> Session -> read());
echo $this -> Form -> create();
echo $this -> Form -> input('id', array('type' => 'hidden'));
echo $this -> Form -> input('title', array('label' => 'Title'));
echo $this -> Form -> input('description', array('label' => 'Description'));
echo $this -> Form -> input('number', array('label' => 'Number'));
echo $this -> Form -> input('fundraising', array('label' => 'Fundraising Efforts'));
echo $this -> Form -> input('type', array('label' => 'Type'));
echo $this -> Form -> input('category', array('label' => 'Category'));
echo $this -> Form -> input('org_id', array(
	'label' => 'Organization',
	'options' => $organizations,
	'default' => 'Select Organization'
));
// debug($bill);echo $this -> Form -> input('Bill.auth_id', array('type' => 'hidden'));
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