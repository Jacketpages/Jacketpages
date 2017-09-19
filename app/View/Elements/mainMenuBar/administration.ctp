<?php

if ($lace || $sga_exec)
{
?>

    <?php echo $this->Html->link('<span>Admin</span>', '#', array('escape' => false))
?>
<ul class="menu">
    <!--<li class="leaf">
		<?php /*echo $this->Html->link('Manage Badges', array('controller' => 'badges', 'action' => 'index'))
		*/
    ?>
	</li>-->
	<?php
	if ($sga_exec) {
	?>
	<li class="leaf">
		<?php echo $this->Html->link('Administer SGA Members', array('controller' => 'sga_people', 'action' => 'index'))
		?>
	</li>
	<?php
	}
	if ($lace) {
	?>
	<li class="leaf">
		<?php echo $this->Html->link('Administer Users', array('controller' => 'users', 'action' => 'index'))
		?>
	</li>
	<li class="leaf">
		<?php echo $this->Html->link('Administer Organizations', array('controller' => 'organizations', 'action' => 'index',))
		?>
	</li>
	<?php
	}
	if ($admin) {
	?>
	<li class="leaf">
		<?php echo $this->Html->link('Login as Other User', array('controller' => 'users', 'action' => 'loginAsOtherUser'))
		?>
	</li>
	<li class="leaf">
		<?php echo $this->Html->link('Submit Bill as Other User', array('controller' => 'bills', 'action' => 'add'))
		?>
	</li>
	<li class="leaf">
		<?php echo $this->Html->link('Clear Cache', array('controller' => 'pages', 'action' => 'clearcache'))
		?>
	</li>
	<?php
	}
	?>
</ul>

<?php
}
?>