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
    public $helpers = array(/*'Html',
        'Form',
        'Paginator',
        'Js',
        'Csv',
        'Excel',
        'Text'*/
    );
    public $components = array(/*'Acl',
        'RequestHandler',
        'Session',
        'Csv'*/
    );

    public function ledger()
    {
        $fy = '17';

        $this->loadModel('Bills');
        $this->paginate = array(
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
        );
        $bills = $this->paginate('Bills');
        $this->set('bills', $bills);

        $this->loadModel('LineItem');
        $bill_totals[] = array();
        foreach ($bills as $bill) {
            $bill_tot = $this->LineItem->find('all', array(
                "joins" => array(
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
                    'Bills.id' => $bill['Bills']['id'],
                    'struck <>' => 1
                )
            ));
            array_push($bill_totals, $bill_tot[0]);
        }
        array_shift($bill_totals);
        $this->set('bill_totals', $bill_totals);
        //echo Debugger::exportVar($bill_totals);


        //TODO make time scalable automatically
        $first_fy = 14;
        $end_fy = 18;
        //for ($fy = $first_fy; $fy <= $end_fy; $fy++) {
        /*$fy = '17';
        $this -> paginate = array(
            'conditions' => array(
                'Bills.status' => '6',
                array('Bills.number LIKE' => $fy . 'J%')
            ),
            'order' => 'Bills.number',
            'limit' => 100
        );
        $this -> loadModel('Bill');
        $this -> set('bills', $this -> paginate('Bills'));*/

        /*$fy_totals_year = $this->LineItem->find('all', array(
            "joins" => array(
                "table" => "bills",
                    "alias" => "Bills",
                    "type" => "LEFT",
                    "conditions" => array(
                        "LineItem.bill_id = Bills.id"
                    )
            ),
            'fields' => array(
                "SUM(IF(ACCOUNT = 'PY' AND STATE = 'Final',amount, 0)) AS " . $fy . "PY",
                "SUM(IF(ACCOUNT = 'CO' AND STATE = 'Final',amount, 0)) AS " . $fy . "CO",
                "SUM(IF(STATE = 'Final',amount, 0)) AS " . $fy . "TOTAL",
            ),
            'conditions' => array(
                //'bill_id' => $id,
                'Bills.org_id' => $org_id,
                'Bills.number LIKE' => $fy . '%',
                'struck <>' => 1
            )
        ));*/
        //array_push($fy_totals[0], $fy_totals_year[0][0]);*/
        //}

    }

}