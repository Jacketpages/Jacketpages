<?php
/**
 * @author Stephen Roca
 * @since 7/29/2013
 */

$rows = array(
	array(
		'Type',
		$bill['Bill']['type']
	),
	array(
		'Category',
		$bill['Bill']['category']
	),
	array(
		'Status',
		$bill['Status']['name']
	)
);
echo $this -> Html -> tag('h1', 'Status');
echo $this -> Html -> tableBegin(array('class' => 'list'));
foreach ($rows as $row)
{
	echo $this -> Html -> tableCells($row);
}
echo $this -> Html -> tableEnd();
?>