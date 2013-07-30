<?php
/**
 * @author Stephen Roca
 * @since 06/26/2012
 */
$this -> extend("/Common/list");
$this -> start('sidebar');
echo $this -> Html -> nestedList(array(
	$this -> Html -> link('Create New Bill', array('action' => 'add')),
	$this -> Html -> link('Export FY Data', array(
		'admin' => false,
		'action' => 'export'
	))
), array(), array('id' => 'underline'));
$this -> end();
$this -> assign("title", "Bills");
$this -> start('search');
echo $this -> element('search', array('action' =>  'my_bills', 'endForm' => 0));
$this -> end();
$this -> start('listing');
echo $this -> element('billsTable', array('bills' => $bills));
echo $this -> element('paging');
$this -> end();
?>