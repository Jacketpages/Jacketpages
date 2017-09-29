<?php
if ($gt_member) {
    ?>

    <?php echo $this->Html->link('<span>Calculations</span>', array('controller' => 'calculations', 'action' => 'ledger'), array('escape' => false)) ?>
    <ul class="menu">
        <li class="leaf first">
            <?php echo $this->Html->link('FY Ledger', array('controller' => 'calculations', 'action' => 'ledger')) ?>
        </li>
        <li class="leaf">
            <?php echo $this->Html->link('By Organization', array('controller' => 'calculations', 'action' => 'orgs')) ?>
        </li>
        <li class="leaf last">
            <?php echo $this->Html->link('Account Totals', array('controller' => 'calculations', 'action' => 'accounts')) ?>
        </li>
    </ul>

    <?php
}
?>