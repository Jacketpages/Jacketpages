<?php
/**
 * @author Stephen Roca
 * @since 06/22/2012
 */
 $this -> Paginator -> options(array(
      'update' => '#forupdate',
      'indicator' => '#indicator',
      'evalScripts' => true,
      'before' => $this -> Js -> get('#listing') -> effect('fadeOut', array('buffer' => false)),
      'complete' => $this -> Js -> get('#listing') -> effect('fadeIn', array('buffer' => false)),
   ));
$this -> extend("/Common/list");
$this -> assign('title', 'SGA Records');
$this -> start('search');
?>

<div id="alphabet">
    <div id="leftHalf">
        <?php
      echo $this -> Form -> create();
      echo $this -> Form -> input('keyword', array(
         'label' => array(
            'text' => 'Search',
            'style' => 'display:inline'
         ),
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
<?php
$this -> end();
$this -> start('listing');
?>
<div id='forupdate'>
<table class='listing'>
    <?php
      // Print out all of the table headers
   echo $this -> Html -> tableHeaders(array(
      $this -> Paginator -> sort('NAME', 'Name'),
      $this -> Paginator -> sort('HOUSE', 'House'),
      $this -> Paginator -> sort('DEPARTMENT', 'Department'),
      $this -> Paginator -> sort('STATUS', 'Status'), "",""
   ), array('class' => 'links'));
   foreach ($sgapeople as $sgaperson)
   {
      echo $this -> Html -> tableCells(array(
         $this -> Html -> link($sgaperson['User']['NAME'], array(
            'controller' => 'users',
            'action' => 'view',
            $sgaperson['SgaPerson']['USER_ID']
         )),
         $sgaperson['SgaPerson']['HOUSE'],
         $sgaperson['SgaPerson']['DEPARTMENT'],
         $sgaperson['SgaPerson']['STATUS'],
         $this -> Html -> link('Edit', array(
            'action' => 'edit',
            $sgaperson['SgaPerson']['ID']
         )),
         // @TODO Figure out what the sprintf thing is for. Display error message. Is it the cakephp standard?
         $this -> Html -> link(__('Delete', true), array(
            'action' => 'delete',
            $sgaperson['SgaPerson']['ID']
         ), null, sprintf(__('Are you sure you want to delete %s?', true), $sgaperson['User']['NAME']))
      ));
   }
    ?>
</table>
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
$this -> end();
?>