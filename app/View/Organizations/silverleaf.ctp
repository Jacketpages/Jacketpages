<?php

// layout
$this -> extend('/Common/list');

// breadcrumb
echo $this -> Html -> addCrumb('All Organizations', '/organizations');
echo $this -> Html -> addCrumb('Silver Leaf Certified Organizations', '/organizations/silverleaf');

// title
$this -> assign('title', 'Silver Leaf Certified Organizations');

// paginator
$this -> Paginator -> options(array(
	'update' => '#forupdate',
	'indicator' => '#indicator',
	'evalScripts' => true,
	'before' => $this -> Js -> get('#listing') -> effect('fadeOut', array('buffer' => false)),
	'complete' => $this -> Js -> get('#listing') -> effect('fadeIn', array('buffer' => false)),
));
?>



<?php
$this -> start('listing');

echo $this->Html->div('silver_leaf_cover', $this->Html->image('silverleafcover.png'));
?>

<div id='forupdate'>
	<?php
	echo $this -> element('organizationBoxed', array('organizations' => $organizations));
	echo $this -> element('paging');
	?>
</div>

<?php
$this -> end();
?>
