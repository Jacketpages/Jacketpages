<?php

$this -> extend('/Common/common');
$this -> assign('title', 'Timebound Resources');

echo $this -> Html -> addCrumb('Budget Submissions', '/budgets/index');

$this -> start('middle');
echo $this->Html->css('timebound');
if(!empty($budgetWindow))
{
	echo $this -> Html -> tableBegin();
	echo $this -> Html -> tableHeaders(array('Type','Alias','Start Time','End Time', 'Delete'));
	echo $this -> Html -> tableCells(array(
		$budgetWindow['TimeboundResource']['name'],
		$budgetWindow['TimeboundResource']['alias'],
		$budgetWindow['TimeboundResource']['start_time'],
		$budgetWindow['TimeboundResource']['end_time'],
		$this -> Html -> link('Delete',
		array('action' => 'delete', $budgetWindow['TimeboundResource']['id'])
		)
	));
	echo $this -> Html -> tableEnd(); 
}
echo $this -> Form -> create();
echo $this -> Form -> hidden('id');
echo $this -> Form -> input('start_time');
echo $this -> Form -> input('end_time');
echo $this->Form->end(__('Submit', true));

$this->end();
?>
