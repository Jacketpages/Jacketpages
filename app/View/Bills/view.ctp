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
if ($bill['Bill']['status'] == 1)
{
	$sidebar[] = $this -> Html -> link('Add Line Items', array(
		'controller' => 'line_items',
		'action' => 'index',
		$bill['Bill']['id'],
		'Submitted'
	));
	$sidebar[] = $this -> Html -> link(__('Submit Bill', true), array(
		'action' => 'submit',
		$bill['Bill']['id']
	));
}
else if ($bill['Bill']['status'] == 3)
{
	$sidebar[] = $this -> Html -> link(__('Place on Agenda', true), array(
		'action' => 'putOnAgenda',
		$bill['Bill']['id']
	));
}
else if ($bill['Bill']['status'] == 4)
{
	$sidebar[] = $this -> Html -> link(__('GSS Votes', true), array(
		'action' => 'votes',
		$bill['Bill']['id'],
		'gss_id',
		$bill['GSS']['id']
	));
	$sidebar[] = $this -> Html -> link(__('UHR Votes', true), array(
		'action' => 'votes',
		$bill['Bill']['id'],
		'uhr_id',
		$bill['UHR']['id']
	));
}
if ($bill['Bill']['status'] == 7)
{
	$sidebar[] = $this -> Html -> link(__('Conference GSS Votes', true), array(
		'action' => 'votes',
		$bill['Bill']['id'],
		'gcc_id',
		$bill['GCC']['id']
	));
	$sidebar[] = $this -> Html -> link(__('Conference UHR Votes', true), array(
		'action' => 'votes',
		$bill['Bill']['id'],
		'ucc_id',
		$bill['UCC']['id']
	));
}
//@formatter:off
if (($bill['Submitter']['id'] == $this -> Session -> read('User.id') && $bill['Bill']['status'] < 3) 
		|| $this -> Session -> read('Sga.id') != null)//@formatter:on
{
	$sidebar[] = $this -> Html -> link(__('Update Bill', true), array(
		'action' => "general_info",
		$bill['Bill']['id']
	));
}
$sidebar[] = $this -> Html -> link('Delete Bill', array(
	'action' => 'delete',
	$bill['Bill']['id']
), array('style' => 'color:red'));

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
echo $this -> element('bills/view/status');
echo $this -> element('bills/view/authors');
echo $this -> element('bills/view/signatures');
echo $this -> element('bills/view/outcomes');

if ($submitted == null)
{
	$this -> end();
}
?>
<script>
	$(function()
	{
		$("#tabs").tabs();
		$("#tabs").tabs("option", "active", localStorage.selected);
		$("#tabs").tabs(
		{
			activate : function(event, ui)
			{
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
		'showAll' => 0,
		'first' => 1,
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
			'Submitted' => 'Submitted',
			'JFC' => 'JFC',
			'Graduate' => 'Graduate',
			'Undergraduate' => 'Undergraduate',
			'Conference' => 'Conference'
		),
		'form_state' => 'Final'
	)), array('id' => 'tabs-7'));
	?>
</div>
<?php
$this -> end();
?>
