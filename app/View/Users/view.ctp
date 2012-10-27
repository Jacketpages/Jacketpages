<?php
/**
 * Path: Jacketpages/users/view/$id
 * Passed variables:
 * @param $user - The User Model array for an indivdual user
 *
 * @author Stephen Roca
 * @since 03/22/2012
 */

//TODO Add in more information about the user. Location etc.

// Add the appropriate breadcrumbs
$this -> Html -> addCrumb('Users', '/users');
$this -> Html -> addCrumb($user['User']['name'], '/users/view/' . $user['User']['id']);
$this -> extend('/Common/common');

$links[] = $this -> Html -> link(__('Edit User', true), array(
      'action' => 'edit',
      $user['User']['ID']
   )); 
if($userDeletePerm)
{
	$links[] = $this -> Html -> link(__('Delete User', true), array(
      'action' => 'delete',
      $user['User']['id']
   ), null, sprintf(__('Are you sure you want to delete %s?', true), $user['User']['name']));
}

$this -> start('sidebar');
echo $this -> Html -> nestedList($links
, array(), array('id' => 'underline'));
$this -> end();

$this -> assign('title', $user['User']['name']);

$this -> start('middle');
?>
<table class='listing' id='halftable'>
 <?php
echo $this -> Html -> tableCells(array(
   'GT Username',
   $user['User']['gt_user_name']
));
echo $this -> Html -> tableCells(array(
   'Email',
   $user['User']['email']
));
echo $this -> Html -> tableCells(array(
   'Alternate Email',
   $user['User']['alt_email']
));
echo $this -> Html -> tableCells(array(
   'Phone',
   $user['User']['phone']
));

 ?>
</table>
<?php
echo $this -> Html -> tag('h1', 'Executive Positions');
?>
<table class='listing'>
   <?php
   echo $this -> Html -> tableHeaders(array(
      'Organization',
      'Title',
      'Start Date'
   ));
   foreach ($memberships as $membership)
   {
      if ($membership['Membership']['title'] != 'Member')
      {
         echo $this -> Html -> tableCells(array(
            $this -> Html -> link($membership['Organization']['name'], array(
               'controller' => 'organizations',
               'action' => 'view',
               $membership['Organization']['id']
            )),
            $membership['Membership']['title'],
            $membership['Membership']['start_date']
         ));
      }
   }
   ?>
</table>
<?php
echo $this -> Html -> tag('h1', 'General Affiliations');
?>
<table class='listing'>
   <?php
   echo $this -> Html -> tableHeaders(array(
      'Organization',
      'Title',
      'Start Date'
   ));
   foreach ($memberships as $membership)
   {
      if ($membership['Membership']['title'] == 'Member')
      {
         echo $this -> Html -> tableCells(array(
            $this -> Html -> link($membership['Organization']['name'], array(
               'controller' => 'organizations',
               'action' => 'view',
               $membership['Organization']['id']
            )),
            $membership['Membership']['title'],
            $membership['Membership']['start_date']
         ));
      }
   }
   ?>
</table>
<?php
$this -> end();
?>