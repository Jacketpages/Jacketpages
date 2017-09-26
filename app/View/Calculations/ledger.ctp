<?php
/**
 * @author Stephen Roca
 * @since 06/26/2012
 */
$this->Paginator->options(array(
    'update' => '#forupdate',
    'indicator' => '#indicator',
    'evalScripts' => true,
    'before' => $this->Js->get('#listing')->effect('fadeOut', array('buffer' => false)),
    'complete' => $this->Js->get('#listing')->effect('fadeIn', array('buffer' => false)),
));
$this->Html->addCrumb('All Bills This Year', '/ledger');
$this->extend("/Common/list");
/*$this -> start('sidebar');
echo $this -> Html -> nestedList(array(
	$this -> Html -> link('My Bills', array('action' => 'my_bills')),
	$this -> Html -> link('Create New Bill', array('action' => 'add')),
	// $this -> Html -> link('Export FY Data', array(
		// 'admin' => false,
		// 'action' => 'export'
	// ))
), array());
$this -> end();*/
$this->assign("title", "Ledger");
$this->start('search');
?>
<!--<div id="accordion">
	<a href="#">Filters</a>
	<div>
		<div style="float: left; width: 45%;">
			<ul>
				<?php
/*				echo $this -> Form -> input('from', array(
				'label' => 'From Status',
					'options' => array(
						1 => 'Created',
						2 => 'Awaiting Author',
						3 => 'Authored',
						4 => 'Agenda',
						5 => 'Conference',
						6 => 'Passed',
						7 => 'Failed',
						8 => 'Tabled'
					),
					'selected' => $this -> Session -> read('Bill.from')
				));
				echo $this -> Form -> input('to', array(
				'label' => 'To Status',
					'options' => array(
						1 => 'Created',
						2 => 'Awaiting Author',
						3 => 'Authored',
						4 => 'Agenda',
						5 => 'Conference',
						6 => 'Passed',
						7 => 'Failed',
						8 => 'Tabled'
					),
					'selected' => ($this -> Session -> read('Bill.to') == null ? 7 : $this -> Session -> read('Bill.to'))
				));
				*/ ?>
			</ul>
		</div>
		<div style="float: right; width: 45%;">
			<?php
/*			echo $this -> Form -> input('category', array(
				'options' => array(
					'All' => 'All',
					'Joint' => 'Joint',
					'Undergraduate' => 'Undergraduate',
					'Graduate' => 'Graduate',
				),
				'selected' => $this -> Session -> read('Bill.category')
			));
			echo "<br/>";
			echo $this -> Form -> submit('Submit', array('div' => array('style' => 'display:inline-block')));
			echo $this -> Form -> submit('Clear', array(
				'div' => array('style' => 'display:inline-block'),
				'name' => 'submit'
			));
			*/ ?>
		</div>
		<div style="clear:both"></div>
	</div>
</div>-->
<?php
$this->end();
$this->start('listing');
?>
<div id='forupdate'>
    <?php
    echo $this->element('calculations/ledger/ledgerBillTable', array('bills' => $bills,
        'bill_totals' => $bill_totals));
    echo $this->element('paging');
    ?>
</div>
<?php
$this->end();
?>
