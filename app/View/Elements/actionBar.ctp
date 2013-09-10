<?php

echo $this->Html->div(null, null, array('id'=>'action-items-wrapper'));
echo $this->Html->div(null, null, array('id'=>'action-items'));

?>
<ul>
	<li class="first active">
		<?php echo $this->element('actionBar/submitBill'); ?>
	</li>
	<li class="active">
		<?php echo $this->element('actionBar/loginLogout'); ?>
	</li>
</ul>
<?php

echo $this->Html->useTag('tagend', 'div');
echo $this->Html->useTag('tagend', 'div');

?>