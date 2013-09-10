
<?=$this->Html->link('<span>Organizations</span>', array('controller' => 'organizations', 'action' => 'index'), array('escape' => false))?>
<ul class="menu">
	<li class="leaf first <?=(!$gt_member)?'last':''?>">
<?=$this->Html->link('View All Organizations', array('controller' => 'organizations', 'action' => 'index'))?>
	</li>
	
<?php if ($gt_member){ ?>
	<li class="leaf">
		<?=$this->Html->link('View My Organizations', array('controller' => 'organizations', 'action' => 'my_orgs', $this -> Session -> read('User.id')))?>
	</li>
	<li class="leaf last">
	<?=$this->Html->link('View Inactive Organizations', array( 'controller' => 'organizations', 'action' => 'inactive_orgs'))?>
	</li>
<?php } ?>

</ul>