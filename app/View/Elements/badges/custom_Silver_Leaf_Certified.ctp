<?php

// default view, but with a class
echo $this->Html->tag('span',
	$this->Html->image($badge['icon_path'], array(
		'alt' => $badge['name'],
		'title' => $badge['name'],
		'class' => 'silverleafbadge'
	)),
	array('class' => 'badgeIcon')
);

// load the css
echo $this->Html->css('colorbox');

// load the javascript
echo $this->Html->script('jquery.colorbox-min');
echo $this->Html->script('silverleaf/silverleaf');


?>