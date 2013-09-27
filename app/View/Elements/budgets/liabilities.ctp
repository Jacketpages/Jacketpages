<?php
/**
 * @author Stephen Roca
 * @since 9/27/2013
 */
 
echo $this -> Html -> div(null, null, array('id' => "liabilities_accordion"));
echo $this -> Html -> link('Liabilities', array());
echo $this -> Html -> div();
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableHeaders(array(
	'Liability',
	'Amount',
	'',
	'',
	'',
	''
));
if(!isset($liabilities))
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
			$this -> Form -> button($this -> Html -> image('up.gif'), array(
				'type' => 'button',
				'onclick' => "moveUp(" . $key . ")",
				'escape' => false
			)),
			$this -> Form -> button($this -> Html -> image('down.gif'), array(
				'type' => 'button',
				'onclick' => "moveDown(" . $key . ")",
				'escape' => false
			)),
			$this -> Form -> button($this -> Html -> image('plus_sign.gif'), array(
				'type' => 'button',
				'onclick' => "addRow(" . $key . ")",
				'escape' => false
			)),
			$this -> Form -> button($this -> Html -> image('minus_sign.png'), array(
				'type' => 'button',
				'onclick' => "deleteRow(" . $key . ")",
				'escape' => false
			)),
		)));
}
echo $this -> Html -> tableEnd();
echo $this -> Html -> useTag('tagend', 'div');
echo $this -> Html -> useTag('tagend', 'div');
?>
