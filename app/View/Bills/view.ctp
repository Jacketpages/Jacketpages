<?php
/**
 * @author Stephen Roca
 * @since 06/30/2012
 */

$this -> extend("/Common/common");
$this -> start('sidebar');
echo $this -> Html -> nestedList(array(
	$this -> Html -> link('Add Line Item', array(
		'controller' => 'line_items',
		'action' => 'add',
		$bill['Bill']['ID']
	)),
	$this -> Html -> link(__('Update Bill', true), array(
		'action' => 'edit',
		$bill['Bill']['ID']
	))
), array(), array('id' => 'underline'));
$this -> end();
$this -> assign("title", "Bill");
$this -> start('middle');
// General bill information table
echo $this -> Html -> tableBegin(array('class' => 'list'));
echo $this -> Html -> tableCells(array(
	'Title',
	$bill['Bill']['TITLE']
));
echo $this -> Html -> tableCells(array(
	'Description',
	$bill['Bill']['DESCRIPTION']
));
echo $this -> Html -> tableCells(array(
	'Number',
	$bill['Bill']['NUMBER']
));
echo $this -> Html -> tableCells(array(
	'Submit Date',
	$bill['Bill']['SUBMIT_DATE']
));
echo $this -> Html -> tableEnd();
// Bill status table
echo $this -> Html -> tag('h1', 'Status');
echo $this -> Html -> tableBegin(array('class' => 'list'));
echo $this -> Html -> tableCells(array(
	'Type',
	$bill['Bill']['TYPE']
));
echo $this -> Html -> tableCells(array(
	'Category',
	$bill['Bill']['CATEGORY']
));
echo $this -> Html -> tableCells(array(
	'Status',
	$bill['Status']['NAME']
));
echo $this -> Html -> tableEnd();
//Bill author table
echo $this -> Html -> tag('h1', 'Authors');
echo $this -> Html -> tableBegin(array('class' => 'list'));
echo $this -> Html -> tableCells(array(
	'Graduate Author',
	'Need to transfer Bill authors still'
));
echo $this -> Html -> tableCells(array(
	'Submitter',
	$bill['Submitter']['NAME']
));
echo $this -> Html -> tableCells(array(
	'Organization',
	$bill['Status']['NAME']
));
echo $this -> Html -> tableEnd();

echo $this -> Html -> tableBegin(array(
	'class' => 'list',
	'width' => '50%'
));
echo $this -> Html -> tableHeaders(array('GSS Outcome:'));
echo $this -> Html -> tableCells(array(
	'd',
	'd'
));
echo $this -> Html -> tableEnd();
if ($submitted == null)
{
	$this -> end();
}
?>
<script>
	$(function() {
		$("#tabs").tabs();
	}); 
</script>
<div id="tabs">
	<ul>
		<li>
			<a href="#tabs-1">Submitted</a>
		</li>
		<li>
			<a href="#tabs-2">JFC</a>
		</li>
		<li>
			<a href="#tabs-3">Graduate</a>
		</li>
		<li>
			<a href="#tabs-4">UnderGraduate</a>
		</li>
		<li>
			<a href="#tabs-5">Conference</a>
		</li>
		<li>
			<a href="#tabs-6">All</a>
		</li>
		<li>
			<a href="#tabs-7">Final</a>
		</li>
	</ul>
	<?php
	echo $this -> Html -> tag('div', $this -> element('lineItemDetails', array(
		'lineitems' => $submitted,
		'showAll' => 0
	)), array('id' => 'tabs-1'));
	echo $this -> Html -> tag('div', $this -> element('lineItemDetails', array(
		'lineitems' => $jfc,
		'showAll' => 0
	)), array('id' => 'tabs-2'));
	echo $this -> Html -> tag('div', $this -> element('lineItemDetails', array(
		'lineitems' => $graduate,
		'showAll' => 0
	)), array('id' => 'tabs-3'));
	echo $this -> Html -> tag('div', $this -> element('lineItemDetails', array(
		'lineitems' => $undergraduate,
		'showAll' => 0
	)), array('id' => 'tabs-4'));
	echo $this -> Html -> tag('div', $this -> element('lineItemDetails', array(
		'lineitems' => $conference,
		'showAll' => 0
	)), array('id' => 'tabs-5'));
	echo $this -> Html -> tag('div', $this -> element('lineItemDetails', array(
		'lineitems' => $all,
		'showAll' => 1
	)), array('id' => 'tabs-6'));
	echo $this -> Html -> tag('div', $this -> element('lineItemDetails', array(
		'lineitems' => $final,
		'showAll' => 0
	)), array('id' => 'tabs-7'));
	?>
</div>
<?php
$this -> end();
?>
