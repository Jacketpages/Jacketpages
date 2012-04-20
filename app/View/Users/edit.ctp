<?php
/**
 * Path: Jacketpages/users/edit/$id
 * Passed variables:
 * @param $user - The User Model array for an indivdual user
 * 
 * @author Stephen Roca
 * @since 3/27/2012
 */

   // Echo the title
   echo $this -> Html -> tag('h1', 'Edit User');
   
   // Begin User Edit Form
   echo $this -> Form -> create('User');
   echo $this -> Form -> hidden('ID');
   echo $this -> Form -> input('FIRST_NAME');
   echo $this -> Form -> input('LAST_NAME');
   echo $this -> Form -> input('EMAIL');
   echo $this -> Form -> end('Save User');
   // End User Edit Form
?>
