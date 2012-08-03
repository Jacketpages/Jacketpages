<?php 
class Bill extends AppModel
{
	public $belongsTo = array(
      'Status' => array(
         'className' => 'BillStatus',
         'foreignKey' => 'STATUS'
      ));
}
?>
