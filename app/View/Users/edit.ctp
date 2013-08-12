<?php
/**
 * Path: jacketpages.gatech.edu/users/edit/$id
 * Passed variables:
 * @param $user - The User Model array for an individual user
 *
 * @author Stephen Roca
 * @since 3/27/2012
 */
echo $this -> Html -> addCrumb($user['User']['NAME'], '/users/view/' . $user['User']['ID']);
echo $this -> Html -> addCrumb('Edit Profile', '/users/edit/' . $user['User']['ID']);

$this -> extend('/Common/common');
$this -> assign('title', 'Edit User');

$this -> start('middle');

// Begin User Edit Form
echo $this -> Form -> create('User');
echo $this -> Form -> hidden('ID');
echo $this -> Form -> input('FIRST_NAME', array(
	'label' => 'First Name',
	array('id' => 'block')
));
echo $this -> Form -> input('LAST_NAME', array('label' => 'Last Name'));
echo $this -> Form -> input('PHONE', array('label' => 'Phone Number'));
echo $this -> Form -> input('EMAIL', array('label' => 'Email'));
echo $this -> Form -> input('ALT_EMAIL', array('label' => 'Alternate Email'));
echo $this -> Form -> end('Submit');
// End User Edit Form
$this -> end();
?>