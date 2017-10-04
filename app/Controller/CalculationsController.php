<?php

/**
 * Created by PhpStorm.
 * User: Decker
 * Date: 9/25/2017
 * Time: 11:14 PM
 */
class CalculationsController extends AppController
{

    /**
     * Overidden $components, $helpers, and $uses
     */
    public $helpers = array(
        'Session',
        'Form'
        /*'Html',
        'Paginator',
        'Js',
        'Csv',
        'Excel',
        'Text'*/
    );
    public $components = array(
        'Session'
        /*'Acl',
        'RequestHandler',
        'Session',
        'Csv'*/
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
            'py' => 999999,
            'co' => 999999,
            'ulr' => 21000,
            'glr' => 9000
        ),
    );

    public function ledger()
    {
        if ($this->request->is('ajax')) {
            $this->layout = 'list';
        }

        //TODO make time scalable automatically
        $first_fy = 14;
        $end_fy = 18;
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

}