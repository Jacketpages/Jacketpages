<?php
if ($gt_member)
{
?>

	<?php echo $this->Html->link('<span>Bills</span>', array('controller' => 'bills', 'action' => 'index'), array('escape' => false)); ?>
	<ul class="menu">
		<li class="leaf first">
			<?php echo $this->Html->link('Submit Bill', array('controller' => 'bills', 'action' => 'add')); ?>
		</li>
		<li class="leaf">
			<?php echo $this->Html->link('View My Bills', array('controller' => 'bills', 'action' => 'my_bills')); ?>
		</li>
		<li class="leaf last">
			<?php echo $this->Html->link('View All Bills', array('controller' => 'bills', 'action' => 'index')); ?>
		</li>
	</ul>
			
<?php
}
?>