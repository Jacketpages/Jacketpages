<?php

if ($admin)
{
?>

<?php echo $this->Html->link('<span>Administration</span>', '#', array('escape' => false))
?>
<ul class="menu">
	<li class="leaf first">
		<?php echo $this->Html->link('Administer All Bills', array('controller' => 'bills', 'action' => 'index'))
		?>
	</li>
	<li class="leaf">
		<?php echo $this->Html->link('Administer SGA Members', array('controller' => 'sga_people', 'action' => 'index'))
		?>
	</li>
	<li class="leaf">
		<?php echo $this->Html->link('Administer Users', array('controller' => 'users', 'action' => 'index'))
		?>
	</li>
	<li class="leaf">
		<?php echo $this->Html->link('Administer Organizations', array('controller' => 'organizations', 'action' => 'index',))
		?>
	</li>
	<li class="leaf">
		<?php echo $this->Html->link('Login as Other User', array('controller' => 'users', 'action' => 'loginasotheruser'))
		?>
	</li>
	<li class="leaf last">
		<?php echo $this->Html->link('Submit Bill as Other User', array('controller' => 'bills', 'action' => 'add'))
		?>
	</li>
</ul>

<?php
}
?>