<?php
/**
 * @author Stephen Roca
 * @since 7/29/2013
 */
$rows = array(
	array(
		'Graduate Author',
		$GradAuthor['User']['name']
	),
	array(
		'Undergraduate Author',
		$UnderAuthor['User']['name']
	),
	array(
		'Submitter',
		$bill['Submitter']['name'],
		""
	),
	array(
		'Organization',
		$this -> Html -> link($bill['Organization']['name'], array(
			'controller' => 'organizations',
			'action' => 'view',
			$bill['Organization']['id']
		)),
		""
	)
);

if ($this -> Session -> read('Sga.id') == $bill['Authors']['grad_auth_id'])
{
	if (!$bill['Authors']['grad_auth_appr'])
	{
		$rows[0][] = $this -> Html -> link("Sign", array(
			'controller' => 'bills',
			'action' => 'authorSign',
			$bill['Bill']['id'],
			'grad_auth_appr',
			1
		));
	}
	else
	{
		$rows[0][] = $this -> Html -> link("Remove Signature", array(
			'controller' => 'bills',
			'action' => 'authorSign',
			$bill['Bill']['id'],
			'grad_auth_appr',
			0
		));
	}
}
if ($this -> Session -> read('Sga.id') == $bill['Authors']['undr_auth_id'])
{
	if (!$bill['Authors']['undr_auth_appr'])
	{
		$rows[1][] = $this -> Html -> link("Sign", array(
			'controller' => 'bills',
			'action' => 'authorSign',
			$bill['Bill']['id'],
			'undr_auth_appr',
			1
		));
	}
	else
	{
		$rows[1][] = $this -> Html -> link("Remove Signature", array(
			'controller' => 'bills',
			'action' => 'authorSign',
			$bill['Bill']['id'],
			'undr_auth_appr',
			0
		));
	}
}
echo $this -> Html -> tag('h1', 'Authors');
echo $this -> Html -> tableBegin(array('class' => 'list'));
foreach ($rows as $row)
{
	echo $this -> Html -> tableCells($row);
}
echo $this -> Html -> tableEnd();
?>