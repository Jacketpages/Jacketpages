<div class="links left-nav" id="sidebar">
        <?php
		echo $this -> fetch('sidebar');
		if($budgetSubmitted)
		{
			echo $this -> Html -> nestedList(array(
				$this -> Html -> link('Past Organization Information', array('controller' => 'budgets','action' => 'submit',$org_id)),
				$this -> Html -> link('Budget Line Items', array('controller' => 'budgetlineitems','action' => 'edit',$org_id)),
				$this -> Html -> link('Fundraising', array('controller' => 'budgets','action' => 'fundraising',$org_id)),
				$this -> Html -> link('Expenses', array('controller' => 'budgets','action' => 'expenses',$org_id)),
				$this -> Html -> link('Assets and Liabilities', array('controller' => 'budgets','action' => 'assets_and_liabilities',$org_id)),
				$this -> Html -> link('Member Contributions', array('controller' => 'budgets','action' => 'member_contributions',$org_id)),
				$this -> Html -> link('Summary', array('controller' => 'budgets','action' => 'summary',$org_id))
			));
		}
		echo $this -> element('sidebar');
        ?>
</div>
<div id="middle">
    <div id="page_title"><?php echo $this -> fetch('title'); ?></div>
    <?php echo $this -> fetch('middle'); ?>
</div>