<?php if(!$budgetSubmitted) {?>
<div class="links left-nav" id="sidebar">
		<?php echo $this -> fetch('sidebar');
			echo $this -> Html -> nestedList(array(
				$this -> Html -> link('Organization Information', array('controller' => 'budgets','action' => 'submit',$org_id)),
				$this -> Html -> link('Budget Line Items', array('controller' => 'budget_line_items','action' => 'edit',$org_id)),
				$this -> Html -> link('Fundraising', array('controller' => 'budgets','action' => 'fundraising',$org_id)),
				$this -> Html -> link('Expenses', array('controller' => 'budgets','action' => 'expenses',$org_id)),
				$this -> Html -> link('Assets and Liabilities', array('controller' => 'budgets','action' => 'assets_and_liabilities',$org_id)),
				$this -> Html -> link('Member Contributions', array('controller' => 'budgets','action' => 'member_contributions',$org_id)),
				$this -> Html -> link('Summary', array('controller' => 'budgets','action' => 'summary',$org_id))
			));
		echo $this -> element('sidebar');
        ?>
</div>
<?php } ?>

<div id="<?php echo (!$budgetSubmitted)?'middle':'middle_full'; ?>">
    <div id="page_title"><?php echo $this -> fetch('title'); ?></div>
    <?php echo $this -> fetch('middle'); ?>
</div>