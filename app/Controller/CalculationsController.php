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

        $this->loadModel('Bills');
        //$this->paginate = array(
        $bills = $this->Bills->find('all', array(
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
        //$bills = $this->paginate('Bills');
        //echo Debugger::exportVar($bills);

        $this->loadModel('LineItem');
        $bill_totals[] = array();
        $accounts[] = array();
        $accounts['py']['allocated'] = 0;
        $accounts['co']['allocated'] = 0;
        //foreach ($bills as $bill) {
        for ($i = 0; $i < sizeof($bills); $i++) {
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
                    'Bills.id' => $bills[$i]['Bills']['id'],
                    'struck <>' => 1
                )
            ));
            $bills[$i]['Bills']['py'] = $total[0][0]['PY'];
            $bills[$i]['Bills']['co'] = $total[0][0]['CO'];
            $bills[$i]['Bills']['tot'] = $total[0][0]['TOTAL'];
            $bills[$i]['Bills']['org_name'] = $bills[$i]['Organizations']['name'];
            unset($bills[$i]['Organizations']);
            $accounts['py']['allocated'] += $bills[$i]['Bills']['py'];
            $accounts['co']['allocated'] += $bills[$i]['Bills']['co'];
        }
        $this->set('bills', $bills);
        $this->set('fy', $fy);

        //TODO find better way to enter initial acocutn info
        $accounts['py']['initial'] = $this->initials[$fy]['py'];
        $accounts['co']['initial'] = $this->initials[$fy]['co'];
        $accounts['py']['balance'] = $accounts['py']['initial'] - $accounts['py']['allocated'];
        $accounts['co']['balance'] = $accounts['co']['initial'] - $accounts['co']['allocated'];
        $this->set('accounts', $accounts);
    }

}