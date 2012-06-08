<?php
/**
 * Path: Jacketpages/users/edit/$id
 * Passed variables:
 * @param $user - The User Model array for an indivdual user
 * 
 * @author Stephen Roca
 * @since 3/27/2012
 */
?>
<?php echo $this -> Html -> addCrumb($user['User']['NAME'], '/users/view/' . $user['User']['ID']);?>
<?php echo $this -> Html -> addCrumb('Edit Profile', '/owner/users/edit/' . $user['User']['ID']);?>
<?php
$this -> extend('/Common/common');
$this -> assign ('title', 'Edit User');

$this -> start('middle');
   
   // Begin User Edit Form
   echo $this -> Form -> create('User');
   echo $this -> Form -> hidden('ID');
   echo $this -> Form -> input('FIRST_NAME', array('label' => 'First Name', array('id' => 'block')));
   echo $this -> Form -> input('LAST_NAME', array('label' => 'Last Name'));
   echo $this -> Form -> input('PHONE', array('label' => 'Phone Number'));
   echo $this -> Form -> input('EMAIL', array('label' => 'Email'));
   echo $this -> Form -> end('Submit');
   // End User Edit Form
$this -> end();
?>
