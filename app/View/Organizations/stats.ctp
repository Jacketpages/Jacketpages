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
$py_vals_currency[] = 'PY';
$co_vals_currency[] = 'CO';
$tot_vals_currency[] = 'Total';
for ($fy = $first_fy; $fy <= $end_fy; $fy++) {
    $i++;
    $fy_headers[] = 'FY' . $fy;
    $py_val =/* $this->Number->currency(*/
        (float)$fy_totals[$i][$fy . 'PY']/*, 'USD')*/
    ;
    $co_val = (float)$fy_totals[$i][$fy . 'CO'];
    $tot_val = $this->Number->currency($fy_totals[$i][$fy . 'TOTAL'], 'USD');

    $py_vals[] = $py_val;
    $co_vals[] = $co_val;
    $tot_vals[] = $tot_val;

    $py_vals_currency[] = $this->Number->currency($py_val);
    $co_vals_currency[] = $this->Number->currency($co_val);
    $tot_vals_currency[] = $this->Number->currency($tot_val);

}

echo $this->Html->tableHeaders($fy_headers);
echo $this->Html->tableCells($py_vals_currency);
echo $this->Html->tableCells($co_vals_currency);
echo $this->Html->tableCells($tot_vals_currency);
echo $this->Html->tableEnd();
array_shift($fy_headers);


echo $this->Html->tag('h1', 'Allocation by Fiscal Year');
//CHART!!!!!!!!!!!!
use Ghunti\HighchartsPHP\Highchart;
use Ghunti\HighchartsPHP\HighchartJsExpr;

$chart = new Highchart();

$chart->chart->renderTo = "container";
$chart->chart->type = "area";
$chart->title->text = "Student Activity Fee Allocation per Fiscal Year";
$chart->subtitle->text = "From Bills";
$chart->xAxis->categories = $fy_headers;
$chart->xAxis->tickmarkPlacement = "on";
$chart->xAxis->title->enabled = false;
$chart->yAxis->title->text = "";
$chart->yAxis->labels->formatter = new HighchartJsExpr("function() { return this.value}");
$chart->tooltip->formatter = new HighchartJsExpr(
/*"function() { return '' + this.x +': '+ Highcharts.numberFormat(this.y, 0, ',') +' millions';}"*/
    "function() { return '' + this.series.name +': $'+ Highcharts.numberFormat(this.y, 0, '.', ',');}"
);
$chart->plotOptions->area->stacking = "normal";
$chart->plotOptions->area->lineColor = "#666666";
$chart->plotOptions->area->lineWidth = 1;
$chart->plotOptions->area->marker->lineWidth = 1;
$chart->plotOptions->area->marker->lineColor = "#666666";

$chart->series[] = array(
    'name' => "PY",
    'data' => $py_vals
);
$chart->series[] = array(
    'name' => "CO",
    'data' => $co_vals
);

?>

    <div id="container"></div>
    <script src="http://code.highcharts.com/highcharts.src.js"></script>
    <script type="text/javascript"><?php echo $chart->render("chart1"); ?></script>

<?php

//echo $this -> Html -> tag('h1', 'Budgets');
$this->end();
?>