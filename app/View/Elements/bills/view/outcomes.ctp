<?php
/**
 * @author Stephen Roca
 * @since 8/7/2013
 */

if (($bill['Bill']['type'] == 'Finance Request' && $bill['Bill']['status'] > 4) || $this -> Session -> read('Sga.id') != null)
{
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

	if ($bill['Bill']['status'] == 7 || $bill['GCC']['id'] != null)
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
