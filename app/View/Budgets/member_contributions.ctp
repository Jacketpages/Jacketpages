<?php
/**
 * @author Stephen Roca
 * @since 9/10/2013
 */

$this -> extend('/Common/common');
$this -> assign('title', 'Member Contributions');
$this -> start('middle');
echo $this -> Html -> para('','In this section, list all personal items such as swimsuit or shoes, 
which each member must purchase to be involved in the organization\'s activities. 
Note: these items must be in addition to the amount paid to the club in dues or donations.');

$this -> end();
?>
