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

if($sga_exec){
	$this -> start('sidebar');
	$links = array();
	$links[] = $this -> Html -> link('Add SGA Member', array('controller' => 'sga_people', 'action' => 'add'));
	if($admin){
		$links[] = $this -> Html -> link('Add User', array('action' => 'add'));
	}
	echo $this -> Html -> nestedList($links);
	$this -> end();
}

$this -> start('search');

echo $this -> element('search', array('action' =>  'index', 'endForm' => 1));
$this->end();

$this->start('listing');
?>
<div id='forupdate'>
<!-- Begin users table -->
<table class='listing'>
    <?php
    // Print out all of the table headers
   echo $this -> Html -> tableHeaders(array(
      $this -> Paginator -> sort('name', 'Name'),
      $this -> Paginator -> sort('email', 'Email'),
      $this -> Paginator -> sort('gt_user_name', 'GT Username'),
      $this -> Paginator -> sort('level', 'Level'),
      "",""
   ), array('class' => 'links'));
   // Loop through all of the users and display their information
   foreach ($users as $user)
   {
      echo $this -> Html -> tableCells(array(
         $this -> Html -> link($user['User']['name'], array(
            'controller' => 'users',
            'action' => 'view',
            $user['User']['id']
         )),
         $user['User']['email'],
         $user['User']['gt_user_name'],
         $user['User']['level'],
         $this -> Html -> link('Edit', array(
            'action' => 'edit',
            $user['User']['id']
         )),
         // @TODO Figure out what the sprintf thing is for. Display error message. Is it the cakephp standard?
         $this -> Html -> link(__('Delete', true), array(
            'action' => 'delete',
            $user['User']['id']
         ), null, __('Are you sure you want to delete %s?', $user['User']['name']))
      ));
   }
    ?>
</table>
<!-- End users table -->

<?php echo $this -> element('paging');?>
</div>
<?php
 $this -> end();?>
 