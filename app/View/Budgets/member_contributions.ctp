<?php
/**
 * @author Stephen Roca
 * @since 9/10/2013
 */

$this -> extend('/Common/budgets');
$this -> assign('title', 'Member Contributions');
$this -> Html -> addCrumb('Member Contributions', $this->here);
$this -> start('script');
echo $this -> Html -> script('budgets/member_contributions');
$this -> end();
$this -> start('middle');
echo $this -> Html -> para('','In this section, list all personal items such as swimsuit or shoes, 
which each member must purchase to be involved in the organization\'s activities. 
Note: these items must be in addition to the amount paid to the club in dues or donations.');
echo $this -> Form -> create('MemberContribution');
echo $this -> Html -> tableBegin(array(
	'class' => 'listing',
	'id' => 'MemberContributionsTable'
));
echo $this -> Html -> tableHeaders(array(
	array('Item' => array('width' => '500px')),
	'Amount',
	'',
	'',
	'',
	''
));
if (count($memberContributions) == 0)
{
	$memberContributions = array( array('MemberContribution' => array(
				'id' => '','item' => '',
				'amount' => ''
			)));
}
foreach ($memberContributions as $key => $memberContribution)
{
	echo $this -> Html -> tableCells(array(
		$this -> Form -> input("$key.MemberContribution.id", array(
			'id' => 'MemberContributionId' . $key,
			'type' => 'hidden',
			'value' => $memberContribution['MemberContribution']['id']
		))  . $this -> Form -> input("$key.MemberContribution.item", array(
			'label' => false,
			'id' => 'Item' . $key,
			'value' => $memberContribution['MemberContribution']['item']
		)),
		$this -> Form -> input("$key.MemberContribution.amount", array(
			'label' => false,
			'id' => 'Amount' . $key,
			'value' => $memberContribution['MemberContribution']['amount'],
			'onchange' => 'updateTotal()'
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
	));
}
echo $this -> Html -> tableCells(array(array(
	array('Total', array('align'=>'right')),
	array('$0.00', array('id' => 'total')),
	'',
	'',
	'',
	''
)), array(), array('id' => 'TotalRow'));
echo $this -> Html -> tableEnd();
echo $this -> Form -> submit('Save', array('style' => 'float:left;'));
echo $this -> Form -> submit('Save and Continue');
echo "<script>updateTotal();</script>";
$this -> end();
?>
