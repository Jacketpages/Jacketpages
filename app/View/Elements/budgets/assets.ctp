<?php
/**
 * @author Stephen Roca
 * @since 9/27/2013
 */
echo $this -> Html -> div(null, null, array('id' => "assets_accordion"));
echo $this -> Html -> tag('h1', 'Assets');
echo $this -> Html -> div();
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableHeaders(array(
	'Asset',
	'Amount',
	'',
	'',
	'',
	''
));
if(!isset($assets))
{
	$assets = array(array('Asset' => array('id'=> '','item'=> '','amount'=> '')));
}
foreach ($assets as $key => $asset)
{
	echo $this -> Html -> tableCells(array( array(
			$this -> Form -> input("$key.Asset.id", array(
				'id' => 'AssetId' . $key,
				'type' => 'hidden',
				'value' => $asset['Asset']['id']
			)) . $this -> Form -> input("$key.Asset.item", array(
				'label' => false,
				'id' => 'Item' . $key,
				'value' => $asset['Asset']['item']
			)),
			$this -> Form -> input("$key.Asset.amount", array(
				'label' => false,
				'id' => 'Amount' . $key,
				'value' => $asset['Asset']['amount']
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
