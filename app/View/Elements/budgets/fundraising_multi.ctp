<?php
/**
 * @author Stephen Roca
 * @since 9/13/2013
 */
echo $this -> Html -> div();
$tableId = 'FundraisingTable' . $num;
echo $this -> Html -> tableBegin(array('class' => 'listing', 'id' => $tableId));
echo $this -> Html -> tableHeaders(array(
		'Fundraiser/Activity',
	'Date',
	'Revenue',
	'',
	'',
	'',
	''
));
if (!isset($fundraisings) || count($fundraisings) == 0)
{
	$fundraisings[] = array('Fundraising' => array(
			'id' => '',
			'activity' => null,
			'amount' => '',
			'date' => '',
			'revenue' => ''
		));
}
foreach ($fundraisings as $key => $fundraising)
{
	echo $this -> Html -> tableCells(array(
		$this -> Form -> hidden($num . '.Fundraising.id', array(
			'value' => $fundraising['Fundraising']['id'],
			'id' => $num . 'FundraisingId' . $key
		)) . $this -> Form -> text($key . '.Fundraising.activity', array(
			'label' => false,
			'value' => $fundraising['Fundraising']['activity'],
			'id' => $num . 'Activity' . $key
		)),
		$this -> Form -> text($key . '.Fundraising.date', array(
			'label' => false,
			'value' => $fundraising['Fundraising']['date'],
			'id' => $num . 'Amount' . $key,
		)),
		$this -> Form -> text($key . '.Fundraising.revenue', array(
			'label' => false,
			'value' => $fundraising['Fundraising']['revenue'],
			'id' => $num . 'Revenue' . $key,
		)),
		$this -> Form -> button($this -> Html -> image('up.gif'), array(
			'type' => 'button',
			'onclick' => "moveUp('$tableId' , $key)",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('down.gif'), array(
			'type' => 'button',
			'onclick' => "moveDown('$tableId' , $key)",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('plus_sign.gif'), array(
			'type' => 'button',
			'onclick' => "addRow('$tableId' ,  $key, $num)",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('minus_sign.png'), array(
			'type' => 'button',
			'onclick' => "deleteRow('$tableId' , $key, $num)",
			'escape' => false
		)),
	), array('id' => 'Fundraising'));
}
echo $this -> Html -> tableEnd();
echo $this -> Html -> useTag('tagend', 'div');
?>
