
<?=$this->Html->link('<span>Student Government</span>', array('controller' => 'sga_people', 'action' => 'index'), array('escape' => false))?>
<ul class="menu">
	<li class="leaf first">
		<?=$this->Html->link('View SGA Members', array('controller' => 'sga_people', 'action' => 'index'))?>
	</li>

<?php if ($sga_user){ ?>
	<li class="leaf">
		<?=$this->Html->link('View Budgets', array('controller' => 'budgets', 'action' => 'index'))?>
	</li>
	<li class="leaf last">
		<?=$this->Html->link('View Bills on Agenda', array('controller' => 'bills', 'action' => 'onAgenda'))?>
	</li>
<?php } ?>

</ul>