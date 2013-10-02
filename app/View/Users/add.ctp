<?php
/**
 * @author Stephen Roca
 * @since 03/22/2012
 */
$this -> Html -> addCrumb('Users', '/users');
$this -> Html -> addCrumb('Add', '/users/add');

$this -> extend('/Common/common');
$this -> assign('title', 'Add User');
$this -> start('middle');
echo $this -> Form -> create('User');
echo $this -> Form -> input('first_name', array('label' => 'First Name'));
echo $this -> Form -> input('last_name', array('label' => 'Last Name'));
echo $this -> Form -> input('gt_user_name', array('label' => 'GT ID'));
echo $this -> Form -> input('phone', array('label' => 'Phone Number'));
echo $this -> Form -> input('email', array('label' => 'Email'));
echo $this -> Form -> input('alt_email', array('label' => 'Alternate Email'));
echo $this -> Form -> end('Save User');
$this -> end();
?>