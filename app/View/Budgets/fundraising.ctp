<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

$this -> extend('/Common/budgets');
$this -> assign('title', 'Revenue Generated From Fundraising');
$this -> Html -> addCrumb($organization['Organization']['name'], '/organizations/view/' . $organization['Organization']['id']);
$this -> Html -> addCrumb('Fundraising', $this -> here);
$this -> start('middle');
echo $this -> Html -> script('budgets/fundraising');
echo $this -> Form -> create('Dues');
echo $this -> Html -> tableBegin(array('class' => 'listing'));

echo $this -> Html -> tableHeaders(array(
	array('Member Type' => array('width' => '120px')),
	'Number of Members',
	'Dues Amount',
	'Dues Income'
));
$types = array(
	'Year-round',
	'Fall',
	'Spring',
	'Summer'
);
if (!isset($dues) || count($dues) == 0)
{
	$element = array('Dues' => array(
			'id' => '',
			'members' => 0,
			'amount' => 0
		));
	$dues[] = $element;
	$dues[] = $element;
	$dues[] = $element;
	$dues[] = $element;
}
foreach ($types as $key => $type)
{
	echo $this -> Html -> tableCells(array(
		$this -> Form -> hidden("$key.Dues.id", array('value' => $dues[$key]['Dues']['id'])) . $this -> Form -> hidden("$key.Dues.budget_id", array('value' => $budget_id)) . $this -> Form -> input("$key.Dues.member_category", array(
			'label' => false,
			'value' => $type,
			'readonly',
			'style' => 'background-color:transparent;border-style:none;'
		)),
		$this -> Form -> input("$key.Dues.members", array(
			'value' => (isset($dues[$key]['Dues']['members'])) ? $dues[$key]['Dues']['members'] : 0,
			'label' => false,
			'onchange' => "updateIncome($key)"
		)),
		$this -> Form -> input("$key.Dues.amount", array(
			'value' => (isset($dues[$key]['Dues']['amount'])) ? $dues[$key]['Dues']['amount'] : 0,
			'label' => false,
			'onchange' => "updateIncome($key)"
		)),
		$this -> Form -> input("$key.Dues.income", array(
			'value' => '$0.00',
			'label' => false,
			'readonly',
			'style' => 'background-color:transparent;border-style:none;'
		))
	));
}
echo $this -> Html -> tableCells(array(
	'',
	'',
	array(
		'Total:',
		array('style' => 'text-align:right;')
	),
	array(
		'$0.00',
		array('id' => 'DuesTotal')
	)
));

echo $this -> Html -> tableEnd();

echo $this -> element('budgets/fundraising_multi_enter');
echo $this -> Form -> submit('Save', array('style' => 'float:left;'));
echo $this -> Form -> submit('Save and Continue', array('name' => "data[redirect]"));
?>
<script>
	correctNumbers("FundraiserTable0", 0);
	correctNumbers("FundraiserTable1", 1);
	correctNumbers("FundraiserTable2", 2);
	updateIncome(0);
	updateIncome(1);
	updateIncome(2);
	updateIncome(3); 
</script>
<?php
$this -> end();
?>
