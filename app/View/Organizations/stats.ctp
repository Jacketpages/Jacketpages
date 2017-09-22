<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 *
 * @param $organization - The selected organization's information
 * @param $president - The president of the selected organization
 * @param $treasurer - The treasurer of the selected organization
 * @param $advisor - The advisor of the selected organization
 * @param $officers - The remaining officers of the selected organization
 * @param $members - The members of the selected organization
 * @param $pending_members - The pending members of the selected organization
 */

// Define which view this view extends.
$this->extend('/Common/common');

// Define any crumbs for this view.
echo $this->Html->addCrumb('Organizations', '/organizations');
echo $this->Html->addCrumb($organization['Organization']['name'], '/organizations/view/' . $organization['Organization']['id']);

// Define any extra sidebar links for this view
$this->start('sidebar');
$sidebar = array();

if ($lace || $isOfficer) {
    $sidebar[] = $this->Html->link(__('Edit Information', true), array(
        'action' => 'edit',
        $organization['Organization']['id']
    ));
    /*
    $sidebar[] = $this -> Html -> link(__('Edit Logo', true), array(
            'action' => 'addlogo',
            $organization['Organization']['id']
        ));*/
}

if ($gt_member) {
    if ($budgetIsOpen) {
        $sidebar[] = $this->Html->link(__('Submit Budget', true), array(
            'controller' => 'budgets',
            'action' => 'submit',
            $organization['Organization']['id']
        ));
    }
}

/*if ($lace || $isOfficer || $isMember)
{
	$sidebar[] = $this -> Html -> link(__('Documents', true), array(
			'controller' => 'documents',
			'action' => 'index',
			$organization['Organization']['id']
		));
	$sidebar[] = $this -> Html -> link(__('Roster', true), array(
		'controller' => 'memberships',
		'action' => 'index',
		$organization['Organization']['id']
	));
}
*/
//MRE TODO add ledger page in budgets
if ($gt_member) {
    $sidebar[] = $this->Html->link(__('Finance Ledger', true), array(
        'controller' => 'bills',
        'action' => 'ledger',
        $organization['Organization']['id']
    ));

    /*if (!$orgJoinOrganizationPerm)
    {
        $sidebar[] = $this -> Html -> link(__('Join Organization', true), array(
            'controller' => 'memberships',
            'action' => 'joinOrganization',
            $organization['Organization']['id']
        ), null, __('Are you sure you want to join ' . $organization['Organization']['name'] . '?', true));
    }*/
}

if ($orgAdminPerm) {
    $sidebar[] = $this->Html->link(__('Delete Organization', true), array(
        'action' => 'delete',
        $organization['Organization']['id']
    ), array('style' => 'color:red'), __('Are you sure you want to delete %s?', $organization['Organization']['name']));
}
if (!empty($sidebar)) {
    echo $this->Html->nestedList($sidebar, array());
}
$this->end();

$badgeStr = '';
foreach ($organization['Badges'] as $badge) {
    $badgeStr .= $this->element('badges/display', array('badge' => $badge));
}

// Define the main information for this view.
$this->assign('title', $organization['Organization']['name'] . $badgeStr);
$this->start('middle');
?>
    <div style="display:inline-block;position:relative;width:100%">
        <div style="float:left;width:50%;">
<?php

echo "</div>";
/*	echo $this -> Html -> tag('h1', 'Description');
	echo $this -> Html -> para('leftalign', $organization['Organization']['name']);
	echo $this -> Html -> tag('h1', 'FY Totals');
	echo $this -> Html -> para('leftalign', Debugger::exportVar($fy_totals));*/

$i = -1;
echo $this->Html->tag('h1', 'Total Allocation by Fiscal Year');
echo $this->Html->tableBegin(array(
    'class' => 'list',
    'id' => 'outcomes'
));
$fy_headers[] = '';
$py_vals[] = 'PY';
$co_vals[] = 'CO';
$tot_vals[] = 'Total';
for ($fy = $first_fy; $fy <= $end_fy; $fy++) {
    $i++;
    $fy_headers[] = 'FY' . $fy;
    $py_vals[] = $this->Number->currency($fy_totals[$i][$fy . 'PY'], 'USD');
    $co_vals[] = $this->Number->currency($fy_totals[$i][$fy . 'CO'], 'USD');
    $tot_vals[] = $this->Number->currency($fy_totals[$i][$fy . 'TOTAL'], 'USD');
}

echo $this->Html->tableHeaders($fy_headers);
echo $this->Html->tableCells($py_vals);
echo $this->Html->tableCells($co_vals);
echo $this->Html->tableCells($tot_vals);
echo $this->Html->tableEnd();


//echo $this -> Html -> tag('h1', 'Budgets');
$this->end();
?>