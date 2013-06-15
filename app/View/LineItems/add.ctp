<?php
$this -> extend('/Common/common');
$this -> assign('title', 'Add Line Item to Bill: ' . $bill['Bill']['title']);
$this -> start('middle');
echo $this -> Form -> create();
echo $this -> Form -> hidden('bill_id', array(
						'value' => $bill['Bill']['id']
				));
echo $this -> Form -> input('name', array('label' => 'Line Item Name'));
echo $this -> Form -> input('account', array('id' => 'account', 'label' => 'Account', 'options' => array(
								'PY' => 'Prior Year',
								'CO' => 'Capital Outlay',
								'ULR' => 'Undergraduate Legislative Reserve',
								'GLR' => 'Graduate Legislative Reserve'
						)));
echo $this -> Form -> input('state', array('label' => 'State', 'options' => array(
								'Submitted' => 'Submitted',
								'JFC' => 'JFC',
								'Undergraduate' => 'Undergraduate',
								'Graduate' => 'Graduate',
								'Conference' => 'Conference',
								'Final' => 'Final'
						)));
echo $this -> Form -> input('cost_per_unit', array('label' => 'Cost Per Unit', 'type' => 'text', 'id' => 'cost_per_unit'));
echo $this -> Form -> input('quantity', array('label' => 'Quantity', 'type' => 'text', 'id' => 'quantity'));
echo $this -> Form -> input('total_cost', array('label' => 'Total Cost', 'type' => 'text', 'id' => 'total_cost'));
if($bill['Bill']['type'] != 'Resolution')
{
	echo $this -> Form -> input('amount', array('label' => 'Amount Requested', 'type' => 'text', 'id' => 'amount'));
	
}
echo $this -> Form -> end('Submit');
?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#quantity").change(updateCost);
		$("#cost_per_unit").change(updateCost);
		$("#account").change(updateCost);
	});
	function updateCost() {
		var account = $("#account option:selected").val();
		var cost_per_unit = $("#cost_per_unit").val();
		var quantity = $("#quantity").val();
		if(cost_per_unit.charAt(0) == "$")
		{
			cost_per_unit = cost_per_unit.slice(1);
		}
		if(quantity.charAt(0) == "$")
		{
			quantity = quantity.slice(1);
		}
		var totalCost = cost_per_unit * quantity;
		$("#total_cost").val(totalCost);
		if(account == 'CO') {
			var co = Math.round(totalCost * (2 / 3) * 100) / 100;
			$("#amount").val(co);
		} else {
			$("#amount").val(totalCost);
		}
	}
</script>
<?php
$this -> end();
?>