<?php
/**
 * @author Stephen Roca
 * @since 8/14/2013
 */
$myAccount = array();
if($general && !$gt_member)
{
	$myAccount[] = $this -> Html -> link('Login', array(
      'controller' => 'users',
      'action' => 'login'
   ));
   $header = $this -> Html -> link('Login', array(
      'controller' => 'users',
      'action' => 'login'
   ));
}
if($gt_member)
{
	$myAccount[] =$this -> Html -> link('Account Profile', array(
      'controller' => 'users',
      'action' => 'view',
      $this -> Session -> read('User.id')
   ));
	$myAccount[] = $this -> Html -> link('Logout', array(
      'controller' => 'users',
      'action' => 'logout'
   ));
    /*$myAccount[] = $this -> Html -> link('JacketPages Home', '/');
    $header = $this -> Html -> link('My Account', '#');*/
}

echo $this -> Html -> nestedList(array($header => $myAccount));
?>
