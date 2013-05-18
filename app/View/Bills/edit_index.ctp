<?php
/**
 * @author Stephen Roca
 * @since 06/26/2012
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Update Bill Index');
$this -> start('sidebar');
echo $this -> Html -> nestedList(array($this -> Html -> link('View All Bills', array('action' => 'index'))), array(), array('id' => 'underline'));
$this -> end();
$this -> start('middle');
debug($bill);
echo $this -> Html -> nestedList(array(
	$this -> Html -> link('Update General Bill Information', array(
		'action' => 'general_info',
		$bill['Bill']['id']
	)),
	$this -> Html -> link('Update Bill Authors and Signatures', array(
		'action' => 'authors_signatures',
		$bill['Bill']['auth_id']
	)),
	$this -> Html -> link('Update Bill Votes', array(
		'action' => 'votes',
		$bill['Bill']['id']
	))
));
$this -> end();
?>