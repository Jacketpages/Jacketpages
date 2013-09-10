<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

$this -> extend('/Common/common');
$this -> assign('title', 'Revenue Generated From Fundraising');
$this -> start('middle');

echo $this -> Html -> tableBegin(array('class' => 'listing'));

echo $this -> Html -> tableHeaders(array(
	'Members',
	'Dues',
	'Dues Income'
));
$types = array(
	'Year-round',
	'Fall',
	'Spring',
	'Summer'
);
foreach ($types as $type)
{
	echo $this -> Html -> tableCells(array(
		$type,
		$this -> Form -> input('num'),
		$this -> Form -> input('dues'),
		$this -> Form -> input('income')
	));
}
echo $this -> Html -> tableCells(array('', '', '',  $this -> Form -> input('total')));

echo $this -> Html -> tableEnd();

echo $this -> element('budgets/fundraising_multi_enter');

$this -> end();
?>
