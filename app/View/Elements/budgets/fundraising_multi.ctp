<?php
/**
 * @author Stephen Roca
 * @since 9/13/2013
 */
echo $this -> Html -> div();
$tableId = 'FundraiserTable' . $othernum;
echo $this -> Html -> tableBegin(array('class' => 'listing', 'id' => $tableId));
echo $this -> Html -> tableHeaders(array(
	array('Fundraiser/Activity' => array('width' => '400px')),
	'Date',
	'Revenue',
	'',
	'',
	'',
	''
));
if (!isset($fundraisings) || count($fundraisings) == 0)
{
	$fundraisings = array(0=> array('Fundraiser' => array(
			'id' => '',
			'activity' => null,
			'amount' => '',
			'date' => '',
			'revenue' => ''
		)));
}
foreach ($fundraisings as $key => $fundraising)
{
	echo $this -> Html -> tableCells(array(
		$this -> Form -> hidden($num . '.'. $key . '.Fundraiser.id', array(
			'value' => $fundraising['Fundraiser']['id'],
			'id' => $othernum . 'FundraiserId' . $key
		)) . $this -> Form -> hidden($num . '.'. $key . '.Fundraiser.type', array(
			'value' => $num,
			'id' => $othernum . 'FundraiserType' . $key
		)).$this -> Form -> textarea($num . '.'. $key . '.Fundraiser.activity', array(
			'label' => false,
			'value' => $fundraising['Fundraiser']['activity'],
			'id' => $othernum . 'Activity' . $key,
			'rows' => 4
		)),
		$this -> Form -> text($num . '.'. $key . '.Fundraiser.date', array(
			'label' => false,
			'value' => $fundraising['Fundraiser']['date'],
			'id' => $othernum . 'Date' . $key,
		)),
		$this -> Form -> text($num . '.'. $key . '.Fundraiser.revenue', array(
			'label' => false,
			'value' => $fundraising['Fundraiser']['revenue'],
			'id' => $othernum . 'Revenue' . $key,
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
			'onclick' => "addRow('$tableId' ,  $key, $othernum)",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('minus_sign.png'), array(
			'type' => 'button',
			'onclick' => "deleteRow('$tableId' , $key, $othernum)",
			'escape' => false
		)),
	), array('id' => 'Fundraiser'));
}
echo $this -> Html -> tableEnd();
echo $this -> Html -> useTag('tagend', 'div');
?>