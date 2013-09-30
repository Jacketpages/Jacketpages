<div class="links" id="sidebar">
        <?php
		echo $this -> fetch('sidebar');
		if($budgetSubmitted)
			echo $this -> Html -> nestedList(array(
				$this -> Html -> link('Past Organization Information', array('action' => 'index')),
				$this -> Html -> link('Budget Line Items', array('action' => 'index')),
				$this -> Html -> link('Fundraising', array('action' => 'index')),
				$this -> Html -> link('Expenses', array('action' => 'index')),
				$this -> Html -> link('Member Contributions', array('action' => 'index')),
				$this -> Html -> link('Summary', array('action' => 'index'))
			), array(), array('id' => 'underline'));
		echo $this -> element('sidebar');
        ?>
</div>
<div id="middle">
    <h1><?php echo $this -> fetch('title'); ?></h1>
    <?php echo $this -> fetch('middle'); ?>
</div>