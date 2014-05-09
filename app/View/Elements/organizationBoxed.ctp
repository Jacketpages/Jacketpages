<div id="listing">
<?php

foreach($organizations as $organization)
{
	$logo = $this->Html->image($organization['Organization']['logo_path']);
	$description = $this->Text->truncate($organization['Organization']['description'], 300, array('ellipsis' => '...', 'exact' => true));
	
	echo $this->Html->div('box_listing',
		$this->Html->div('box_img', '<span class=\'center_helper\'></span>'.$logo).
		$this ->Html->link('<b>'.$organization['Organization']['name'].'</b>', array(
			'action' => 'view',
			$organization['Organization']['id']
		), array('escape' => false)).
		'<br />'.$description
	);
}

?>
</div>