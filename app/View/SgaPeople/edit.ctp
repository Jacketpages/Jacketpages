<?php

$this -> extend('/Common/common');
$this -> assign('title', 'Edit SGA Membership');

$this -> Html -> addCrumb('SGA People', '/sga_people');
$this -> Html -> addCrumb('Edit SGA Member', '/sga_people/edit/'.$sgaPerson['SgaPerson']['id']);

$this -> start('middle');

echo $this -> Form -> create('SgaPerson');

echo $this -> Form -> input('house', array('options' => array(
			'Graduate' => 'Graduate',
			'Undergraduate' => 'Undergraduate'
	)));
echo $this -> Form -> input('department');
echo $this -> Form -> input('status', array('options' => array(
			'Active' => 'Active',
			'Inactive' => 'Inactive'
	)));
echo $this -> Form -> end(__('Submit', true));

$this -> end();

?>