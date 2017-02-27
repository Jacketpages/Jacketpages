
<?php echo $this->Html->link('<span>Organizations</span>', array('controller' => 'organizations', 'action' => 'index'), array('escape' => false))?>
<!--<ul class="menu">
	<li class="leaf first <?php /*echo (!$gt_member)?'last':''*/?>">
<?php /*echo $this->Html->link('View All Organizations', array('controller' => 'organizations', 'action' => 'index'))*/?>
	</li>
	
<?php /*if ($gt_member){ */?>
	<li class="leaf">
		<?php /*echo $this->Html->link('View My Organizations', array('controller' => 'organizations', 'action' => 'my_orgs', $this -> Session -> read('User.id')))*/?>
	</li>
	<li class="leaf">
	<?php /*echo $this->Html->link('View Inactive Organizations', array( 'controller' => 'organizations', 'action' => 'inactive_orgs'))*/?>
	</li>
<?php /*} */?>
	<li class="leaf last <?php /*echo (!$gt_member)?'last':''*/?>">
<?php /*echo $this->Html->link('Silver Leaf Certified', array('controller' => 'organizations', 'action' => 'silverleaf'))*/?>
	</li>

</ul>-->