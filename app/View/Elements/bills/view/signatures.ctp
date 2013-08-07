<?php
/**
 * @author Stephen Roca
 * @since 7/22/2013
 */
if ($bill['Bill']['status'] >= 4)
{
	$signatures = array(
		'grad_pres_id',
		'grad_secr_id',
		'undr_pres_id',
		'undr_secr_id',
		'vp_fina_id'
	);
	$sign_labels = array(
		'Graduate President',
		'Graduate Secretary',
		'Undergraduate President',
		'Undergraduate Secretary',
		'Vice President of Finance'
	);

	echo $this -> Html -> tag('h1', 'Signatures');
	echo $this -> Html -> tableBegin(array('class' => 'list'));
	for ($i = 0; $i < count($signatures); $i++)
	{
		$tableCells = array();
		$tableCells[] = $sign_labels[$i];
		if ($bill['Bill']['status'] == 4)
		{
			if ($bill['Authors'][$signatures[$i]] == 0)
			{
				$tableCells[] = $this -> Html -> link("Sign", array(
					'controller' => 'bills',
					'action' => 'sign',
					$bill['Bill']['id'],
					$signatures[$i],
					$this -> Session -> read('Sga.id')
				));
			}
			else
			{
				$tableCells[] = $signee_names[str_replace("_id", "", $signatures[$i])];
				$tableCells[] = $this -> Html -> link("Remove Signature", array(
					'controller' => 'bills',
					'action' => 'sign',
					$bill['Bill']['id'],
					$signatures[$i],
					0
				));
			}
		}
		else
		{
			$tableCells[] = $signee_names[str_replace("_id", "", $signatures[$i])];
		}
		echo $this -> Html -> tableCells($tableCells);
	}
	echo $this -> Html -> tableEnd();
}
?>
