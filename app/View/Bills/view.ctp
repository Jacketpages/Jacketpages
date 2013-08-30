<?php
/**
 * @author Stephen Roca
 * @since 06/30/2012
 */
$this -> extend("/Common/common");
$this -> start('sidebar');
echo $this -> element('bills/sidebar_links');
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

if ($bill['Bill']['status'] == $CREATED)
{
	echo $this -> Form -> postButton('Submit Bill', array(
		'controller' => 'bills',
		'action' => 'submit',
		$bill['Bill']['id']
	));
}

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
			<a href="#tabs-4">Undergraduate</a>
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
