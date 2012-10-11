<?php
$this -> extend('/Common/common');
$this -> assign('title', 'Update Bill');
$this -> start('sidebar');
echo $this -> Html -> nestedList(
array($this -> Html -> link('View All Bills', array('action' => 'index'))), array(), array('id' => 'underline')
);
$this -> end();
$this -> start('middle');
echo $this -> Form -> create();
echo $this -> Form -> input('TITLE', array('label' => 'Title'));
echo $this -> Form -> input('DESCRIPTION', array('label' => 'Description'));
echo $this -> Form -> input('NUMBER', array('label' => 'Number'));
echo $this -> Form -> input('FUNDRAISING', array('label' => 'Fundraising Efforts'));
echo $this -> Form -> input('TYPE', array('label' => 'Type'));
echo $this -> Form -> input('CATEGORY', array('label' => 'Category'));
echo $this -> Form -> input('STATUS', array('label' => 'Status'));
echo $this -> Form -> input('ORGANIZATION', array('label' => 'Organization'));
echo $this -> Form -> input('Authors.UNDR_AUTH_ID', array('label' => 'Undergraduate Author'));
echo $this -> Form -> input('Authors.UNDR_AUTH_APPR', array('label' => 'Undergraduate Approval'));
echo $this -> Form -> input('Authors.GRAD_AUTH_ID', array('label' => 'Graduate Author'));
echo $this -> Form -> input('Authors.GRAD_AUTH_APPR', array('label' => 'Graduate Approval'));
echo $this -> Form -> end('Submit');
$this -> end();
?>