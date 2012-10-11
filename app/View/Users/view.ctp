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
$this -> Html -> addCrumb($user['User']['NAME'], '/users/view/' . $user['User']['ID']);
$this -> extend('/Common/common');

$links[] = $this -> Html -> link(__('Edit User', true), array(
      'action' => 'edit',
      $user['User']['ID']
   )); 
if($userDeletePerm)
{
	$links[] = $this -> Html -> link(__('Delete User', true), array(
      'action' => 'delete',
      $user['User']['ID']
   ), null, sprintf(__('Are you sure you want to delete %s?', true), $user['User']['NAME']));
}

$this -> start('sidebar');
echo $this -> Html -> nestedList($links
, array(), array('id' => 'underline'));
$this -> end();

$this -> assign('title', $user['User']['NAME']);

$this -> start('middle');
?>
<table class='listing' id='halftable'>
 <?php
echo $this -> Html -> tableCells(array(
   'GT Username',
   $user['User']['GT_USER_NAME']
));
echo $this -> Html -> tableCells(array(
   'Email',
   $user['User']['EMAIL']
));
echo $this -> Html -> tableCells(array(
   'Alternate Email',
   $user['User']['ALT_EMAIL']
));
echo $this -> Html -> tableCells(array(
   'Phone',
   $user['User']['PHONE']
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
      if ($membership['Membership']['TITLE'] != 'Member')
      {
         echo $this -> Html -> tableCells(array(
            $this -> Html -> link($membership['Organization']['NAME'], array(
               'controller' => 'organizations',
               'action' => 'view',
               $membership['Organization']['ID']
            )),
            $membership['Membership']['TITLE'],
            $membership['Membership']['START_DATE']
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
      if ($membership['Membership']['TITLE'] == 'Member')
      {
         echo $this -> Html -> tableCells(array(
            $this -> Html -> link($membership['Organization']['NAME'], array(
               'controller' => 'organizations',
               'action' => 'view',
               $membership['Organization']['ID']
            )),
            $membership['Membership']['TITLE'],
            $membership['Membership']['START_DATE']
         ));
      }
   }
   ?>
</table>
<?php
$this -> end();
?>