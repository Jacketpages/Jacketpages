<?php echo $this -> Html -> addCrumb('All Organizations', '/organizations');?>
<?php echo $this -> Html -> addCrumb($organization['Organization']['NAME'], '/organizations/view/' . $organization['Organization']['ID']);?>
<?php
$this -> extend('/Common/common');
$this -> start('sidebar');
echo $this -> Html -> nestedList(array(
$this -> Html -> link(__('Edit Information', true), array(
               'action' => 'edit',
               $organization['Organization']['ID']
         )),
         $this -> Html -> link(__('Edit Logo', true), array(
               'action' => 'addlogo',
               $organization['Organization']['ID'],
               'officer' => false,
               'admin' => false,
               'owner' => false
         )),
         $this -> Html -> link(__('Edit Officers/Roster', true), array(
               'controller' => 'organizations',
               'action' => 'roster',
               $organization['Organization']['ID'],
               'admin' => false
         )),
         $this -> Html -> link(__('View/Add Documents', true), array(
               'controller' => 'charters',
               'action' => 'index',
               $organization['Organization']['ID'],
               'admin' => false
         )),
         $this -> Html -> link(__('Join Organization', true), array(
               'action' => 'join',
               $organization['Organization']['ID'],
               'admin' => false,
               'owner' => false,
               'officer' => false
         ), null, sprintf(__('Are you sure you want to join %s?', true), $organization['Organization']['NAME'])),
         $this -> Html -> link(__('Delete Organization', true), array(
               'action' => 'delete',
               $organization['Organization']['ID']
         ), null, sprintf(__('Are you sure you want to delete %s?', true), $organization['Organization']['NAME']))), array(), array('id' => 'underline'));
$this -> end();
$this -> assign('title', $organization['Organization']['NAME']);
$this -> start('middle');
echo $this -> Html -> tag('h3', 'Officers:');
?>
<table class='listing' id='halftable'>
    <?php
   echo $this -> Html -> tableCells(array(
      $president[0]['NAME'],
      $president['Membership']['TITLE']
   ));
   if ($treasurer[0]['NAME'])
   {
      echo $this -> Html -> tableCells(array(
         $treasurer[0]['NAME'],
         $treasurer['Membership']['TITLE']
      ));
   }
   if ($advisor[0]['NAME'])
   {
      echo $this -> Html -> tableCells(array(
         $advisor[0]['NAME'],
         $advisor['Membership']['TITLE']
      ));
   }
   foreach ($officers as $officer)
   {
      echo $this -> Html -> tableCells(array(
         $officer[0]['NAME'],
         $officer['Membership']['TITLE']
      ));
   }
    ?>
</table>

<?php
echo $this -> Html -> tag('h1', 'Description');
echo $this -> Html -> para('leftalign', $organization['Organization']['DESCRIPTION']);
//debug($organization);
echo $this -> Html -> nestedList(array(
   'Status: ' . $organization['Organization']['STATUS'],
   'Organization Contact: ' . $organization['User']['NAME'],
   'Website: ' . $this -> Html -> link($organization['Organization']['WEBSITE']),
   'Meetings: ' .  $organization['Organization']['METNG_INFO']
));
echo $this -> Html -> tag('h1', 'Budgets');
echo $this -> Html -> tag('h1', 'Bills');
echo $this -> Html -> tag('h1', 'Members');
echo $this -> Html -> tag('h1', 'Pending Members');
$this -> end();
?>