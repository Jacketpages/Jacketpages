<?php

// Define the links that are needed.
$campusCalendar = $this -> Html -> link('Campus Calendar', '/');
$contactJacktpages = $this -> Html -> link('Contact JacketPages', array('controller' => 'pages', 'action' => 'contact'));
$privacyPolicy = $this -> Html -> link("Privacy Policy", array('controller' => 'pages', 'action' => 'privacy_policy'));
$gatechMain = $this -> Html -> link('Georgia Tech Main', 'http://www.gatech.edu');
$studentAffairs = $this -> Html -> link('Student Affairs', 'http://www.studentaffairs.gatech.edu/');
$studentGovernment = $this -> Html -> link('Student Government', 'http://www.sga.gatech.edu/');
$studentInvolvement = $this -> Html -> link('Student Involvement', 'http://www.involvement.gatech.edu/');


// Define the nested list with those links.
$utilityLinks = array(
	$campusCalendar,
	$contactJacktpages,
	$privacyPolicy
);
$customLinks = array(
	$gatechMain,
	$studentAffairs,
	$studentGovernment,
	$studentInvolvement
);


// Display the nested list
echo $this->Html->nestedList($utilityLinks, array('class' => 'menu gt-footer-utility-links'));
echo $this->Html->nestedList($customLinks, array('class' => 'menu custom-links-included'));

?>