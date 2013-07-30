<?php
/**
 * This page defines the standard links that appear in the left sidebar on
 * every page of JacketPages.
 * @author Stephen Roca
 * @since 5/20/2012
 */
?>
<!-- Define the links that are needed. -->
<?php
   $campusCalendar = $this -> Html -> link('Campus Calendar', array(
      'controller' => 'pages',
      'action' => 'calendar'
   ));
   $studentInvolvement = $this -> Html -> link('Student Involvement', 'http://www.involvement.gatech.edu/');
   $studentGovernment = $this -> Html -> link('Student Government', 'http://www.sga.gatech.edu/');
   $studentAffairs = $this -> Html -> link('Student Affairs', 'http://www.studentaffairs.gatech.edu/');
   $gatechMain = $this -> Html -> link('Georgia Tech Main', 'http://www.gatech.edu');
   $contactJacktpages = $this -> Html -> link('Contact JacketPages', array('controller' => 'pages', 'action' => 'contact'));
?>
<!-- Define the nested list with those links. -->
<?php
   $sidebarList = array(
      $campusCalendar,
      'Campus Links' => array(
         $gatechMain,
         $studentAffairs,
         $studentGovernment,
         $studentInvolvement
      ),
      $contactJacktpages
   );
?>
<!-- Display the nested list. -->
<?php
echo $this -> Html -> nestedList($sidebarList, array('class' => 'links'));
?>