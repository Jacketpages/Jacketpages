<?php
echo $this -> Html -> tag('h1', 'Budgets Export');
echo $this -> Form -> create('Budget', array('action' => 'export'));
echo $this -> Html -> tableBegin(array('class' => 'listing'));

echo $this -> Html -> tableCells(array(
	'Fiscal Year',
	$this -> Form -> input('fiscal_year', array(
		'type' => 'select',
		'label' => false,
		'options' => $fiscal_years,
		'onchange' => 'updateOrganizationDropDown()'
	))
));

echo $this -> Html -> tableCells(array(
	'Tier',
	$this -> Form -> input('tier', array(
		'type' => 'select',
		'label' => false,
		'options' => array(
			'All',
			'I',
			'II',
			'III'
		),
		'onchange' => 'updateOrganizationDropDown()'
	))
));
echo $this -> Html -> tableCells(array(
	'Organization',
	$this -> Form -> input('org_id', array(
		'type' => 'select',
		'label' => false,
		'options' => $organizations
	))
));

echo $this -> Html -> tableEnd();
echo $this -> Form -> submit('Export');
?>

<script>
	function updateOrganizationDropDown()
	{
		var fiscalYear = $("#BudgetFiscalYear").val();
		var tier = $("#BudgetTier").val();
		var orgDropDown = $("#BudgetOrgId");
		orgDropDown.empty();
		$.ajax({
			url:'getOrgsForDropDown/' + fiscalYear + "/" + tier,
			success: function(result) {
				console.log( $.parseJSON(result));
				var options = $.parseJSON(result)["Options"];
				//options.sort(function(a,b) { return a[1] == b[1] } );
				orgDropDown.append($("<option></option>").attr("value", 0).text("All"));
				$.each(options, function(key, value){
					orgDropDown.append($("<option></option>").attr("value", value).text(key));
				});
			}
		});
	}
	
</script>