<?php

$myAccount = array();
if($general && !$gt_member)
{
?>

	<?=$this->Html->link('<span>Login</span>', array('controller' => 'users', 'action' => 'login'), array('escape' => false))?>
	<ul class="menu">
		<li class="first last leaf">
			<?=$this->Html->link('Login', array('controller'=>'users', 'action'=>'login'))?>
		</li>
	</ul>

<?php
}

if($gt_member)
{
?>

	<?=$this->Html->link('<span>My Account</span>', array('controller' => 'users', 'action' => 'view', $this -> Session -> read('User.id')), array('escape' => false))?>
	<ul class="menu">
		<li class="leaf first">
			<?=$this->Html->link('Account Profile', array('controller' => 'users', 'action' => 'view', $this -> Session -> read('User.id')))?>
		</li>
		<li class="leaf">
			<?=$this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'))?>
		</li>
		<li class="leaf last">
			<?=$this->Html->link('JacketPages Home', '/')?>
		</li>
	</ul>
	
<?php
}
?>