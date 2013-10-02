<?php
/**
 * @author Stephen Roca
 * @since 12/8/2012
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Update Details');
$this -> Html -> addCrumb('All Bills', '/bills');
$this -> Html -> addCrumb('Update Signature', $this->here);
$this -> start('sidebar');
echo $this -> Html -> nestedList(array($this -> Html -> link('View All Bills', array('action' => 'index'))), array());
$this -> end();
$this -> start('middle');
echo $this -> Form -> create('BillAuthor');
echo $this -> Form -> input('id', array('type' => 'hidden'));
//echo $this -> Form -> input('Bill.auth_id', array('type' => 'hidden'));
//echo $this -> Form -> input('Authors.ID', array('type' => 'hidden'));
if ($authors['BillAuthor']['undr_auth_id'] == $this -> Session -> read('Sga.id'))
{
	echo $this -> Form -> input('undr_auth_appr', array(
		'options' => array(
			0 => 'Not Approved',
			1 => 'Approved'
		),
		'label' => 'Undergraduate Approval - ' . $UnderAuthor['User']['name']
	));
}

if ($authors['BillAuthor']['grad_auth_id'] == $this -> Session -> read('Sga.id'))
{
	echo $this -> Form -> input('grad_auth_appr', array(
		'options' => array(
			0 => 'Not Approved',
			1 => 'Approved'
		),
		'label' => 'Graduate Approval - ' . $GradAuthor['User']['name']
	));
}
echo $this -> Form -> input('grad_pres_id', array(
	'options' => array(
		$authors['BillAuthor']['grad_pres_id'] => $authors['BillAuthor']['grad_pres_id'],
		0 => 'Not Signed',
		$this -> Session -> read('Sga.id') => 'Signed'
	),
	'label' => 'Graduate President\'s Signature'
));
echo $this -> Form -> input('grad_secr_id', array(
	'options' => array(
		0 => 'Not Signed',
		$this -> Session -> read('Sga.id') => 'Signed'
	),
	'label' => 'Graduate Secretary\'s Signature'
));
echo $this -> Form -> input('undr_pres_id', array(
	'options' => array(
		0 => 'Not Signed',
		$this -> Session -> read('Sga.id') => 'Signed'
	),
	'label' => 'Undergraduate President\' Signature'
));
echo $this -> Form -> input('undr_secr_id', array(
	'options' => array(
		0 => 'Not Signed',
		$this -> Session -> read('Sga.id') => 'Signed'
	),
	'label' => 'Undergraduate Secretary\'s Signature'
));
echo $this -> Form -> input('vp_fina_id', array(
	'options' => array(
		0 => 'Not Signed',
		$this -> Session -> read('Sga.id') => 'Signed'
	),
	'label' => 'Vice President of Finance\'s Signature'
));
echo $this -> Form -> end('Submit');
$this -> end();
?>