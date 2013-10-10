<?php
/**
 * @author Stephen Roca
 * @since 8/7/2013
 */

 //MRE no clue why outcomes WERE only being shown for finance requests...
 //Also, may have to change != null checks to isset
if ($bill['Bill']['status'] >= $AGENDA)
{
	if ($bill['Bill']['category'] == 'Graduate'
		|| $bill['Bill']['category'] == 'Joint'
		&& $bill['GSS']['id'] != null)
	{
		$titles[] = $this -> Html -> link('GSS:','#',array('onclick'=> 'javascript:openComments()'));
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

	if ($bill['Bill']['category'] == 'Undergraduate'
		|| $bill['Bill']['category'] == 'Joint'
		&& $bill['UHR']['id'] != null)
	{
		$titles[] = $this -> Html -> link('UHR:','#',array('onclick'=> 'javascript:openComments()'));
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
	
	if($bill['GSS']['id'] != null || $bill['UHR']['id'] != null)
	{
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
	}
	
	if ($bill['Bill']['category'] == 'Joint'
		&& $bill['GCC']['id'] != null)
	{
		$ctitles[] = $this -> Html -> link('GSS Conference:','#',array('onclick'=> 'javascript:openComments()'));
		$ctitles[] = '';
		$cdates[] = 'Date';
		$cdates[] = $bill['GCC']['date'];
		$cyeas[] = 'Yeas';
		$cyeas[] = $bill['GCC']['yeas'];
		$cnays[] = 'Nays';
		$cnays[] = $bill['GCC']['nays'];
		$cabstains[] = 'Abstains';
		$cabstains[] = $bill['GCC']['abstains'];
	}
	if ($bill['Bill']['category'] == 'Joint'
		&& $bill['UCC']['id'] != null)
	{
		$ctitles[] = $this -> Html -> link('UHR Conference:','#',array('onclick'=> 'javascript:openComments()'));
		$ctitles[] = '';
		$cdates[] = 'Date';
		$cdates[] = $bill['UCC']['date'];
		$cyeas[] = 'Yeas';
		$cyeas[] = $bill['UCC']['yeas'];
		$cnays[] = 'Nays';
		$cnays[] = $bill['UCC']['nays'];	
		$cabstains[] = 'Abstains';
		$cabstains[] = $bill['UCC']['abstains'];
	}	
	
	if($bill['GCC']['id'] != null || $bill['UCC']['id'] != null)
	{	
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
<script>
function openComments() {
	$("#comments").attr("style", "");
	$("#comments_text").update(text);
}

function closeComments() {
	$("#comments").attr("style", "display:none;");
}
</script>			