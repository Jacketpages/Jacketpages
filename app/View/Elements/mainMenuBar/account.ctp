<?php

$myAccount = array();
if($general && !$gt_member)
{
?>

	<?php echo $this->Html->link('<span>Login</span>', array('controller' => 'users', 'action' => 'login'), array('escape' => false)); ?>
	<ul class="menu">
		<li class="first last leaf">
			<?php echo $this->Html->link('Login', array('controller'=>'users', 'action'=>'login')); ?>
		</li>
	</ul>

<?php
}

if($gt_member)
{
?>

	<?php echo $this->Html->link('<span>My Account</span>', array('controller' => 'users', 'action' => 'view', $this -> Session -> read('User.id')), array('escape' => false)); ?>
	<ul class="menu">
		<li class="leaf first">
			<?php echo $this->Html->link('Account Profile', array('controller' => 'users', 'action' => 'view', $this -> Session -> read('User.id'))); ?>
		</li>
		<li class="leaf">
			<?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?>
		</li>
		<li class="leaf last">
			<?php echo $this->Html->link('JacketPages Home', '/'); ?>
		</li>
	</ul>
	
<?php
}
?>