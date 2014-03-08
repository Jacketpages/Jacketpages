<?php
$this -> Excel -> create();
foreach($export['Organizations'] as $org_name => $budgets)
{
	$this -> Excel -> addRow($org_name);
	foreach($budgets['Categories'] as $category_name => $categories)
	{
		$this -> Excel -> addRow($category_name);
		$line_number = 1;
		foreach($categories['LineItems'] as $line_item_name => $amounts)
		{
			if($line_number == 1)
			{
				$this -> Excel -> addRow(array_merge(array("#","Line Item Name", "Requested", "Allocated", "Submitted"), $states));
			}
			$this -> Excel -> addRow(array_merge(array($line_number), array($line_item_name), $amounts));
			$line_number++;
		}
	}
}
$this -> Excel -> generate('Budgets');
?>