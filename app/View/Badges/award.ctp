<?php

$this -> extend('/Common/list');

$this -> assign('title', 'Badge Awards for '.$badge['Badge']['name']);

$this -> Html -> addCrumb('Badges', '/badges');
$this -> Html -> addCrumb('Award', '/badges/award/'.$badge['Badge']['id']);

$this -> start('sidebar');
$sidebar = array();
$sidebar[] = $this -> Html -> link('View All Badges', array('action' => '/'));
$sidebar[] = $this -> Html -> link('Award A Badge', array('action' => 'award'));
$sidebar[] = $this -> Html -> link('Edit This Badge', array('action' => 'edit', $badge['Badge']['id']));
$sidebar[] = $this -> Html -> link('Add New Badge', array('action' => 'add'));
echo $this -> Html -> nestedList($sidebar);
$this -> end();


// search bar
$this -> start('search');
?>
<div id="awardSearchBox">
	<?php
	echo $this->Form->input('awardsSearch', array(
		'label' => array(
			'text' => 'Search',
			'style' => 'display:inline'
		),
		'id' => 'awardsSearch',
		'style' => 'width:90%'
	));
	?>
	<div id="div_choices" class="autocomplete"></div>
</div>
<?php
$this -> end();


// create the table content
$leftMultiselect = $this->Form->input('awarded', array(
	'options'  => $orgs_awarded,
	'label'    => 'Organizations Awarded',
	'type'     => 'select',
	'multiple' => 'true',
	'size'     => 15
));

$rightMultiselect = $this->Form->input('unawarded', array(
	'options'  => $orgs_unawarded,
	'label'    => 'Organizations Not Awarded',
	'type'     => 'select',
	'multiple' => 'true',
	'size'     => 15
));

$btnOpts = array('type'=>'button');
$centerButtons = $this->Html->div('awardsCenterButtons', 
	'Selected<br />'.
	$this->Form->button('&lt;', $btnOpts).'&nbsp'.$this->Form->button('&gt;', $btnOpts).
	'<br />'.'<br />'.
	'All<br />'.
	$this->Form->button('&lt;&lt;', $btnOpts).$this->Form->button('&gt;&gt;', $btnOpts)
);

// middle content
$this -> start('listing');

echo $this->Form->create('Badge');

echo $this->Html->tableBegin(array(
	'class' => 'awardsTable'
));
echo $this->Html->tableCells(array(
	array(
		array($leftMultiselect, array('width' => '45%')),
		array($centerButtons, array('width' => '10%')),
		array($rightMultiselect, array('width' => '45%')),
	)
));
echo $this->Html->tableEnd();
echo $this->Form->submit('Save');

echo $this->Form->end();


?>
<script>
$(function(){
	$('.awardsCenterButtons button').click(function(){

		var btnTxt = $(this).text();
		
		switch(btnTxt){
		case '<':
			// selected left
			$('#unawarded option:selected:visible').remove().appendTo('#awarded');
			break;
		case '>':
			// selected right
			$('#awarded option:selected:visible').remove().appendTo('#unawarded');
			break;
		case '<<':
			// all left
			$('#unawarded option:visible').remove().appendTo('#awarded');
			break;
		case '>>':
			// all right
			$('#awarded option:visible').remove().appendTo('#unawarded');
			break;	
		}
		
		// keep sorted
		var sortedOptions = $("#awarded option").sort(sortOptionsFn);
		$("#awarded").empty().append(sortedOptions);

		var sortedOptions = $("#unawarded option").sort(sortOptionsFn);
		$("#unawarded").empty().append(sortedOptions);
	});
	
	$('#awardsSearch').on('input', function(e){
		var keyword = $(this).val();
		
		// highlight the searchbox if in use
		if(keyword.length == 0){
			$('#awardsSearch').css('border-color', '');
		} else {
			$('#awardsSearch').css('border-color', '#EEB211');
		}
		
		// hide anything that doesn't match
		$('.awardsTable option').each(function(){
			// case insensitive compare
			var contains = ($(this).text().toUpperCase().indexOf(keyword.toUpperCase()) >= 0);
			if(contains){
				$(this).show();
			} else {
				$(this).hide();
			}
		});
	});
	
	// onsubmit make sure all the awarded options are selected
	$('#BadgeAwardForm').submit(function(){
		$('#awarded option').prop('selected', true);
	});
	
	// sort function
	var sortOptionsFn = function(a, b) {
	    return a.text.localeCompare(b.text);
	}
});
</script>
<?php

$this -> end();

?>
