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
$this -> extend('/Common/common');

// Define any crumbs for this view.
echo $this -> Html -> addCrumb('All Organizations', '/organizations');
echo $this -> Html -> addCrumb($organization['Organization']['name'], '/organizations/view/' . $organization['Organization']['id']);

// Define any extra sidebar links for this view
$this -> start('sidebar');
$sidebar = array();

if ($lace || $isOfficer)
{
	$sidebar[] = $this -> Html -> link(__('Edit Information', true), array(
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
	if ($budgetIsOpen)
	{
		$sidebar[] = $this -> Html -> link(__('Submit Budget', true), array(
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
if ($gt_member)
{
	$sidebar[] = $this -> Html -> link(__('Finance Ledger', true), array(
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

if ($orgAdminPerm)
{
	$sidebar[] = $this -> Html -> link(__('Delete Organization', true), array(
		'action' => 'delete',
		$organization['Organization']['id']
	), array('style' => 'color:red'), __('Are you sure you want to delete %s?', $organization['Organization']['name']));
}
if(!empty($sidebar)){
	echo $this -> Html -> nestedList($sidebar, array());
}
$this -> end();

$badgeStr = '';
foreach($organization['Badges'] as $badge){
	$badgeStr .= $this->element('badges/display', array('badge' => $badge));
}

// Define the main information for this view.
$this -> assign('title', $organization['Organization']['name'].$badgeStr);
$this -> start('middle');
?>
<div style="display:inline-block;position:relative;width:100%">
<div style="float:left;width:50%;">
	<?php
	/*
	echo $this -> Html -> tag('h1', 'Officers');
		echo $this -> Html -> tableBegin(array('class' => 'listing'));
		foreach ($presidents as $president)
		{
			if (isset($president['Membership']))
			{
				echo $this -> Html -> tableCells(array(
					($gt_member) ? $this -> Html -> link($president['Membership']['name'], 'mailto:' . $president['User']['email']) : $president['Membership']['name'],
					(!strcmp($president['Membership']['title'], $president['Membership']['role'])) ? $president['Membership']['title'] : $president['Membership']['title'] . " (" . $president['Membership']['role'] . ")"
				));
			}
		}
		foreach ($treasurers as $treasurer)
		{
			if (isset($treasurer['Membership']))
			{
				echo $this -> Html -> tableCells(array(
					($gt_member) ? $this -> Html -> link($treasurer['Membership']['name'], 'mailto:' . $treasurer['User']['email']) : $treasurer['Membership']['name'],
					(!strcmp($treasurer['Membership']['title'], $treasurer['Membership']['role'])) ? $treasurer['Membership']['title'] : $treasurer['Membership']['title'] . " (" . $treasurer['Membership']['role'] . ")"
				));
			}
		}
		foreach ($advisors as $advisor)
		{
			if (isset($advisor['Membership']))
			{
				echo $this -> Html -> tableCells(array(
					($gt_member) ? $this -> Html -> link($advisor['Membership']['name'], 'mailto:' . $advisor['User']['email']) : $advisor['Membership']['name'],
					(!strcmp($advisor['Membership']['title'], $advisor['Membership']['role'])) ? $advisor['Membership']['title'] : $advisor['Membership']['title'] . " (" . $advisor['Membership']['role'] . ")"
				));
			}
		}
		foreach ($officers as $officer)
		{
			if (isset($officer['Membership']))
			{
				echo $this -> Html -> tableCells(array(
					($gt_member) ? $this -> Html -> link($officer['Membership']['name'], 'mailto:' . $officer['User']['email']) : $officer['Membership']['name'],
					$officer['Membership']['title']
				));
			}
		}
		echo $this -> Html -> tableEnd();*/
	
	echo "</div>";
	/*
	echo $this -> Html -> div('org_logo', 
			'<span class="center_helper"></span>'.$this -> Html -> image($organization['Organization']['logo_path'],
			array('id' => 'logo')
		));
		echo "</div>";*/
	
	// Print out the Organization's description and other
	// general information.
	echo $this -> Html -> tag('h1', 'Description');
	//echo $this -> Html -> para('leftalign', $organization['Organization']['description']);
	if (stristr($organization['Organization']['website'], "http://") ==  false)
	{
        $site = $organization['Organization']['website'];
	} 
	else
	{
		$site = $organization['Organization']['website'];
	}
	// info list
	$list = array(
		'Tier: ' . $tier,
		/*
		'Organization Contact: ' . (($organization['User']['name'] != '') ? $this -> Html -> link($organization['User']['name'], 'mailto:' . $organization['User']['email']) : 'N/A'),
				*/
		'OrgSync: ' . (($organization['Organization']['website'] != '') ? $this -> Html -> link($site) : 'N/A'),
		/*
		'Meetings: ' . (($organization['Organization']['meeting_information'] != '') ? $organization['Organization']['meeting_information'] : 'N//A'),
				*/
		//'Dues: ' . $organization['Organization']['dues']
	);
	/*if($lace || $sga_user || $isOfficer){
		// add officer specific info to the beginning of the array
		$officer_list = array(
			'Status: '.$organization['Organization']['status'],
			'Alcohol Form Date: '.date('M j, Y', strtotime($organization['Organization']['alcohol_form'])),
			'Advisor Form Date: '.date('M j, Y', strtotime($organization['Organization']['advisor_date']))
		);
		$list = array_merge($officer_list, $list);
	}*/
	echo $this -> Html -> nestedList($list, array('id' => 'description'));
    //$this -> assign('title', $organization['Organization']['name'].$badgeStr);
    //$this -> start('middle');
	//$this -> end();
    //echo "<br/><br/>";
    $this -> end();
    $this -> start('middle');
    echo $this -> Html -> tag('h1', 'Bills');
    //$this -> end();

/*$this -> Paginator -> options(array(
    'update' => '#forupdate',
    'indicator' => '#indicator',
    'evalScripts' => true,
    'before' => $this -> Js -> get('#listing') -> effect('fadeOut', array('buffer' => false)),
    'complete' => $this -> Js -> get('#listing') -> effect('fadeIn', array('buffer' => false)),
));*/
//$this -> Html -> addCrumb($org_name, '/organizations/view/'.$org_id);
//$this -> Html -> addCrumb('Finance Ledger', $this -> here);
//$this -> extend("/Common/list");
//$this -> start('sidebar');
//$this -> end();
//$this -> assign("title", "Finance Ledger");
//$this -> start('listing');
//echo $this -> Html -> tag('h1', 'Bills');
?>
    <table class="listing">
        <?php
        echo $this -> Html -> tableheaders(array(
            $this -> Paginator -> sort('title', 'Title'),
            $this -> Paginator -> sort('number', 'Number'),
            $this -> Paginator -> sort('category', 'Category'),
            $this -> Paginator -> sort('Status.name', 'Status'),
            $this -> Paginator -> sort('submit_date', 'Submit Date')
        ), array('class' => 'links'));
        foreach ($bills as $bill)
        {
            echo $this -> Html -> tableCells(array(
                $this -> Html -> link($bill['Bill']['title'], array(
                    'controller' => 'bills',
                    'action' => 'view',
                    $bill['Bill']['id']
                )),
                $bill['Bill']['number'],
                $bill['Bill']['category'],
                $bill['Status']['name'],
                $bill['Bill']['submit_date']
            ));
        }
        ?>
    </table>
<?php
echo $this -> element('paging');
//echo $this -> Html -> tag('h1', 'Budgets');
$this -> end();
?>