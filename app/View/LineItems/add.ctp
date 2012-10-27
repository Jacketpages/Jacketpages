<?php
$this -> extend('/Common/common');
$this -> assign('title', 'Add Line Item to Bill: ' . $bill['Bill']['title']);
$this -> start('middle');
echo $this -> Form -> create();
echo $this -> Form -> hidden('bill_id', array(
						'value' => $bill['Bill']['id']
				));
echo $this -> Form -> input('name', array('label' => 'Line Item Name'));
echo $this -> Form -> input('account', array('label' => 'Account', 'options' => array(
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
echo $this -> Form -> input('cost_per_unit', array('label' => 'Cost Per Unit'));
echo $this -> Form -> input('quantity', array('label' => 'Quantity'));
echo $this -> Form -> input('total_cost', array('label' => 'Total Cost'));
if($bill['Bill']['type'] != 'Resolution')
{
echo $this -> Form -> input('amount', array('label' => 'Amount Requested'));
	
}
echo $this -> Form -> end('Submit');
$this -> end();
?>