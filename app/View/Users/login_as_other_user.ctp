<?php


$this -> Html -> addCrumb('Login', $this->here);

$this -> extend('/Common/common');
$this -> assign('title', 'Login');

$this -> start('middle');
echo $this -> Form -> create();
echo $this -> Form -> input('username');
// echo $this -> Form -> input('password');
echo $this -> Form -> end('Submit');
$this -> end();
?>
