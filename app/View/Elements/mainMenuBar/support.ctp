<?php

$myAccount = array();
/*if($general && !$gt_member)
{
*/ ?><!--

	<?php /*echo $this->Html->link('<span>Login</span>', array('controller' => 'users', 'action' => 'login'), array('escape' => false))*/ ?>
	<ul class="menu">
		<li class="first last leaf">
			<?php /*echo $this->Html->link('Login', array('controller'=>'users', 'action'=>'login'))*/ ?>
		</li>
	</ul>

--><?php
/*}
*/ ?>

<?php
if($gt_member)
{
?>

    <?php echo $this->Html->link('<span>Support</span>', array('controller' => 'users', 'action' => 'view', $this->Session->read('User.id')), array('escape' => false)) ?>
	<ul class="menu">
        <!--<li class="leaf first">
            <?php /*echo $this->Html->link('Help', '#', array('onclick' => 'openHelp()')); */
        ?>
		</li>-->
		<li class="leaf first">
            <?php echo $this->Html->link('<span>Update Name/Email</span>', array('controller' => 'users', 'action' => 'view', $this->Session->read('User.id')), array('escape' => false)) ?>
		</li>
		<li class="leaf last">
            <?php echo $this->Html->link('Contact Us', array('controller' => 'resources', 'action' => 'contact')) ?>
		</li>
	</ul>
	
<?php
}
?>