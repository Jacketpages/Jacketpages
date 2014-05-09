<?php

// make element view name
$elementName = 'badges/custom_'.Inflector::slug($badge['name']);

// display the appropriate view
// in the controller, make sure to call: $this->loadModel('Badge');
if($badge['view_style'] == Badge::VIEW_STYLE_CUSTOM && $this->elementExists($elementName)){
	// custom view
	echo $this->element($elementName, array('badge' => $badge));
	
} else {
	// default view
	echo $this->Html->tag('span',
		$this->Html->image($badge['icon_path'], array(
			'alt' => $badge['name'],
			'title' => $badge['name']
		)),
		array('class' => 'badgeIcon')
	);
}

?>
