<?php
if ($gt_member) {
    ?>

    <?php echo $this->Html->link('<span>Resources</span>', array('controller' => 'resources', 'action' => 'view0'), array('escape' => false)) ?>
    <ul class="menu">
        <li class="leaf first">
            <?php echo $this->Html->link('Guides/Documents', array('controller' => 'resources', 'action' => 'view1')) ?>
        </li>
        <li class="leaf">
            <?php echo $this->Html->link('Travel Calculator', array('controller' => 'resources', 'action' => 'view2')) ?>
        </li>
        <li class="leaf last">
            <?php echo $this->Html->link('Budget Information', array('controller' => 'resources', 'action' => 'view3')) ?>
        </li>
    </ul>

    <?php
}
?>