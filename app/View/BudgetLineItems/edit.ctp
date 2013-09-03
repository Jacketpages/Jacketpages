<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */
 
$this -> extend('/Common/common');
$this -> assign('title',"FY $fiscalYear Budget Line Items");
$this -> start('middle');
echo $this -> Html -> script('budgetlineitems/multi_enter');
?>
<div id="accordion">
<?php
for ($i = 0; $i < count($category_names); $i++)
{
	echo $this -> Html -> link($category_names[$i] . " " . $category_descriptions[$i], '#');
	echo $this -> element('budgetLineItems/multi_enter', array('num' => $i));
}
?>
</div>
<script>
  $(function() {
    $( "#accordion" ).accordion();
  });
  </script>
<?php
$this -> end();
?>
