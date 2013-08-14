<?php
/**
 * @author Stephen Roca
 * @since 03/22/2012
 * This file defines the utility bar's menu options/links.
 */
?>

<div id="utilityBarWrapper">
   <div class="utilityMenu" id="utilityBar">
<?php
echo $this -> element('utilityBar/account');
echo $this -> element('utilityBar/bills');
echo $this -> element('utilityBar/organizations');
echo $this -> element('utilityBar/student_government');
echo $this -> element('utilityBar/administration');
echo $this -> Html -> nestedList(array($this -> Html -> tag('p', 'Help', array('onclick' => 'openHelp()'))));
?>
</div>
</div> 