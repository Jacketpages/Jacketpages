<?php
if ($gt_member)
{
?>

	<?=$this->Html->link('<span>Bills</span>', array('controller' => 'bills', 'action' => 'index'), array('escape' => false))?>
	<ul class="menu">
		<li class="leaf first">
			<?=$this->Html->link('Submit Bill', array('controller' => 'bills', 'action' => 'add'))?>
		</li>
		<li class="leaf">
			<?=$this->Html->link('View My Bills', array('controller' => 'bills', 'action' => 'my_bills'))?>
		</li>
		<li class="leaf last">
			<?=$this->Html->link('View All Bills', array('controller' => 'bills', 'action' => 'index'))?>
		</li>
	</ul>
			
<?php
}
?>