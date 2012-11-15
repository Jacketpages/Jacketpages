<?php
/**
 * @author Stephen Roca
 * @since 10/29/2012
 */
$this -> extend('/Common/common');
$this -> assign('title', 'LineItems');
$this -> start('middle');

echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableHeaders(array(
	'#',
	'State',
	'Name',
	'Cost Per Unit',
	'Quantity',
	'Total Cost',
	'Amount',
	'',
	'',
	'',
	''
));
echo $this -> Form -> create();
//debug($lineitems);
foreach ($lineitems as $key => $lineitem)
{
	echo $this -> Html -> tableCells(array(
		$this -> Form -> input('LineItem.line_number', array(
			'readonly' => true,
			'type' => 'text',
			'label' => '',
			'value' => $key + 1
		)),
		$this -> Form -> text('LineItem.state', array(
			'label' => '',
			'value' => $lineitem['LineItem']['state']
		)),
		$this -> Form -> text('LineItem.name', array(
			'label' => '',
			'value' => $lineitem['LineItem']['name']
		)),
		$this -> Form -> text('LineItem.cost_per_unit', array(
			'label' => '',
			'value' => $lineitem['LineItem']['cost_per_unit']
		)),
		$this -> Form -> text('LineItem.quantity', array(
			'label' => '',
			'value' => $lineitem['LineItem']['quantity']
		)),
		$this -> Form -> text('LineItem.total_cost', array(
			'label' => '',
			'value' => $lineitem['LineItem']['total_cost']
		)),
		$this -> Form -> text('LineItem.amount', array(
			'label' => '',
			'value' => $lineitem['LineItem']['amount']
		)),
		$this -> Form -> button($this -> Html -> image('down.gif'), array(
			'type' => 'button',
			'onclick' => "moveDown()",
			'escape' => false
		)),
		$this -> Form -> button($this -> Html -> image('up.gif'), array(
			'type' => 'button',
			'onclick' => "moveUp()",
			'escape' => false
		)),
		$this -> Fom -> button($this -> Html -> image('plus_sign.gif'), array(
			'type' => 'button',
			'onclick' => "addRow()",
			'escape' => false
		)),
		$this -> Fom -> button($this -> Html -> image('minus_sign.gif'), array(
			'type' => 'button',
			'onclick' => "deleteRow()",
			'escape' => false
		)),
	));
}
echo $this -> Html -> tableEnd();

echo $this -> Form -> end('Submit');
?>
<p id="demo">
	My First Paragraph
</p>
<script type="text/javascript">
var emptyRow =<?php
echo $this -> Html -> tableCells(array(
	$this -> Form -> input('LineItem.line_number', array(
		'readonly' => true,
		'type' => 'text',
		'label' => '',
		'value' => $key + 1
	)),
	$this -> Form -> text('LineItem.state', array(
		'label' => '',
		'value' => $lineitem['LineItem']['state']
	)),
	$this -> Form -> text('LineItem.name', array(
		'label' => '',
		'value' => $lineitem['LineItem']['name']
	)),
	$this -> Form -> text('LineItem.cost_per_unit', array(
		'label' => '',
		'value' => $lineitem['LineItem']['cost_per_unit']
	)),
	$this -> Form -> text('LineItem.quantity', array(
		'label' => '',
		'value' => $lineitem['LineItem']['quantity']
	)),
	$this -> Form -> text('LineItem.total_cost', array(
		'label' => '',
		'value' => $lineitem['LineItem']['total_cost']
	)),
	$this -> Form -> text('LineItem.amount', array(
		'label' => '',
		'value' => $lineitem['LineItem']['amount']
	)),
	$this -> Form -> button($this -> Html -> image('down.gif'), array(
		'type' => 'button',
		'onclick' => "moveDown()",
		'escape' => false
	)),
	$this -> Form -> button($this -> Html -> image('up.gif'), array(
		'type' => 'button',
		'onclick' => "moveUp()",
		'escape' => false
	)),
	$this -> Fom -> button($this -> Html -> image('plus_sign.gif'), array(
		'type' => 'button',
		'onclick' => "addRow()",
		'escape' => false
	)),
	$this -> Fom -> button($this -> Html -> image('minus_sign.gif'), array(
		'type' => 'button',
		'onclick' => "deleteRow()",
		'escape' => false
	)),
));
?>
	function moveDown() {
		document.getElementById().innerHTML.append
		alert("Hey");
	}
</script>
<?php
$this -> end();
?>