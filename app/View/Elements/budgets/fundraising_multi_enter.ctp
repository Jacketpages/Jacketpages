<?php
/**
 * @author Stephen Roca
 * @since 9/9/2013
 */
echo $this -> Html -> script('budgets/fundraising_multi_enter');
?>
<div id="accordion">
<?php
$names = array('Executed in FY ' . ($fiscalYear - 1), "Expected in FY $fiscalYear", 'Planned for FY ' . ($fiscalYear + 1));
	foreach($names as $key => $name)
	{
		echo $this -> Html -> link($name, '#');
		$arr = explode(' ',trim($name));
		echo $this -> element('budgets/fundraising_multi', array('num' => $arr[0],'othernum' => $key, 'fundraisings' => $fundraisers[$arr[0]]));
	}
?>
</div>
<script>
	$(function() {
    $( "#accordion" ).accordion({
      heightStyle: "content"
    });
  }); 
</script>