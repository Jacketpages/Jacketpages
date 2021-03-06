<?php
/**
 * @author Stephen Roca
 * @since 7/29/2013
 */

$grad = array('Graduate Author');
$undr = array('Undergraduate Author');

if (!$bill['Authors']['grad_auth_appr'])
{
	$signed = 'Not Signed';
}
else
{
	$signed = 'Signed';
}
$name = (isset($GradAuthor['User']['name'])) ? $GradAuthor['User']['name'] : '';
$email = (isset($GradAuthor['User']['email'])) ? $GradAuthor['User']['email'] : '';
$grad[] = $this -> Html -> link ($name, 'mailto:' . $email) . " - " . $signed;

if (!$bill['Authors']['undr_auth_appr'])
{
	$signed = 'Not Signed';
}
else
{
	$signed = 'Signed';
}
$name = (isset($UnderAuthor['User']['name'])) ? $UnderAuthor['User']['name'] : '';
$email = (isset($UnderAuthor['User']['email'])) ? $UnderAuthor['User']['email'] : '';
$undr[] = $this -> Html -> link ($name, 'mailto:' . $email) . " - " . $signed;

if ($bill['Bill']['status'] < $AGENDA)
{
	if ($this -> Session -> read('Sga.id') == $bill['Authors']['grad_auth_id']  && ($bill['Bill']['category'] == 'Joint' || $bill['Bill']['category'] == 'Graduate'))
	{
		if (!$bill['Authors']['grad_auth_appr'])
		{
			$grad[1] = $grad[1] . " - " . $this -> Html -> link("Sign", array(
				'controller' => 'bills',
				'action' => 'authorSign',
				$bill['Bill']['id'],
				'grad_auth_appr',
				1
			));
		}
		else
		{
			$grad[1] = $grad[1] . " - " . $this -> Html -> link("Remove Signature", array(
				'controller' => 'bills',
				'action' => 'authorSign',
				$bill['Bill']['id'],
				'grad_auth_appr',
				0
			));
		}
	}
	if ($this -> Session -> read('Sga.id') == $bill['Authors']['undr_auth_id'] && ($bill['Bill']['category'] == 'Joint' || $bill['Bill']['category'] == 'Undergraduate'))
	{
		if (!$bill['Authors']['undr_auth_appr'])
		{
			$undr[1] = $undr[1] . " - " . $this -> Html -> link("Sign", array(
				'controller' => 'bills',
				'action' => 'authorSign',
				$bill['Bill']['id'],
				'undr_auth_appr',
				1
			));
		}
		else
		{
			$undr[1] = $undr[1] . " - " . $this -> Html -> link("Remove Signature", array(
				'controller' => 'bills',
				'action' => 'authorSign',
				$bill['Bill']['id'],
				'undr_auth_appr',
				0
			));
		}
	}
}

$rows = array();
if($bill['Bill']['category'] == 'Joint' || $bill['Bill']['category'] == 'Graduate' || $bill['Bill']['category'] == 'Conference')
	$rows[] = $grad;
if($bill['Bill']['category'] == 'Joint' || $bill['Bill']['category'] == 'Undergraduate' || $bill['Bill']['category'] == 'Conference')
	$rows[] = $undr;
$rows[] = array(
		'Submitter',
		$this -> Html -> link($bill['Submitter']['name'], 'mailto:' . $bill['Submitter']['email']),
		""
);
$rows[] = array(
		'Organization',
		$this -> Html -> link($bill['Organization']['name'], array(
			'controller' => 'organizations',
			'action' => 'view',
			$bill['Organization']['id']
		)),
		""
);

echo $this -> Html -> tag('h1', 'Authors');
echo $this -> Html -> tableBegin(array('class' => 'list'));
foreach ($rows as $row)
{
	echo $this -> Html -> tableCells($row);
}
echo $this -> Html -> tableEnd();
?>