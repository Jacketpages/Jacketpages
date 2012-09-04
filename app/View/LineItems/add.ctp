<?php
$this -> extend('/Common/common');
$this -> assign('title', 'Add Line Item to Bill: ' . $bill['Bill']['TITLE']);
$this -> start('middle');
echo $this -> Form -> create();
echo $this -> Form -> input('NAME', array('label' => 'Line Item Name'));
echo $this -> Form -> input('ACCOUNT', array('label' => 'Account', 'options' => array(
								'PY' => 'Prior Year',
								'CO' => 'Capital Outlay',
								'ULR' => 'Undergraduate Legislative Reserve',
								'GLR' => 'Graduate Legislative Reserve'
						)));
echo $this -> Form -> input('STATE', array('label' => 'State', 'options' => array(
								'Submitted' => 'Submitted',
								'JFC' => 'JFC',
								'Undergraduate' => 'Undergraduate',
								'Graduate' => 'Graduate',
								'Conference' => 'Conference',
								'Final' => 'Final'
						)));
echo $this -> Form -> input('COST_PER_UNIT', array('label' => 'Cost Per Unit'));
echo $this -> Form -> input('QUANTITY', array('label' => 'Quantity'));
echo $this -> Form -> input('TOTAL_COST', array('label' => 'Total Cost', 'readonly' => 'readonly'));
if($bill['Bill']['TYPE'] != 'Resolution')
{
echo $this -> Form -> input('AMOUNT', array('label' => 'Amount Requested'));
	
}
echo $this -> Form -> end('Submit');
$this -> end();
?>