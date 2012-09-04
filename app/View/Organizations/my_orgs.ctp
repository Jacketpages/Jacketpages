<?php
/**
 * @author Stephen Roca
 * @since 8/24/2012
 */

$this -> extend('/Common/common');
$this -> assign('title', 'My Organizations and Positions');
$this -> start('middle');
echo $this -> Html -> tag('h1', 'Executive Positions');
echo $this -> Html -> tag('h1', 'General Affilitations');
$this -> end();
?>