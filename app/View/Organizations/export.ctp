<?php
/*$this -> Excel -> create();
foreach($export as $row)
{
	$this -> Excel -> addRow($row);
}
$this -> Excel->generate("Organizations");*/

$this -> Csv -> start();
foreach($export as $row)
{
	$this -> Csv -> append($row);
}
$this -> Csv->setFilename("Organizations.csv");
echo $this -> Csv -> end();
?>