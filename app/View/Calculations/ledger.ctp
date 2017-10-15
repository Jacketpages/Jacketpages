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
$this->assign("title", "FY" . $fy . " Ledger");
$this->start('search');
?>
<div id="accordion">
    <a href="#">Fiscal Year</a>
	<div>
		<div style="float: left; width: 45%;">
			<ul>
				<?php
                echo $this->Form->create('Ledger');
                echo $this->Form->input('fy', array(
                    'label' => 'FY',
                    'options' => $fys /*array(
						1 => 'FY14',
						2 => 'FY15',
						3 => 'FY16',
						4 => 'FY17',
						5 => 'FY18' //TODO make scalable
					)*/
				));
                ?>
			</ul>
		</div>
        <div style="float: right; width: 45%;">
            <?php
            echo $this->Form->submit('Submit', array('div' => array('style' => 'display:inline-block')));
            echo $this->Form->submit('Clear', array(
                'div' => array('style' => 'display:inline-block'),
                'name' => 'submit'
            ));
            ?>
        </div>
        <div style="clear:both"></div>
	</div>
</div>
<?php
$tableOptions = array(
    'class' => 'list even',
    'id' => 'account_summary',
    'style' => 'width: 25%;display:inline-table'
);
$tdOptions = array('style' => 'width: auto');

echo $this->Html->tag('h1', 'Account Summary');

echo $this->Html->tableBegin($tableOptions);
//echo $this -> Html -> tableHeaders(array('Test1', 'Test2'));
echo $this->Html->tableCells(array('PY Initial', $this->Number->currency($accounts['py']['initial'])), null, $tdOptions);
echo $this->Html->tableCells(array('PY Allocated', $this->Number->currency($accounts['py']['allocated'])));
echo $this->Html->tableCells(array('PY Balance', $this->Number->currency($accounts['py']['balance'])));
echo $this->Html->tableEnd();
///

echo $this->Html->tableBegin($tableOptions);
echo $this->Html->tableCells(array('CO Initial', $this->Number->currency($accounts['co']['initial'])));
echo $this->Html->tableCells(array('CO Allocated', $this->Number->currency($accounts['co']['allocated'])));
echo $this->Html->tableCells(array('CO Balance', $this->Number->currency($accounts['co']['balance'])));
echo $this->Html->tableEnd();

echo $this->Html->tableBegin($tableOptions);
echo $this->Html->tableCells(array('ULR Initial', $this->Number->currency($accounts['ulr']['initial'])));
echo $this->Html->tableCells(array('ULR Allocated', $this->Number->currency($accounts['ulr']['allocated'])));
echo $this->Html->tableCells(array('ULR Balance', $this->Number->currency($accounts['ulr']['balance'])));
echo $this->Html->tableEnd();

echo $this->Html->tableBegin($tableOptions);
echo $this->Html->tableCells(array('GLR Initial', $this->Number->currency($accounts['glr']['initial'])));
echo $this->Html->tableCells(array('GLR Allocated', $this->Number->currency($accounts['glr']['allocated'])));
echo $this->Html->tableCells(array('GLR Balance', $this->Number->currency($accounts['glr']['balance'])));
echo $this->Html->tableEnd();

?>
<div id='forupdate'>
    <?php
    echo $this->Html->tag('h1', 'Joint Bills');
    echo $this->element('calculations/ledger/ledgerJointBillTable', array('bills' => $joint_bills));
    echo $this->Html->tag('h1', 'Undergraduate Bills');
    echo $this->element('calculations/ledger/ledgerUBillTable', array('bills' => $u_bills));
    echo $this->Html->tag('h1', 'Graduate Bills');
    echo $this->element('calculations/ledger/ledgerGBillTable', array('bills' => $g_bills));
    ?>
</div>
<?php
$this->end();
?>
