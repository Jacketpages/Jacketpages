<?php
/**
 * @author Stephen Roca
 * @since 06/30/2012
 */

$this -> extend("/Common/common");
$this -> start('sidebar');
$updateBillAction = 'general_info';
if ($this -> Session -> read('Sga.id') != null)
{
	$updateBillAction = 'edit_index';
}
$sidebar = array();
//@formatter:off
if (($bill['Submitter']['id'] == $this -> Session -> read('User.id') && $bill['Bill']['status'] < 3) 
		|| $this -> Session -> read('Sga.id') != null)//@formatter:on
{
	$sidebar[] = $this -> Html -> link('Add Line Item', array(
		'controller' => 'line_items',
		'action' => 'add',
		$bill['Bill']['id']
	));
	$sidebar[] = $this -> Html -> link(__('Update Bill', true), array(
		'action' => $updateBillAction,
		$bill['Bill']['id']
	));
}
$sidebar[] = $this -> Html -> link('Delete Bill', array(
	'action' => 'delete',
	$bill['Bill']['id']
), array('style' => 'color:red'));
if ($bill['Bill']['status'] == 1)
{
	$sidebar[] = $this -> Html -> link(__('Submit Bill', true), array(
		'action' => 'submit',
		$bill['Bill']['id']
	));
}
if ($bill['Bill']['status'] == 3)
{
	$sidebar[] = $this -> Html -> link(__('Place on Agenda', true), array(
		'action' => 'putOnAgenda',
		$bill['Bill']['id']
	));
}
if ($sidebar != null)
{
	echo $this -> Html -> nestedList($sidebar, array(), array('id' => 'underline'));
}
$this -> end();
$this -> assign("title", "Bill");
$this -> start('middle');
// General bill information table
echo $this -> Html -> tableBegin(array('class' => 'list'));
echo $this -> Html -> tableCells(array(
	'Title',
	$bill['Bill']['title']
));
echo $this -> Html -> tableCells(array(
	'Description',
	$bill['Bill']['description']
));
echo $this -> Html -> tableCells(array(
	'Number',
	$bill['Bill']['number']
));
echo $this -> Html -> tableCells(array(
	'Submit Date',
	$bill['Bill']['submit_date']
));
echo $this -> Html -> tableEnd();
// Bill status table
echo $this -> Html -> tag('h1', 'Status');
echo $this -> Html -> tableBegin(array('class' => 'list'));
echo $this -> Html -> tableCells(array(
	'Type',
	$bill['Bill']['type']
));
echo $this -> Html -> tableCells(array(
	'Category',
	$bill['Bill']['category']
));
echo $this -> Html -> tableCells(array(
	'Status',
	$bill['Status']['name']
));
echo $this -> Html -> tableEnd();
//Bill author table
echo $this -> Html -> tag('h1', 'Authors');
echo $this -> Html -> tableBegin(array('class' => 'list'));
echo $this -> Html -> tableCells(array(
	'Graduate Author',
	$GradAuthor['User']['name']
));
echo $this -> Html -> tableCells(array(
	'Undergraduate Author',
	$UnderAuthor['User']['name']
));
echo $this -> Html -> tableCells(array(
	'Submitter',
	$bill['Submitter']['name']
));
echo $this -> Html -> tableCells(array(
	'Organization',
	$this -> Html -> link($bill['Organization']['name'], array(
		'controller' => 'organizations',
		'action' => 'view',
		$bill['Organization']['id']
	)),
));
echo $this -> Html -> tableEnd();

if ($bill['Bill']['type'] == 'Finance Request' && $bill['Bill']['status'] > 4)
{
	echo $this -> Html -> tableBegin(array(
		'class' => 'list',
		'width' => '50%'
	));
	echo $this -> Html -> tag('h1', 'Outcomes:');
	if ($bill['Bill']['category'] == 'Graduate' || $bill['Bill']['category'] == 'Joint')
	{
		$titles[] = 'GSS Outcome:';
		$titles[] = '';
		$dates[] = 'Date';
		$dates[] = $bill['GSS']['date'];
		$yeas[] = 'Yeas';
		$yeas[] = $bill['GSS']['yeas'];
		$nays[] = 'Nays';
		$nays[] = $bill['GSS']['nays'];
		$abstains[] = 'Abstains';
		$abstains[] = $bill['GSS']['abstains'];
	}

	if ($bill['Bill']['category'] == 'Undergraduate' || $bill['Bill']['category'] == 'Joint')
	{
		$titles[] = 'UHR Outcome:';
		$titles[] = '';
		$dates[] = 'Date';
		$dates[] = $bill['UHR']['date'];
		$yeas[] = 'Yeas';
		$yeas[] = $bill['UHR']['yeas'];
		$nays[] = 'Nays';
		$nays[] = $bill['UHR']['nays'];
		$abstains[] = 'Abstains';
		$abstains[] = $bill['UHR']['abstains'];
	}

	if ($bill['Bill']['category'] == 'Conference')
	{
		$ctitles[] = 'GSS Conference Outcome:';
		$ctitles[] = '';
		$ctitles[] = 'UHR Conference Outcome:';
		$ctitles[] = '';
		$cdates[] = 'Date';
		$cdates[] = $bill['GCC']['date'];
		$cdates[] = 'Date';
		$cdates[] = $bill['UCC']['date'];
		$cyeas[] = 'Yeas';
		$cyeas[] = $bill['GCC']['yeas'];
		$cyeas[] = 'Yeas';
		$cyeas[] = $bill['UCC']['yeas'];
		$cnays[] = 'Nays';
		$cnays[] = $bill['GCC']['nays'];
		$cnays[] = 'Nays';
		$cnays[] = $bill['UCC']['nays'];
		$cabstains[] = 'Abstains';
		$cabstains[] = $bill['GCC']['abstains'];
		$cabstains[] = 'Abstains';
		$cabstains[] = $bill['UCC']['abstains'];
	}
	echo $this -> Html -> tableHeaders($titles);
	echo $this -> Html -> tableCells($dates);
	echo $this -> Html -> tableCells($yeas);
	echo $this -> Html -> tableCells($nays);
	echo $this -> Html -> tableCells($abstains);
	echo $this -> Html -> tableEnd();
}
if ($submitted == null)
{
	$this -> end();
}
?>
<script>
	$(function() {
		$("#tabs").tabs();
		$("#tabs").tabs("option", "active", localStorage.selected);
		$("#tabs").tabs({
			activate : function(event, ui) {
				localStorage.selected = $("#tabs").tabs("option", "active");
			}
		});
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
		'showAll' => 0,
		'eligibleStates' => array('Submitted' => 'Submitted'),
		'form_state' => 'JFC'
	)), array('id' => 'tabs-2'));
	echo $this -> Html -> tag('div', $this -> element('lineItemDetails', array(
		'lineitems' => $graduate,
		'showAll' => 0,
		'eligibleStates' => array(
			'Submitted' => 'Submitted',
			'JFC' => 'JFC',
			'Undergraduate' => 'Undergraduate'
		),
		'form_state' => 'Graduate'
	)), array('id' => 'tabs-3'));
	echo $this -> Html -> tag('div', $this -> element('lineItemDetails', array(
		'lineitems' => $undergraduate,
		'showAll' => 0,
		'eligibleStates' => array(
			'Submitted' => 'Submitted',
			'JFC' => 'JFC',
			'Graduate' => 'Graduate'
		),
		'form_state' => 'Undergraduate'
	)), array('id' => 'tabs-4'));
	echo $this -> Html -> tag('div', $this -> element('lineItemDetails', array(
		'lineitems' => $conference,
		'showAll' => 0,
		'eligibleStates' => array(
			'Submitted' => 'Submitted',
			'JFC' => 'JFC',
			'Graduate' => 'Graduate',
			'Undergraduate' => 'Undergraduate'
		),
		'form_state' => 'Conference'
	)), array('id' => 'tabs-5'));
	echo $this -> Html -> tag('div', $this -> element('lineItemDetails', array(
		'lineitems' => $all,
		'showAll' => 1
	)), array('id' => 'tabs-6'));
	echo $this -> Html -> tag('div', $this -> element('lineItemDetails', array(
		'lineitems' => $final,
		'showAll' => 0,
		'eligibleStates' => array(
			'Submitted',
			'JFC',
			'Graduate',
			'Undergraduate',
			'Conference'
		),
		'form_state' => 'Final'
	)), array('id' => 'tabs-7'));
	?>
</div>
<?php
$this -> end();
?>
