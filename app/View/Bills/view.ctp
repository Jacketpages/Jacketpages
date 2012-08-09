<?php
/**
 * @author Stephen Roca
 * @since 06/30/2012
 */
 
$this -> extend("/Common/common");
$this -> start('sidebar');
echo $this -> Html -> nestedList(array(
   $this -> Html -> link(__('Update Bill', true), array(
      'action' => 'edit',
      $bill['Bill']['ID']
   )),
   $this -> Html -> link(__('Delete Bill', true), array(
      'action' => 'delete',
      $bill['Bill']['ID']
   ), null, sprintf(__('Are you sure you want to delete %s?', true), $bill['Bill']['TITLE']))
), array(), array('id' => 'underline'));
$this -> end();
$this -> assign("title", "Bill");
$this -> start('middle');
?>
<table class='list'>
	<?php
		echo $this -> Html -> tableCells(array('Title', $bill['Bill']['TITLE']));
		echo $this -> Html -> tableCells(array('Description', $bill['Bill']['DESCRIPTION']));
		echo $this -> Html -> tableCells(array('Number', $bill['Bill']['NUMBER']));
		echo $this -> Html -> tableCells(array('Submit Date', $bill['Bill']['SUBMIT_DATE']));
	?>
</table>
<?php
echo $this -> Html -> tag('h1', 'Status');
?>
<table class='list'>
<?php
echo $this -> Html -> tableCells(array('Type', $bill['Bill']['TYPE']));
echo $this -> Html -> tableCells(array('Category', $bill['Bill']['CATEGORY']));
echo $this -> Html -> tableCells(array('Status', $bill['Status']['NAME']));
?>
</table>
<?php
echo $this -> Html -> tag('h1', 'Authors');
?>
<table class='list'>
<?php
echo $this -> Html -> tableCells(array('Graduate Author', 'Need to transfer Bill authors still'));
echo $this -> Html -> tableCells(array('Submitter', $bill['Submitter']['NAME']));
echo $this -> Html -> tableCells(array('Organization', $bill['Status']['NAME']));
?>
</table>
<?php
$this -> end();
?>
