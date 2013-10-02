<?php
/**
 * @author Stephen Roca
 * @since 9/27/2013
 */
 $this -> start('script');
echo $this -> Html -> script('budgets/assets');
$this -> end();
echo $this -> Html -> div(null, null, array('id' => "assets_accordion", 'class' => 'accordion_no_padding'));
echo $this -> Html -> link('Assets', array());
echo $this -> Html -> div();
echo $this -> Html -> tableBegin(array('class' => 'listing','id' => 'AssetsTable'));
echo $this -> Html -> tableHeaders(array(
	array('Asset' => array('width' => '400px')),
	'Amount',
	array('Tag' => array('width' => '30px', 'title' => 'Has GT tag')),
	'',
	'',
	'',
	''
));
if(!isset($assets) || count($assets) == 0)
{
	$assets = array(array('Asset' => array('id'=> '','item'=> '','amount'=> '', 'tagged' => '')));
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
			$this -> Form -> input("$key.Asset.tagged", array(
				'label' => false,
				'id' => 'Tagged' . $key,
				'checked' => $asset['Asset']['tagged'],
				'type' => 'checkbox',
				'style' => 'height: 18px'
			)),
			$this -> Form -> button($this -> Html -> image('up.gif'), array(
				'type' => 'button',
				'onclick' => "amoveUp(" . $key . ")",
				'escape' => false
			)),
			$this -> Form -> button($this -> Html -> image('down.gif'), array(
				'type' => 'button',
				'onclick' => "amoveDown(" . $key . ")",
				'escape' => false
			)),
			$this -> Form -> button($this -> Html -> image('plus_sign.gif'), array(
				'type' => 'button',
				'onclick' => "aaddRow(" . $key . ")",
				'escape' => false
			)),
			$this -> Form -> button($this -> Html -> image('minus_sign.png'), array(
				'type' => 'button',
				'onclick' => "adeleteRow(" . $key . ")",
				'escape' => false
			)),
		)));
}
echo $this -> Html -> tableEnd();
echo $this -> Html -> useTag('tagend', 'div');
echo $this -> Html -> useTag('tagend', 'div');
?>
