<?php
$this -> Excel -> create();
foreach($export as $row)
{
	$this -> Excel -> addRow($row);
}
$this -> Excel->generate("Organizations");
?>