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
// @TODO Massage this paginator declaration to have this page use ajax.
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
?>

<div id="alphabet">
    <div id="leftHalf">
        <?php
      echo $this -> Form -> create();
      echo $this -> Form -> input('keyword', array(
         'label' => array('text' => 'Search', 'style' => 'display:inline'),
         'id' => 'search',
         'default' => $this -> Session -> read('Search.keyword'),
         'width' => '80%'
      ));
      echo $this -> Form -> end();
        ?>
    </div>
    <div id="rightHalf">
        <ul>
            <?php
            // TODO Clean up this whole alphabet thing. Is there an easier way?
            // set up alphabet
            $alpha = range('A', 'Z');
            for ($i = 0; $i < count($alpha); $i++)
            {
               echo "<li>\n";
               echo $this -> Html -> link($alpha[$i], array(
                  'controller' => strtolower($this -> params['controller']),
                  'action' => 'index',
                  strtolower($alpha[$i])
               ));
               echo "&nbsp";
               echo "</li>\n";
            }
            echo "<li>\n";
            echo $this -> Html -> link('ALL', array(
               'controller' => strtolower($this -> params['controller']),
               'action' => 'index'
            ));
            ?>
            </li>
        </ul>
    </div>
</div>
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

<div class="paging">
    <?php echo $this -> Paginator -> prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled'));?>
    |
    <?php echo $this -> Paginator -> numbers();?>
    |
    <?php echo $this -> Paginator -> next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
    <br>
    <br>
    <?php
   echo $this -> Paginator -> counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of
{:count} total, starting on record {:start}, ending on {:end}', true)));
// Implement Ajax for this page.
echo $this -> Js -> writeBuffer();
    ?>
</div>
</div>
<?php
 $this -> end();?>
 