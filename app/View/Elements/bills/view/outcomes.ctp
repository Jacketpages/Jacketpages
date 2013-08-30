<?php
/**
 * @author Stephen Roca
 * @since 8/7/2013
 */

if (($bill['Bill']['type'] == 'Finance Request' && $bill['Bill']['status'] > $AGENDA) || ($bill['Bill']['status'] == $AGENDA && $sga_user))
{
	if ($bill['Bill']['category'] == 'Graduate' || $bill['Bill']['category'] == 'Joint')
	{
		$titles[] = $this -> Html -> link('GSS Outcome:',array(), array('title'=>$bill['GSS']['comments']));
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
		$titles[] = $this -> Html -> link('UHR Outcome:',array(), array('title'=>$bill['UHR']['comments']));
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

	echo $this -> Html -> tag('h1', 'Outcomes:');
	echo $this -> Html -> tableBegin(array(
		'class' => 'list',
		'id' => 'outcomes',
		'width' => '50%'
	));
	echo $this -> Html -> tableHeaders($titles);
	echo $this -> Html -> tableCells($dates);
	echo $this -> Html -> tableCells($yeas);
	echo $this -> Html -> tableCells($nays);
	echo $this -> Html -> tableCells($abstains);
	echo $this -> Html -> tableEnd();

	if (($bill['Bill']['status'] == $CONFERENCE && $sga_user) || 
	($bill['Bill']['type'] == 'Finance Request' && $bill['Bill']['status'] > $CONFERENCE && $bill['GCC']['id'] != null))
	{
		$ctitles[] = $this -> Html -> link('GSS Conference Outcome:',array(), array('title'=>$bill['GCC']['comments']));
		$ctitles[] = '';
		$ctitles[] = $this -> Html -> link('UHR Conference Outcome:', array(), array('title'=>$bill['UCC']['comments']));
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
		echo $this -> Html -> tag('h1', 'Conference Outcomes:');
		echo $this -> Html -> tableBegin(array(
			'class' => 'list',
			'id' => 'outcomes',
			'width' => '50%'
		));
		echo $this -> Html -> tableHeaders($ctitles);
		echo $this -> Html -> tableCells($cdates);
		echo $this -> Html -> tableCells($cyeas);
		echo $this -> Html -> tableCells($cnays);
		echo $this -> Html -> tableCells($cabstains);
		echo $this -> Html -> tableEnd();
	}
}
?>
