<?php

/**
 * Created by PhpStorm.
 * User: Decker
 * Date: 9/25/2017
 * Time: 11:14 PM
 */
class CalculationsController extends AppController
{

    public $helpers = array(
        'Html',
        'Form',
        'Paginator',
        'Js',
        'Csv',
        'Excel',
        'Text'
    );
    public $components = array(
        'Acl',
        'RequestHandler',
        'Session',
        'Csv'
    );

    //TODO find better way to enter initial account info
    private $initials = array(
        '14' => array(
            'py' => 423703,
            'co' => 699210,
            'ulr' => 20367,
            'glr' => 9633
        ),
        '15' => array(
            'py' => 515664,
            'co' => 948402,
            'ulr' => 21000,
            'glr' => 9000
        ),
        '16' => array(
            'py' => 549692,
            'co' => 1231652,
            'ulr' => 21000,
            'glr' => 9000
        ),
        '17' => array(
            'py' => 422940,
            'co' => 1458110,
            'ulr' => 21000,
            'glr' => 9000
        ),
        '18' => array(
            'py' => 400000,
            'co' => 1000000,
            'ulr' => 21000,
            'glr' => 9000
        ),
    );

    public function ledger()
    {
        if ($this->request->is('ajax')) {
            $this->layout = 'list';
        }

        $first_fy = 14;
        $end_fy = $this->getFiscalYear() + 1;
        $fys = array();
        for ($i = $first_fy; $i <= $end_fy; $i++) {
            $fys[$i] = 'FY' . $i;
        }
        $this->set('fys', $fys);

        if (!isset($this->request->data['Ledger']['fy']) || $this->request->data('submit') === 'Clear') {
            $this->request->data['Ledger']['fy'] = $end_fy;
        }

        $fy = $this->request->data['Ledger']['fy'];
        $this->set('fy', $fy);

        //Pull bills
        $this->loadModel('Bills');
        $joint_bills = $this->Bills->find('all', array(
            'joins' => array(
                array(
                    "table" => "organizations",
                    "alias" => "Organizations",
                    "type" => "LEFT",
                    "conditions" => array(
                        "Organizations.id = Bills.org_id"
                    )
                )
            ),
            'fields' => array(
                "Bills.id",
                "Bills.number",
                "Bills.title",
                "Bills.org_id",
                "Organizations.name"
            ),
            'conditions' => array(
                'Bills.status' => '6',
                'Bills.number LIKE' => $fy . 'J%',
                'Bills.type' => 'Finance Request'
            ),
            'order' => 'Bills.number',
            'limit' => 300
        ));
        $u_bills = $this->Bills->find('all', array(
            'joins' => array(
                array(
                    "table" => "organizations",
                    "alias" => "Organizations",
                    "type" => "LEFT",
                    "conditions" => array(
                        "Organizations.id = Bills.org_id"
                    )
                )
            ),
            'fields' => array(
                "Bills.id",
                "Bills.number",
                "Bills.title",
                "Bills.org_id",
                "Organizations.name"
            ),
            'conditions' => array(
                'Bills.status' => '6',
                'Bills.type' => 'Finance Request',
                'OR' => array(
                    'Bills.number LIKE' => $fy . 'U%'
                )
            ),
            'order' => 'Bills.number',
            'limit' => 50
        ));
        $g_bills = $this->Bills->find('all', array(
            'joins' => array(
                array(
                    "table" => "organizations",
                    "alias" => "Organizations",
                    "type" => "LEFT",
                    "conditions" => array(
                        "Organizations.id = Bills.org_id"
                    )
                )
            ),
            'fields' => array(
                "Bills.id",
                "Bills.number",
                "Bills.title",
                "Bills.org_id",
                "Organizations.name"
            ),
            'conditions' => array(
                'Bills.status' => '6',
                'Bills.type' => 'Finance Request',
                'OR' => array(
                    'Bills.number LIKE' => $fy . 'G%'
                )
            ),
            'order' => 'Bills.number',
            'limit' => 50
        ));

        //Sum the line items of each bill
        $this->loadModel('LineItem');
        $accounts[] = array();
        $accounts['py']['allocated'] = 0;
        $accounts['co']['allocated'] = 0;
        $accounts['ulr']['allocated'] = 0;
        $accounts['glr']['allocated'] = 0;
        for ($i = 0; $i < sizeof($joint_bills); $i++) {
            $total = $this->LineItem->find('all', array(
                'joins' => array(
                    array(
                        "table" => "bills",
                        "alias" => "Bills",
                        "type" => "LEFT",
                        "conditions" => array(
                            "LineItem.bill_id = Bills.id"
                        )
                    )
                ),
                'fields' => array(
                    "SUM(IF(ACCOUNT = 'PY' AND STATE = 'Final', amount, 0)) AS PY",
                    "SUM(IF(ACCOUNT = 'CO' AND STATE = 'Final', amount, 0)) AS CO",
                    "SUM(IF(STATE = 'Final', amount, 0)) AS TOTAL",
                ),
                'conditions' => array(
                    'Bills.id' => $joint_bills[$i]['Bills']['id'],
                    'struck <>' => 1
                )
            ));
            $joint_bills[$i]['Bills']['py'] = $total[0][0]['PY'];
            $joint_bills[$i]['Bills']['co'] = $total[0][0]['CO'];
            $joint_bills[$i]['Bills']['tot'] = $total[0][0]['TOTAL'];
            $joint_bills[$i]['Bills']['org_name'] = $joint_bills[$i]['Organizations']['name'];
            unset($joint_bills[$i]['Organizations']);
            $accounts['py']['allocated'] += $joint_bills[$i]['Bills']['py'];
            $accounts['co']['allocated'] += $joint_bills[$i]['Bills']['co'];
        }
        $this->set('joint_bills', $joint_bills);

        for ($i = 0; $i < sizeof($u_bills); $i++) {
            $total = $this->LineItem->find('all', array(
                'joins' => array(
                    array(
                        "table" => "bills",
                        "alias" => "Bills",
                        "type" => "LEFT",
                        "conditions" => array(
                            "LineItem.bill_id = Bills.id"
                        )
                    )
                ),
                'fields' => array(
                    "SUM(IF(ACCOUNT = 'ULR' AND STATE = 'Undergraduate', amount, 0)) AS ULR",
                ),
                'conditions' => array(
                    'Bills.id' => $u_bills[$i]['Bills']['id'],
                    'struck <>' => 1
                )
            ));
            $u_bills[$i]['Bills']['ulr'] = $total[0][0]['ULR'];
            $u_bills[$i]['Bills']['org_name'] = $u_bills[$i]['Organizations']['name'];
            unset($u_bills[$i]['Organizations']);
            $accounts['ulr']['allocated'] += $u_bills[$i]['Bills']['ulr'];
        }
        $this->set('u_bills', $u_bills);

        for ($i = 0; $i < sizeof($g_bills); $i++) {
            $total = $this->LineItem->find('all', array(
                'joins' => array(
                    array(
                        "table" => "bills",
                        "alias" => "Bills",
                        "type" => "LEFT",
                        "conditions" => array(
                            "LineItem.bill_id = Bills.id"
                        )
                    )
                ),
                'fields' => array(
                    "SUM(IF(ACCOUNT = 'GLR' AND STATE = 'Graduate', amount, 0)) AS GLR",
                ),
                'conditions' => array(
                    'Bills.id' => $g_bills[$i]['Bills']['id'],
                    'struck <>' => 1
                )
            ));
            $g_bills[$i]['Bills']['glr'] = $total[0][0]['GLR'];
            $g_bills[$i]['Bills']['org_name'] = $g_bills[$i]['Organizations']['name'];
            unset($g_bills[$i]['Organizations']);
            $accounts['glr']['allocated'] += $g_bills[$i]['Bills']['glr'];
        }
        $this->set('g_bills', $g_bills);

        $accounts['py']['initial'] = $this->initials[$fy]['py'];
        $accounts['co']['initial'] = $this->initials[$fy]['co'];
        $accounts['ulr']['initial'] = $this->initials[$fy]['ulr'];
        $accounts['glr']['initial'] = $this->initials[$fy]['glr'];
        $accounts['py']['balance'] = $accounts['py']['initial'] - $accounts['py']['allocated'];
        $accounts['co']['balance'] = $accounts['co']['initial'] - $accounts['co']['allocated'];
        $accounts['ulr']['balance'] = $accounts['ulr']['initial'] - $accounts['ulr']['allocated'];
        $accounts['glr']['balance'] = $accounts['glr']['initial'] - $accounts['glr']['allocated'];
        $this->set('accounts', $accounts);
    }

    public function accounts()
    {
        $fy = $this->getFiscalYear() + 1;

        //------------------Account Pie Charts------------------
        $this->loadModel('LineItem');
        $totals = $this->LineItem->find('all', array(
            'joins' => array(
                array(
                    "table" => "bills",
                    "alias" => "Bills",
                    "type" => "LEFT",
                    "conditions" => array(
                        "LineItem.bill_id = Bills.id"
                    )
                )
            ),
            'fields' => array(
                "SUM(IF(ACCOUNT = 'PY' AND STATE = 'Final', amount, 0)) AS py",
                "SUM(IF(ACCOUNT = 'CO' AND STATE = 'Final', amount, 0)) AS co",
                "SUM(IF(ACCOUNT = 'ULR' AND STATE = 'Final', amount, 0)) AS ulr",
                "SUM(IF(ACCOUNT = 'GLR' AND STATE = 'Final', amount, 0)) AS glr",
            ),
            'conditions' => array(
                'Bills.number LIKE' => $fy . '%',
                'struck <>' => 1
            )
        ));
        $totals['initial'] = $this->initials[$fy];
        $totals['allocated'] = $totals[0][0];
        $this->set('totals', $totals);
        $this->set('fy', $fy);

        //------------------Allocations by Week------------------
        //TODO take this out, only for debugging
        $fy = 17;

        //Get first Tuesday of the year as
        $startDate = "20" . ($fy - 1) . "-07-01";
        $endDate = "20" . $fy . "-06-31";
        $endDate = strtotime($endDate);
        $tuesdays = array();
        for ($i = strtotime('Tuesday', strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i)) {
            $d = date('m-d-Y', $i);
            //array_push($tuesdays, $i);
            $tuesdays[$i]['date'] = $d;
            $tuesdays[$i]['py_allocated'] = 0;
            $tuesdays[$i]['co_allocated'] = 0;
            $tuesdays[$i]['ulr_allocated'] = 0;
            $tuesdays[$i]['glr_allocated'] = 0;
            //date('m-d-Y', $i)
        }
        //echo Debugger::exportVar($tuesdays);

        $this->loadModel('Bills');
        $billsWithDates = $this->Bills->find('all', array(
            'joins' => array(
                array(
                    "table" => "bill_votes",
                    "alias" => "GSSVotes",
                    "type" => "LEFT",
                    "conditions" => array(
                        "Bills.gss_id = GSSVotes.id"
                    )
                ),
                array(
                    "table" => "bill_votes",
                    "alias" => "UHRVotes",
                    "type" => "LEFT",
                    "conditions" => array(
                        "Bills.uhr_id = UHRVotes.id"
                    )
                )
            ),
            'fields' => array(
                "Bills.id",
                "Bills.number",
                "GSSVotes.date",
                "UHRVotes.date",
                "IF(GSSVotes.date IS NOT NULL, IF(UHRVotes.date IS NOT NULL, IF(GSSVotes.date > UHRVotes.date, GSSVotes.date, UHRVotes.date), GSSVotes.date), UHRVotes.date) AS date"
            ),
            'conditions' => array(
                'Bills.number LIKE' => $fy . '%',
                'Bills.type' => 'Finance Request',
                'Bills.status' => 6
            )
        ));

        //echo Debugger::exportVar($billsWithDates);

        for ($i = 0; $i < sizeof($billsWithDates); $i++) {
            $billsWithDates[$i]['Bills']['date'] = $billsWithDates[$i][0]['date'];
            unset($billsWithDates[$i][0]['date']);


            $total = $this->LineItem->find('all', array(
                'joins' => array(
                    array(
                        "table" => "bills",
                        "alias" => "Bills",
                        "type" => "LEFT",
                        "conditions" => array(
                            "LineItem.bill_id = Bills.id"
                        )
                    )
                ),
                'fields' => array(
                    "SUM(IF(ACCOUNT = 'PY' AND STATE = 'Final', amount, 0)) AS PY",
                    "SUM(IF(ACCOUNT = 'CO' AND STATE = 'Final', amount, 0)) AS CO",
                    "SUM(IF(ACCOUNT = 'ULR' AND STATE = 'Undergraduate', amount, 0)) AS ULR",
                    "SUM(IF(ACCOUNT = 'GLR' AND STATE = 'Graduate', amount, 0)) AS GLR",
                ),
                'conditions' => array(
                    'Bills.id' => $billsWithDates[$i]['Bills']['id'],
                    'struck <>' => 1
                )
            ));
            $billsWithDates[$i]['Bills']['py'] = $total[0][0]['PY'];
            $billsWithDates[$i]['Bills']['co'] = $total[0][0]['CO'];
            $billsWithDates[$i]['Bills']['ulr'] = $total[0][0]['ULR'];
            $billsWithDates[$i]['Bills']['glr'] = $total[0][0]['GLR'];
        }

        //echo '<br><br><br>'.Debugger::exportVar($tuesdays);

        for ($i = 0; $i < sizeof($billsWithDates); $i++) {
            $tues = strtotime('previous tuesday', strtotime($billsWithDates[$i]['Bills']['date']));
            //echo $billsWithDates[$i]['Bills']['id'].'   |   '.$billsWithDates[$i]['Bills']['number'].'   |   '.$tues.'<br>';
            if ($tues > 0) {
                $tuesdays[$tues]['py_allocated'] = $tuesdays[$tues]['py_allocated'] + $billsWithDates[$i]['Bills']['py'];
                $tuesdays[$tues]['co_allocated'] = $tuesdays[$tues]['co_allocated'] + $billsWithDates[$i]['Bills']['co'];
                $tuesdays[$tues]['ulr_allocated'] = $tuesdays[$tues]['ulr_allocated'] + $billsWithDates[$i]['Bills']['ulr'];
                $tuesdays[$tues]['glr_allocated'] = $tuesdays[$tues]['glr_allocated'] + $billsWithDates[$i]['Bills']['glr'];
            }
        }

        $py_balance = $this->initials[$fy]['py'];
        $co_balance = $this->initials[$fy]['co'];
        $ulr_balance = $this->initials[$fy]['ulr'];
        $glr_balance = $this->initials[$fy]['glr'];

        $last_date = $tuesdays[end(array_keys($tuesdays))]['date'];
        $first_date = $tuesdays[reset(array_keys($tuesdays))]['date'];
        foreach ($tuesdays as &$val) {
            //echo $val['date'].'  |  '.$val['py_allocated'].'  |  '. $val['co_allocated'].'  |  '. $val['ulr_allocated'].'  |  '. $val['glr_allocated'].'<br>';

            if ($val['date'] && ($val['py_allocated'] != 0 || $val['co_allocated'] != 0 || $val['ulr_allocated'] != 0 || $val['glr_allocated'] != 0)) {
                $py_balance = $py_balance - $val['py_allocated'];
                $co_balance = $co_balance - $val['co_allocated'];
                $ulr_balance = $ulr_balance - $val['ulr_allocated'];
                $glr_balance = $glr_balance - $val['glr_allocated'];

                $val['date_nonzero'] = $val['date'];
                $val['py_balance'] = $py_balance;
                $val['co_balance'] = $co_balance;
                $val['ulr_balance'] = $ulr_balance;
                $val['glr_balance'] = $glr_balance;
            } else {
                $val['date_nonzero'] = $val['date'];
                $val['py_balance'] = null;
                $val['co_balance'] = null;
                $val['ulr_balance'] = null;
                $val['glr_balance'] = null;
            }

            if ($val['date'] == $first_date) {
                $val['date_nonzero'] = $val['date'];
                $val['py_balance'] = $py_balance;
                $val['co_balance'] = $co_balance;
                $val['ulr_balance'] = $ulr_balance;
                $val['glr_balance'] = $glr_balance;
            } elseif ($val['date'] == $last_date) {
                $val['date_nonzero'] = $val['date'];
                $val['py_balance'] = $py_balance;
                $val['co_balance'] = $co_balance;
                $val['ulr_balance'] = $ulr_balance;
                $val['glr_balance'] = $glr_balance;
            }
        }

        //echo Debugger::exportVar($tuesdays);

        $this->set('tuesdays', $tuesdays);
    }

    public function chart()
    {

    }
}