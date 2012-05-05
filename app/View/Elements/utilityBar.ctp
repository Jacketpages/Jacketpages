<?php
/**
 * @author Stephen Roca
 *
 * @TODO finish including all of the standard links
 * @TODO Include html comments to denote when a list starts and ends
 * This file defines the utility bar's menu options/links.
 */
?>

<div id="utilityBarWrapper">
   <div class="utilityMenu" id="utilityBar">
                 <!-- <ul class="utilityMenu">
                  <li> -->
<!-- Begin populating Login/Profile links -->
<?php
//@TODO make this section use nested lists so it's more php less html
if (!$this -> Permission -> isLoggedIn()){
   $list = array(
   $this -> Html -> link('Login', '#') => array($this -> Html -> link(__('Login', true), array(
                              'controller' => 'users',
                              'action' => 'login'
                           ))
   ));
   echo $this -> Html -> nestedList($list);

?>
<!--                      <a href="#">Login</a> -->
<!-- <ul>
<li>
<?php echo $this -> Html -> link(__('Login', true), array(
'controller' => 'users',
'action' => 'login'
));
?>
</li>
</ul> -->
<?php }
   else
   {
   $list = array(
   $this -> Html -> link('Profile', '#') => array(
   $this -> Html -> link('Account Profile', array(
   'owner' => true,
   'controller' => 'users',
   'action' => 'view',
   $this -> Session -> read('User.id')
   )),
   $this -> Html -> link('Logout', array(
   'admin' => false,
   'controller' => 'users',
   'action' => 'logout'
   )),
   $this -> Html -> link('JacketPages Home', '/')
   )
   );
   echo $this -> Html -> nestedList($list);
?>
<!--       <a href='#'>Profile</a> -->
<?php }?>
</li>
</ul>
<!-- End populating Login/Profile links -->
</div>
</div> 