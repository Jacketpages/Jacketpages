<?php
/**
 * @author Stephen Roca
 * @since 9/27/2013
 */
  $this -> start('script');
echo $this -> Html -> script('budgets/liabilities');
$this -> end();
echo $this -> Html -> div(null, null, array('id' => "liabilities_accordion", 'class' => 'accordion_no_padding'));
echo $this -> Html -> link('Liabilities', array());
echo $this -> Html -> div();
echo $this -> Html -> tableBegin(array('class' => 'listing accordion_no_padding', 'id' => 'LiabilitiesTable'));
echo $this -> Html -> tableHeaders(array(
	array('Liability' => array('width' => '400px')),
	'Amount',
	array('' => array('width' => '30px')),
	'',
	'',
	'',
	''
));
if(!isset($liabilities) || count($liabilities) == 0)
{
	$liabilities = array(array('Liability' => array('id'=> '','item'=> '','amount'=> '')));
}
foreach ($liabilities as $key => $liability)
{
	echo $this -> Html -> tableCells(array( array(
			$this -> Form -> input("$key.Liability.id", array(
				'id' => 'AssetId' . $key,
				'type' => 'hidden',
				'value' => $liability['Liability']['id']
			)) . $this -> Form -> input("$key.Liability.item", array(
				'label' => false,
				'id' => 'Item' . $key,
				'value' => $liability['Liability']['item']
			)),
			$this -> Form -> input("$key.Liability.amount", array(
				'label' => false,
				'id' => 'Amount' . $key,
				'value' => $liability['Liability']['amount']
			)),
			'',
			$this -> Form -> button($this -> Html -> image('up.gif'), array(
				'type' => 'button',
				'onclick' => "lmoveUp(" . $key . ")",
				'escape' => false
			)),
			$this -> Form -> button($this -> Html -> image('down.gif'), array(
				'type' => 'button',
				'onclick' => "lmoveDown(" . $key . ")",
				'escape' => false
			)),
			$this -> Form -> button($this -> Html -> image('plus_sign.gif'), array(
				'type' => 'button',
				'onclick' => "laddRow(" . $key . ")",
				'escape' => false
			)),
			$this -> Form -> button($this -> Html -> image('minus_sign.png'), array(
				'type' => 'button',
				'onclick' => "ldeleteRow(" . $key . ")",
				'escape' => false
			)),
		)));
}
echo $this -> Html -> tableEnd();
echo $this -> Html -> useTag('tagend', 'div');
echo $this -> Html -> useTag('tagend', 'div');
?>
