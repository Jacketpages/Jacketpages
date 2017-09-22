<?php
/**
 * @author Stephen Roca
 * @since 03/22/2012
 * This file defines the utility bar's menu options/links.
 *
 *	The utility bar is being replaced with the mainMenuBar.ctp
 */

echo $this -> Html -> div(null, null, array('id' => 'utilityBarWrapper'));
echo $this -> Html -> div("utilityMenu", null, array('id' => 'utilityBar'));

echo $this->element('utilityBar/support');
echo $this -> element('utilityBar/bills');
echo $this -> element('utilityBar/organizations');
echo $this -> element('utilityBar/student_government');
echo $this -> element('utilityBar/administration');
echo $this -> Html -> nestedList(array($this -> Html -> tag('p', 'Help', array('onclick' => 'openHelp()'))));

echo $this -> Html -> useTag('tagend', 'div');
echo $this -> Html -> useTag('tagend', 'div');
?>