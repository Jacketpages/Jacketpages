<?php

if ($admin)
{
?>

<?=$this->Html->link('<span>Administration</span>', '#', array('escape' => false))?>
<ul class="menu">
	<li class="leaf first">
		<?=$this->Html->link('Administer All Bills', array('controller' => 'bills', 'action' => 'index'))?>
	</li>
	<li class="leaf">
		<?=$this->Html->link('Administer SGA Members', array('controller' => 'sga_people', 'action' => 'index'))?>
	</li>
	<li class="leaf">
		<?=$this->Html->link('Administer Users', array('controller' => 'users', 'action' => 'index'))?>
	</li>
	<li class="leaf">
		<?=$this->Html->link('Administer Organizations', array('controller' => 'organizations', 'action' => 'index',))?>
	</li>
	<li class="leaf">
		<?=$this->Html->link('Login as Other User', array('controller' => 'users', 'action' => 'login'))?>
	</li>
	<li class="leaf">
		<?=$this->Html->link('Submit Bill as Other User', array('controller' => 'bills', 'action' => 'add'))?>
	</li>
	<li class="leaf last">
		<?=$this->Html->link('Post Message', '/Messages/message')?>
	</li>
</ul>	
	
<?php
}
?>