<?php
/**
 * @author Stephen Roca
 * @since 06/26/2012
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Travel Calculator');
$this -> start('middle');
echo $this -> Form -> create();
echo $this -> Form -> input('IBMR', array(
	'label' => 'IBMR (Mileage Rate)',
	'type' => 'text',
	'value' => '.555'
));
echo $this -> Form -> input('Students', array(
	'label' => 'Number of Students Traveling',
	'type' => 'text',
	'value' => '1'
));
echo $this -> Form -> input('Distance', array(
	'label' => 'Total Mileage',
	'type' => 'text',
	'value' => '300'
));
echo $this -> Form -> input('Funding', array(
	'label' => 'Maximum Funding',
	'type' => 'text',
	'readonly' => 'readonly',
	'class' => 'notice'
));
echo $this -> Form -> button('Calculate', array('type' => 'button'));
?>
<script type="text/javascript">
	$(document).ready(function() {
		calculate();
		$("input").change(calculate);
	});

	/**
	 * Calculates the maximum funding that can be received
	 * for a trip and displays the amount to the page.
	 */
	function calculate() {
		var IBMR = $("input#LineItemIBMR").val();
		var students = $("input#LineItemStudents").val();
		var miles = $("input#LineItemDistance").val();

		var maxMiles = 6000 * (Math.pow(students, -0.4));
		if (miles > maxMiles) {
			miles = maxMiles;
			$(".notice").html("Not all miles are being funded.");
			$(".notice").show();
		}

		var funding = .055 * students * IBMR * miles;
		var maxfunding = .055 * students * IBMR * maxMiles;

		$("input#LineItemFunding").val(Math.round(funding * 100) / 100);
	}
</script>
<?php
$this -> end();
?>