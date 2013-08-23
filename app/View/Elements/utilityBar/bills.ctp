<?php
/**
 * @author Stephen Roca
 * @since 8/14/2013
 */

if ($gt_member)
{
	$billList = array($this -> Html -> link('Bills', '#') => array(
			$this -> Html -> link('Submit Bill', array(
				'controller' => 'bills',
				'action' => 'add'
			)),
			$this -> Html -> link('View My Bills', array(
				'controller' => 'bills',
				'action' => 'my_bills'
			)),
			$viewBills = $this -> Html -> link('View All Bills', array(
				'controller' => 'bills',
				'action' => 'index'
			))
		));
	echo $this -> Html -> nestedList($billList);
}
