<?php
/**
 * @author Stephen Roca
 * @since 06/30/2012
 */
 
$this -> extend("/Common/common");
$this -> assign("title", "Bill");
$this -> start('middle');
?>
<table>
	<?php
		echo $this -> Html -> tableCells(array('Title', $bill['Bill']['TITLE']));
	?>
</table>
<?php
$this -> end();
?>
