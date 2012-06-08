<?php
$this -> Paginator -> options(array(
      'update' => '#forupdate',
      'indicator' => '#indicator',
      'evalScripts' => true,
      'before' => $this -> Js -> get('#listing') -> effect('fadeOut', array('buffer' => false)),
      'complete' => $this -> Js -> get('#listing') -> effect('fadeIn', array('buffer' => false)),
   ));
echo $this -> Html -> addCrumb('All Organizations', '/organizations');
$this -> extend('/Common/list');
$this -> assign('title', 'Organizations');
$this -> start('sidebar');
echo $this -> Html -> nestedList(array(
   $this -> Html -> link('Create Organization', array('action' => 'add')),
   $this -> Html -> link('Export Organizations', array(
      'admin' => false,
      'action' => 'export'
   )),
   'Organization Category'
));
?>
<div id='category'>
    <?php
   echo $this -> Form -> create();
   echo $this -> Form -> input('category', array(
      'label' => false,
      //'default' => $cat,
      'options' => array(
         'all' => 'All',
         'CPC Sorority' => 'CPC Sorority',
         'Cultural/Diversity' => 'Cultural/Diversity',
         'Departmental Sponsored' => 'Departmental Sponsored',
         'Departments' => 'Departments',
         'Governing Boards' => 'Governing Boards',
         'Honor Society' => 'Honor Society',
         'IFC Fraternity' => 'IFC Fraternity',
         'Institute Recognized' => 'Institute Recognized',
         'MGC Chapter' => 'MGC Chapter',
         'None' => 'None',
         'NPHC Chapter' => 'NPHC Chapter',
         'Production/Performance/Publication' => 'Production/Performance/Publication',
         'Professional/Departmental' => 'Professional/Departmental',
         'Recreational/Sports/Leisure' => 'Recreational/Sports/Leisure',
         'Religious/Spiritual' => 'Religious/Spiritual',
         'Residence Hall Association' => 'Residence Hall Association',
         'Service/Political/Educational' => 'Service/Political/Educational',
         'Student Government' => 'Student Government',
         'Umbrella' => 'Umbrella',
         'Other' => 'Other'
      )
   ));
   echo $this -> Form -> end(__('Search', true));
    ?>
</div>
<!--    TODO edit this to where this is not done with a random div -->
<div style="border-bottom: 1px solid #DDD;"></div>
<?php
$this -> end();

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
         'style' => 'display:inline'
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
   $this -> start('listing');
?>
<div id='forupdate'>
<table class='listing'>
    <?php
   echo $this -> Html -> tableHeaders(array(
      '',
      $this -> Paginator -> sort('NAME', 'Name'),
      'Description',
      '',
      ''
   ), array('class' => 'links'));
   foreach ($organizations as $organization)
   {
      if (strlen($organization['Organization']['LOGO_NAME']) < 1)
      {
         $logo = $this -> Html -> image('/img/default_logo.gif', array('width' => '60'));
      }
      else
      {
         $logo = $this -> Html -> image(array(
            'controller' => 'organizations',
            'action' => 'getLogo',
            $organization['Organization']['ID']
         ), array('width' => '60'));
      }
      $summary = $organization['Organization']['DESCRIPTION'];
      //$summary = Sanitize::html($summary, array('remove' => TRUE));
      if (strlen($summary) > 200)
      {
         $summary = substr($summary, 0, strrpos(substr($summary, 0, 200), ' ')) . '...';
      }
      echo $this -> Html -> tableCells(array(
         $logo,
         $this -> Html -> link($organization['Organization']['NAME'], array(
                  'action' => 'view',
                  $organization['Organization']['ID']
            )),
         $summary,
         $this -> Html -> link(__('Edit', true), array(
            'action' => 'edit',
            $organization['Organization']['ID']
         )),
         $this -> Html -> link(__('Delete', true), array(
            'action' => 'delete',
            $organization['Organization']['ID']
         ), null, sprintf(__('Are you sure you want to delete this organization?', true)))
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
?>)
