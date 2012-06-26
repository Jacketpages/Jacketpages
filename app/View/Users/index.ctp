<?php
/**
 * Path: Jacketpages/users/index.ctp
 * Passed variables:
 * @param $users - The User Model array for more than one user.
 * @author Stephen Roca
 * @since 03/22/2012
 */
?>
<?php
$this -> Paginator -> options(array(
      'update' => '#forupdate',
      'indicator' => '#indicator',
      'evalScripts' => true,
      'before' => $this -> Js -> get('#listing') -> effect('fadeOut', array('buffer' => false)),
      'complete' => $this -> Js -> get('#listing') -> effect('fadeIn', array('buffer' => false)),
   ));
$this -> Html -> addCrumb('Users', '/users');
$this -> extend('/Common/list');
$this -> assign('title', 'Users');

$this -> start('sidebar');
echo $this -> Html -> nestedList(array(
   $this -> Html -> link('Add User', array('action' => 'add')),
   $this -> Html -> link('Add SGA Member', array('action' => 'add'))
), array(), array('id' => 'underline'));
$this -> end();
$this -> start('search');

echo $this -> element('search');
?>


<?php $this -> end();
   $this -> start('listing');?>
<div id='forupdate'>
<!-- Begin users table -->
<table class='listing'>
    <?php
    // Print out all of the table headers
   echo $this -> Html -> tableHeaders(array(
      $this -> Paginator -> sort('NAME', 'Name'),
      $this -> Paginator -> sort('EMAIL', 'Email'),
      $this -> Paginator -> sort('GT_USER_NAME', 'GT Username'),
      $this -> Paginator -> sort('LEVEL', 'Level'),
      $this -> Paginator -> sort('PHONE', 'Phone'), "",""
   ), array('class' => 'links'));
   // Loop through all of the users and display their information
   foreach ($users as $user)
   {
      echo $this -> Html -> tableCells(array(
         $this -> Html -> link($user['User']['NAME'], array(
            'controller' => 'users',
            'action' => 'view',
            $user['User']['ID']
         )),
         $user['User']['EMAIL'],
         $user['User']['GT_USER_NAME'],
         $user['User']['LEVEL'],
         $user['User']['PHONE'],
         $this -> Html -> link('Edit', array(
            'action' => 'edit',
            $user['User']['ID']
         )),
         // @TODO Figure out what the sprintf thing is for. Display error message. Is it the cakephp standard?
         $this -> Html -> link(__('Delete', true), array(
            'action' => 'delete',
            $user['User']['ID']
         ), null, sprintf(__('Are you sure you want to delete %s?', true), $user['User']['NAME']))
      ));
   }
    ?>
</table>
<!-- End users table -->

<?php echo $this -> element('paging');?>
</div>
<?php
 $this -> end();?>
 