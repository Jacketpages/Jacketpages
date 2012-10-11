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
		'Number',
		'Time'
	);

	public $components = array(
		'RequestHandler',
		'Session'
	);

	/**
	 * A table listing of bills. If given a user's id then displays all the bills for
	 * that user
	 * @param $id - a user's id
	 */
	public function index($letter = null, $id = null)
	{
		// Set page view permissions
		$this -> set('billExportPerm', $this -> Acl -> check('Role/' . $this -> Session -> read('USER.LEVEL'), 'billExportPerm'));

		// Writes the search keyword to the Session if the request is a POST
		if ($this -> request -> is('post'))
		{
			$this -> Session -> write('Search.keyword', $this -> request -> data['Bill']['keyword']);
		}
		// Deletes the search keyword if the letter is null and the request is not ajax
		else if (!$this -> RequestHandler -> isAjax())
		{
			$this -> Session -> delete('Search');
		}

		/* START CHECKBOX FILTER LOGIC*/
		// Check to see if the status Session variables are null
		// If they are null set them to 1.
		if ($this -> Session -> read($this -> name . '.On Agenda') == null)
		{
			$this -> Session -> write($this -> name . '.On Agenda', 1);
		}
		else if ($this -> data['Bill']['On Agenda'] != null)
		{
			$this -> Session -> write($this -> name . '.On Agenda', $this -> data['Bill']['On Agenda']);

		}

		if ($this -> Session -> read($this -> name . '.Awaiting Author') == null)
			$this -> Session -> write($this -> name . '.Awaiting Author', 1);
		else if ($this -> data['Bill']['Awaiting Author'] != null)
		{
			$this -> Session -> write($this -> name . '.Awaiting Author', $this -> data['Bill']['Awaiting Author']);

		}

		if ($this -> Session -> read($this -> name . '.Authored') == null)
			$this -> Session -> write($this -> name . '.Authored', 1);
		else if ($this -> data['Bill']['Authored'] != null)
		{
			$this -> Session -> write($this -> name . '.Authored', $this -> data['Bill']['Authored']);

		}

		if ($this -> Session -> read($this -> name . '.Passed') == null)
			$this -> Session -> write($this -> name . '.Passed', 1);
		else if ($this -> data['Bill']['Passed'] != null)
		{
			$this -> Session -> write($this -> name . '.Passed', $this -> data['Bill']['Passed']);

		}

		if ($this -> Session -> read($this -> name . '.Failed') == null)
			$this -> Session -> write($this -> name . '.Failed', 1);
		else if ($this -> data['Bill']['Authored'] != null)
		{
			$this -> Session -> write($this -> name . '.Failed', $this -> data['Bill']['Failed']);

		}

		if ($this -> Session -> read($this -> name . '.Archived') == null)
			$this -> Session -> write($this -> name . '.Archived', 1);
		else if ($this -> data['Bill']['Failed'] != null)
		{
			$this -> Session -> write($this -> name . '.Archived', $this -> data['Bill']['Archived']);

		}

		if ($this -> Session -> read($this -> name . '.Joint') == null)
			$this -> Session -> write($this -> name . '.Joint', 1);
		else if ($this -> data['Bill']['Joint'] != null)
		{
			$this -> Session -> write($this -> name . '.Joint', $this -> data['Bill']['Joint']);

		}

		if ($this -> Session -> read($this -> name . '.Conference') == null)
			$this -> Session -> write($this -> name . '.Conference', 1);
		else if ($this -> data['Bill']['Conference'] != null)
		{
			$this -> Session -> write($this -> name . '.Conference', $this -> data['Bill']['Conference']);

		}

		if ($this -> Session -> read($this -> name . '.Undergraduate') == null)
			$this -> Session -> write($this -> name . '.Undergraduate', 1);
		else if ($this -> data['Bill']['Undergraduate'] != null)
		{
			$this -> Session -> write($this -> name . '.Undergraduate', $this -> data['Bill']['Undergraduate']);

		}

		if ($this -> Session -> read($this -> name . '.Graduate') == null)
			$this -> Session -> write($this -> name . '.Graduate', 1);
		else if ($this -> data['Bill']['Graduate'] != null)
		{
			$this -> Session -> write($this -> name . '.Graduate', $this -> data['Bill']['Graduate']);

		}

		if ($this -> Session -> read($this -> name . '.On Agenda'))
			$statuses[] = 3;
		if ($this -> Session -> read($this -> name . '.Awaiting Author'))
			$statuses[] = 1;
		if ($this -> Session -> read($this -> name . '.Authored'))
			$statuses[] = 2;
		if ($this -> Session -> read($this -> name . '.Passed'))
			$statuses[] = 4;
		if ($this -> Session -> read($this -> name . '.Failed'))
			$statuses[] = 5;
		if ($this -> Session -> read($this -> name . '.Archived'))
			$statuses[] = 6;

		if ($this -> Session -> read($this -> name . '.Joint'))
			$categories[] = 'Joint';
		if ($this -> Session -> read($this -> name . '.Conference'))
			$categories[] = 'Conference';
		if ($this -> Session -> read($this -> name . '.Undergraduate'))
			$categories[] = 'Undergraduate';
		if ($this -> Session -> read($this -> name . '.Graduate'))
			$categories[] = 'Graduate';

		/* END CHECKBOX FILTER LOGIC*/

		$this -> paginate = array(
			'conditions' => array(
				'Bill.STATUS' => $statuses,
				'Bill.CATEGORY' => $categories,
				'OR' => array(
					array('Bill.TITLE LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%'),
					array('Bill.DESCRIPTION LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%'),
					array('Bill.NUMBER LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%')
				),
				array('Bill.TITLE LIKE' => $letter . '%')
			),
			'limit' => 20
		);

		// If given a user's id then filter to show only that user's bills
		if ($id != null)
		{
			$this -> set('bills', $this -> paginate('Bill', array('SUBMITTER' => $id)));
		}
		else
		{
			$this -> set('bills', $this -> paginate('Bill'));
		}
	}

	/**
	 * View an individual bill's information
	 * @param id - the bill's id
	 */
	public function view($id = null)
	{
		$this -> loadModel('BillAuthor');
		$this -> set('GradAuthor', $this -> BillAuthor -> find('first', array(
			'fields' => array('CONCAT(Users.FIRST_NAME," ", Users.LAST_NAME) as NAME'),
			'joins' => array(
				array(
					'table' => 'SGA_PEOPLE',
					'type' => 'inner',
					'foreignKey' => 'GRAD_AUTH_ID',
					'conditions' => array('BillAuthor.GRAD_AUTH_ID = SGA_PEOPLE.ID')
				),
				array(
					'table' => 'Users',
					'type' => 'left',
					'foreignKey' => false,
					'conditions' => array('SGA_PEOPLE.USER_ID = USERS.ID')
				)
			)
		)));
		$this -> set('UnderAuthor', $this -> BillAuthor -> find('first', array(
			'fields' => array('CONCAT(Users.FIRST_NAME," ", Users.LAST_NAME) as NAME'),
			'joins' => array(
				array(
					'table' => 'SGA_PEOPLE',
					'type' => 'inner',
					'foreignKey' => 'UNDR_AUTH_ID',
					'conditions' => array('BillAuthor.UNDR_AUTH_ID = SGA_PEOPLE.ID')
				),
				array(
					'table' => 'Users',
					'type' => 'left',
					'foreignKey' => false,
					'conditions' => array('SGA_PEOPLE.USER_ID = USERS.ID')
				)
			)
		)));

		// Set which bill to retrieve from the database.
		$this -> Bill -> id = $id;
		$this -> set('bill', $this -> Bill -> read());
		// Set the lineitem arrays for the different states to
		// pass to the view.
		$this -> loadModel('LineItem');
		$this -> set('submitted', $this -> LineItem -> find('all', array('conditions' => array(
				'BILL_ID' => $id,
				'STATE' => 'Submitted'
			))));
		$this -> set('jfc', $this -> LineItem -> find('all', array('conditions' => array(
				'BILL_ID' => $id,
				'STATE' => 'JFC'
			))));
		$this -> set('graduate', $this -> LineItem -> find('all', array('conditions' => array(
				'BILL_ID' => $id,
				'STATE' => 'Graduate'
			))));
		$this -> set('undergraduate', $this -> LineItem -> find('all', array('conditions' => array(
				'BILL_ID' => $id,
				'STATE' => 'Undergraduate'
			))));
		$this -> set('conference', $this -> LineItem -> find('all', array('conditions' => array(
				'BILL_ID' => $id,
				'STATE' => 'Conference'
			))));
		$this -> set('all', $this -> LineItem -> find('all', array(
			'conditions' => array('BILL_ID' => $id),
			'order' => array("FIELD(STATE, 'Submitted','JFC', 'Graduate', 'Undergraduate', 'Conference', 'Final')")
		)));
		$this -> set('final', $this -> LineItem -> find('all', array('conditions' => array(
				'BILL_ID' => $id,
				'STATE' => 'Final'
			))));
		// Set the amounts for prior year, capital outlay, and total
		$totals = $this -> LineItem -> find('all', array(
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
		/* Create an array of states to easily loop through and display the
		 * totals for the individual accounts
		 */
		$this -> set('states', $this -> LineItem -> query("SELECT DISTINCT STATE FROM LINE_ITEMS AS LineItem where STATE != 'Final'"));
	}

	function getValidNumber($category)
	{
		$number = $this -> getFiscalYear() + 1;
		if ($category == 'Undergraduate')
			$number .= 'U';
		if ($category == 'Graduate')
			$number .= 'G';
		if ($category == 'Joint')
			$number .= 'J';
		if ($category == 'Budget')
			$number .= 'B';
		$sql = "SELECT substr(number,4) as num FROM bills WHERE substr(number,1,3) = '$number' ORDER BY num DESC LIMIT 1";
		$num = $this -> Bill -> query($sql);
		if (empty($num))
		{
			$num = 1;
		}
		else
		{
			$num = $num[0][0]['num'] + 1;
		}
		$num = str_pad($num, 3, '0', STR_PAD_LEFT);
		$number .= $num;
		return $number;
	}

	/**
	 * Add a new bill
	 */
	public function add()
	{
		if ($this -> request -> is('post'))
		{
			$this -> Bill -> create();
			$this -> request -> data['Bill']['NUMBER'] = $this -> getValidNumber($this -> request -> data['Bill']['CATEGORY']);
			$this -> request -> data['Bill']['SUBMITTER'] = $this -> Session -> read('USER.ID');
			$this->Bill->set('LAST_MOD_DATE',date ('Y-m-d'));
			if ($this -> Bill -> saveAssociated($this -> request -> data))
			{
				$this -> Session -> setFlash('The bill has been saved.');
				$this -> redirect(array('action' => 'index'));
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

	/**
	 * Edit an existing bill
	 * @param id - the id of the bill to edit
	 */
	public function edit($id = null)
	{
		//TODO Implement
		$this->Bill->set('LAST_MOD_DATE',date ('Y-m-d'));
		$this -> Bill -> id = $id;
		if ($this -> request -> is('get'))
		{
			$this -> request -> data = $this -> Bill -> read();
			$this -> set('user', $this -> Bill -> read());
		}
		else
		{
			if ($this -> Bill -> save($this -> request -> data))
			{
				$this -> Session -> setFlash('The Bill has been saved.');
				$this -> redirect(array('action' => 'index'));
			}
			else
			{
				$this -> Session -> setFlash('Unable to edit the Bill.');
			}
		}
	}

	/**
	 * A table listing of bills for a specific user
	 * @param id - a user's id
	 */
	public function my_bills($letter = null, $id = null)
	{
		$this -> index($letter, $id);
	}

}
?>
