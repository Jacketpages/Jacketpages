<?php
/**
 * @author Stephen Roca
 * @since 06/26/2012
 */
class BillsController extends AppController
{
	public $helpers = array(
		'Form',
		'Html',
		'Session',
		'Number'
	);
	public function index($id = null)
	{
		// Set page view permissions
		$this -> set('billExportPerm', $this -> Acl -> check('Role/' . $this -> Session -> read('USER.LEVEL'), 'billExportPerm'));

		if ($id != null)
		{
			$this -> set('bills', $this -> paginate('Bill', array('SUBMITTER' => $id)));
		}
		else
		{
			$this -> set('bills', $this -> paginate('Bill'));
		}
	}

	public function view($id = null)
	{
		// Set which bill to retrieve from the database.
		$this -> Bill -> id = $id;
		$this -> set('bill', $this -> Bill -> read());
		// Set the lineitem arrays for the different states to
		// pass to the view.
		$this -> loadModel('Line_Item');
		$this -> set('submitted', $this -> Line_Item -> find('all', array('conditions' => array(
				'BILL_ID' => $id,
				'STATE' => 'Submitted'
			))));
		$this -> set('jfc', $this -> Line_Item -> find('all', array('conditions' => array(
				'BILL_ID' => $id,
				'STATE' => 'JFC'
			))));
		$this -> set('graduate', $this -> Line_Item -> find('all', array('conditions' => array(
				'BILL_ID' => $id,
				'STATE' => 'Graduate'
			))));
		$this -> set('undergraduate', $this -> Line_Item -> find('all', array('conditions' => array(
				'BILL_ID' => $id,
				'STATE' => 'Undergraduate'
			))));
		$this -> set('conference', $this -> Line_Item -> find('all', array('conditions' => array(
				'BILL_ID' => $id,
				'STATE' => 'Conference'
			))));
		$this -> set('all', $this -> Line_Item -> find('all', array(
			'conditions' => array('BILL_ID' => $id),
			'order' => array("FIELD(STATE, 'Submitted','JFC', 'Graduate', 'Undergraduate', 'Conference', 'Final')")
		)));
		$this -> set('final', $this -> Line_Item -> find('all', array('conditions' => array(
				'BILL_ID' => $id,
				'STATE' => 'Final'
			))));
		// Set the amounts for prior year, capital outlay, and total
		$totals = $this -> Line_Item -> find('all', array(
			'fields' => array(
				"SUM(IF(ACCOUNT = 'PY' AND STATE = 'Submitted',TOTAL_COST, 0)) AS PY_SUBMITTED",
				"SUM(IF(ACCOUNT = 'CO' AND STATE = 'Submitted',TOTAL_COST, 0)) AS CO_SUBMITTED",
				"SUM(IF(STATE = 'Submitted',TOTAL_COST, 0)) AS TOTAL_SUBMITTED",
				"SUM(IF(ACCOUNT = 'PY' AND STATE = 'JFC',TOTAL_COST, 0)) AS PY_JFC",
				"SUM(IF(ACCOUNT = 'CO' AND STATE = 'JFC',TOTAL_COST, 0)) AS CO_JFC",
				"SUM(IF(STATE = 'JFC',TOTAL_COST, 0)) AS TOTAL_JFC",
				"SUM(IF(ACCOUNT = 'PY' AND STATE = 'Graduate',TOTAL_COST, 0)) AS PY_GRADUATE",
				"SUM(IF(ACCOUNT = 'CO' AND STATE = 'Graduate',TOTAL_COST, 0)) AS CO_GRADUATE",
				"SUM(IF(STATE = 'Graduate',TOTAL_COST, 0)) AS TOTAL_GRADUATE",
				"SUM(IF(ACCOUNT = 'PY' AND STATE = 'Undergraduate',TOTAL_COST, 0)) AS PY_UNDERGRADUATE",
				"SUM(IF(ACCOUNT = 'CO' AND STATE = 'Undergraduate',TOTAL_COST, 0)) AS CO_UNDERGRADUATE",
				"SUM(IF(STATE = 'Undergraduate',TOTAL_COST, 0)) AS TOTAL_UNDERGRADUATE",
				"SUM(IF(ACCOUNT = 'PY' AND STATE = 'Conference',TOTAL_COST, 0)) AS PY_CONFERENCE",
				"SUM(IF(ACCOUNT = 'CO' AND STATE = 'Conference',TOTAL_COST, 0)) AS CO_CONFERENCE",
				"SUM(IF(STATE = 'Conference',TOTAL_COST, 0)) AS TOTAL_CONFERENCE"
			),
			'conditions' => array('BILL_ID' => $id)
		));
		$this -> set('totals', $totals[0][0]);
		$this -> set('states', $this -> Line_Item -> query("SELECT DISTINCT STATE FROM LINE_ITEMS AS LineItem where STATE != 'Final'"));
	}

	public function add()
	{
		if ($this -> request -> is('post'))
		{
			$this -> Bill -> create();
			if ($this -> Bill -> saveAssociated($this -> request -> data))
			{
				$this -> Session -> setFlash('The bill has been saved.');
				//$this -> redirect(array('action' => 'index'));
			}
			else
			{
				$this -> Session -> setFlash('Unable to add bill.');
			}
		}
		// Set the organization drop down list for
		// creating a new bill
		$id = $this -> Session -> read('USER.ID');
		$this -> loadModel('Membership');
		$orgs[''] = 'Select Organization';
		$ids = $this -> Membership -> find('all', array(
			'fields' => array('Membership.ORG_ID'),
			'conditions' => array('Membership.USER_ID' => $id)
		));
		$this -> loadModel('Organization');
		$orgs['My Organizations'] = $this -> Organization -> find('list', array(
			'fields' => array('NAME'),
			'conditions' => array('Organization.ID' => Set::extract('/Membership/ORG_ID', $ids))
		));
		$na_id = key($this -> Organization -> find('list', array(
			'fields' => array('NAME'),
			'conditions' => array('NAME' => 'N/A')
		)));
		$orgs['My Organizations'][$na_id] = 'N/A';
		$orgs['All'] = $this -> Organization -> find('list', array('fields' => array('NAME')));
		$this -> set('organizations', $orgs);

		// Set the graduate authors drop down list
		// for creating a new bill
		$this -> loadModel('SgaPerson');
		$sga_graduate = $this -> SgaPerson -> find('list', array(
			'fields' => array('NAME_DEPARTMENT'),
			'conditions' => array(
				'STATUS' => 'Active',
				'HOUSE' => 'Graduate'
			),
			'recursive' => 0
		));
		$gradAuthors[''] = "Unknown";
		$gradAuthors['SGA'] = $sga_graduate;
		$this -> set('gradAuthors', $gradAuthors);
		$sga_undergraduate = $this -> SgaPerson -> find('list', array(
			'fields' => array('NAME_DEPARTMENT'),
			'conditions' => array(
				'STATUS' => 'Active',
				'HOUSE' => 'Undergraduate'
			),
			'recursive' => 0
		));
		$underAuthors[''] = "Unknown";
		$underAuthors['SGA'] = $sga_undergraduate;
		$this -> set('underAuthors', $underAuthors);
	}

	public function edit()
	{

	}

	public function my_bills($id = null)
	{
		$this -> index($id);
	}

}
?>
