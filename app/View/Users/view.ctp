<?php
/**
 * Path: Jacketpages/users/view/$id
 * Passed variables:
 * @param $user - The User Model array for an indivdual user
 * 
 * @author Stephen Roca
 * @since 03/22/2012
 */

// Add the appropriate Breadcrumbs
$this -> Html -> addCrumb('Users', '/users');
$this -> Html -> addCrumb($user['User']['NAME'], '/users/view/' . $user['User']['ID']);

// Begin User Information
echo h($user['User']['NAME']);
echo h($user['User']['EMAIL']); 
// End User Information
?>