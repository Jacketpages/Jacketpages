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
	public function index($letter = null, $id = null, $onAgenda = null)
	{
		// Set page view permissions
		$this -> set('billExportPerm', $this -> Acl -> check('Role/' . $this -> Session -> read('User.level'), 'billExportPerm'));

		// Writes the search keyword to the Session if the request is a POST
		if ($this -> request -> is('post'))
		{
			$this -> Session -> write('Search.keyword', $this -> request -> data['Bill']['keyword']);
		}
		// Deletes the search keyword if the letter is null and the request is not ajax
		else
		if (!$this -> RequestHandler -> isAjax())
		{
			$this -> Session -> delete('Search');
		}
		/* START CHECKBOX FILTER LOGIC*/
		// Check to see if the status Session variables are null
		// If they are null set them to 1.
		if ($this -> Session -> read($this -> name) == null || $this -> Session -> read($this -> name . '.On Agenda') == null || $this -> data == null)
		{
			$this -> Session -> write($this -> name . '.On Agenda', 1);
			$this -> Session -> write($this -> name . '.Awaiting Author', 1);
			$this -> Session -> write($this -> name . '.Authored', 1);
			$this -> Session -> write($this -> name . '.Passed', 1);
			$this -> Session -> write($this -> name . '.Failed', 1);
			$this -> Session -> write($this -> name . '.Archived', 1);
			$this -> Session -> write($this -> name . '.Joint', 1);
			$this -> Session -> write($this -> name . '.Conference', 1);
			$this -> Session -> write($this -> name . '.Undergraduate', 1);
			$this -> Session -> write($this -> name . '.Graduate', 1);
		}
		else
		{
			$this -> Session -> write($this -> name . '.On Agenda', $this -> data['Bill']['On Agenda']);
			$this -> Session -> write($this -> name . '.Authored', $this -> data['Bill']['Authored']);
			$this -> Session -> write($this -> name . '.Awaiting Author', $this -> data['Bill']['Awaiting Author']);
			$this -> Session -> write($this -> name . '.Passed', $this -> data['Bill']['Passed']);
			$this -> Session -> write($this -> name . '.Failed', $this -> data['Bill']['Failed']);
			$this -> Session -> write($this -> name . '.Archived', $this -> data['Bill']['Archived']);
			$this -> Session -> write($this -> name . '.Joint', $this -> data['Bill']['Joint']);
			$this -> Session -> write($this -> name . '.Conference', $this -> data['Bill']['Conference']);
			$this -> Session -> write($this -> name . '.Undergraduate', $this -> data['Bill']['Undergraduate']);
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

		if ($onAgenda)
			$statuses = 4;

		/* END CHECKBOX FILTER LOGIC*/

		$this -> paginate = array(
			'conditions' => array(
				'Bill.status' => $statuses,
				'Bill.category' => $categories,
				'OR' => array(
					array('Bill.title LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%'),
					array('Bill.description LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%'),
					array('Bill.number LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%')
				),
				array('Bill.title LIKE' => $letter . '%')
			),
			'limit' => 20
		);

		// If given a user's id then filter to show only that user's bills
		if ($id != null)
		{
			$this -> set('bills', $this -> paginate('Bill', array('submitter' => $id)));
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
		// Set which bill to retrieve from the database.
		$this -> Bill -> id = $id;
		$bill = $this -> Bill -> read();
		$this -> setAuthorNames($bill['Authors']['grad_auth_id'], $bill['Authors']['undr_auth_id']);
		$this -> set('bill', $bill);
		// Set the lineitem arrays for the different states to
		// pass to the view.
		$this -> loadModel('LineItem');
		$this -> set('submitted', $this -> LineItem -> find('all', array('conditions' => array(
				'bill_id' => $id,
				'STATE' => 'Submitted'
			))));
		$this -> set('jfc', $this -> LineItem -> find('all', array('conditions' => array(
				'bill_id' => $id,
				'STATE' => 'JFC'
			))));
		$this -> set('graduate', $this -> LineItem -> find('all', array('conditions' => array(
				'bill_id' => $id,
				'STATE' => 'Graduate'
			))));
		$this -> set('undergraduate', $this -> LineItem -> find('all', array('conditions' => array(
				'bill_id' => $id,
				'STATE' => 'Undergraduate'
			))));
		$this -> set('conference', $this -> LineItem -> find('all', array('conditions' => array(
				'bill_id' => $id,
				'STATE' => 'Conference'
			))));
		$this -> set('all', $this -> LineItem -> find('all', array(
			'conditions' => array('bill_id' => $id),
			'order' => array("FIELD(STATE, 'Submitted','JFC', 'Graduate', 'Undergraduate', 'Conference', 'Final')")
		)));
		$this -> set('final', $this -> LineItem -> find('all', array('conditions' => array(
				'bill_id' => $id,
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
			'conditions' => array('bill_id' => $id)
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
			//TODO There is a built in method for below instead of doing "=" replace later
			$this -> request -> data['Bill']['submitter'] = $this -> Session -> read('User.id');
			$this -> request -> data['Bill']['last_mod_by'] = $this -> Session -> read('User.id');
			$this -> request -> data['Bill']['status'] = 1;
			$this -> request -> data['Bill']['submit_date'] = date('Y-m-d');
			$this -> request -> data['Bill']['last_mod_date'] = date('Y-m-d h:i:s');
			// If Graduate author or Undergraduate author are set as Unknown
			// then set them to a place holder author.
			if ($this -> request -> data['Authors']['grad_auth_id'] == null)
			{
				$this -> request -> data['Authors']['grad_auth_id'] = 1;
			}
			if ($this -> request -> data['Authors']['undr_auth_id'] == null)
			{
				$this -> request -> data['Authors']['undr_auth_id'] = 1;
			}

			if ($this -> Bill -> saveAssociated($this -> request -> data))
			{
				$this -> Session -> setFlash('The bill has been saved.');
				$bill = $this -> Bill -> find('first', array(
					'conditions' => array('submitter' => $this -> Session -> read('User.id')),
					'order' => 'Bill.id DESC'
				));
				$this -> redirect(array(
					'action' => 'view',
					$bill['Bill']['id']
				));
			}
			else
			{
				$this -> Session -> setFlash('Unable to add bill.');
			}
		}

		$this -> setOrganizationNames();
		// Set the graduate authors drop down list
		// for creating a new bill
		$this -> loadModel('SgaPerson');
		$sga_graduate = $this -> SgaPerson -> find('list', array(
			'fields' => array('name_department'),
			'conditions' => array(
				'status' => 'Active',
				'house' => 'Graduate'
			),
			'recursive' => 0
		));
		$gradAuthors[''] = "Unknown";
		$gradAuthors['SGA'] = $sga_graduate;
		$this -> set('gradAuthors', $gradAuthors);
		$sga_undergraduate = $this -> SgaPerson -> find('list', array(
			'fields' => array('name_department'),
			'conditions' => array(
				'status' => 'Active',
				'house' => 'Undergraduate'
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
	public function edit_index($id = null)
	{
		$this -> Bill -> id = $id;
		$this -> Bill -> set('last_mod_date', date('Y-m-d h:i:s'));
		$this -> Bill -> set('last_mod_by', $this -> Session -> read('User.id'));
		$this -> setOrganizationNames();
		debug($this -> request -> data);
		if ($this -> request -> is('get'))
		{
			$this -> Bill -> id = $id;
			$this -> request -> data = $this -> Bill -> read();
			$bill = $this -> Bill -> read();
			$this -> setAuthorNames($bill['Authors']['grad_auth_id'], $bill['Authors']['undr_auth_id']);
			$this -> set('bill', $this -> Bill -> read());
		}
		else
		{
			$this -> loadModel('BillAuthor');
			$this -> request -> data['Authors']['id'] = $this -> request -> data['Bill']['auth_id'];

			if ($this -> Bill -> saveAssociated($this -> request -> data, array('deep' => true)))
			{
				$savedBill = $this -> Bill -> findById($id);
				if ($savedBill['Authors']['grad_auth_appr'] && $savedBill['Authors']['undr_auth_appr'] && $savedBill['Bill']['status'] == 1)
				{
					$savedBill['Bill']['status'] = 2;
				}
				$this -> Bill -> save($savedBill);
				$this -> Session -> setFlash('The Bill has been saved.');
				$this -> redirect(array('action' => 'index'));

			}
			else
			{
				$this -> Session -> setFlash('Unable to edit the Bill.');
			}
		}
	}

	public function delete($id = null)
	{
		if (!$id)
		{
			$this -> Session -> setFlash(__('Invalid ID for document', true));
			$this -> redirect(array('action' => 'index'));
		}
		
		if ($this -> Bill -> delete($id, true))
		{
			$this -> Session -> setFlash(__('Bill deleted.', true));
			$this -> redirect(array('controller' => 'bills', 'action' => 'index', $org_id));
		}
		$this -> Session -> setFlash(__('Bill was not deleted.', true));
		$this -> redirect(array('controller' => 'bills','action' => 'index'));
	}

	/**
	 * A table listing of bills for a specific user
	 * @param id - a user's id
	 */
	public function my_bills($letter = null)
	{
		$this -> index($letter, $this -> Session -> read('User.id'));
	}

	private function setOrganizationNames()
	{
		// Set the organization drop down list for
		// creating a new bill
		$id = $this -> Session -> read('User.id');
		$this -> loadModel('Membership');
		$orgs[''] = 'Select Organization';
		$ids = $this -> Membership -> find('all', array(
			'fields' => array('Membership.org_id'),
			'conditions' => array('Membership.user_id' => $id)
		));
		$this -> loadModel('Organization');
		$orgs['My Organizations'] = $this -> Organization -> find('list', array(
			'fields' => array('name'),
			'conditions' => array('Organization.id' => Set::extract('/Membership/org_id', $ids))
		));
		$na_id = key($this -> Organization -> find('list', array(
			'fields' => array('name'),
			'conditions' => array('name' => 'N/A')
		)));
		$orgs['My Organizations'][$na_id] = 'N/A';
		$orgs['All'] = $this -> Organization -> find('list', array('fields' => array('name')));
		$this -> set('organizations', $orgs);
	}

	private function setAuthorNames($grad_id, $undr_id)
	{
		$this -> loadModel('BillAuthor');
		$this -> loadModel('User');
		$this -> set('GradAuthor', $this -> User -> find('first', array(
			'fields' => array('name'),
			'conditions' => array('User.sga_id' => $grad_id)
		)));

		$this -> set('UnderAuthor', $this -> User -> find('first', array(
			'fields' => array('name'),
			'conditions' => array('User.sga_id' => $undr_id)
		)));

	}

	public function onAgenda($letter = null, $id = null)
	{
		$this -> Session -> write($this -> name . '.Awaiting Author', 0);
		$this -> Session -> write($this -> name . '.Authored', 0);
		$this -> Session -> write($this -> name . '.Passed', 0);
		$this -> Session -> write($this -> name . '.Failed', 0);
		$this -> Session -> write($this -> name . '.Archived', 0);
		$this -> index($letter, $id, true);
	}

	public function submit($id)
	{
		$this -> setBillStatus($id,2,true);
	}

	public function general_info()
	{
		$this -> setOrganizationNames();
	}

	public function authors_signatures($id)
	{
		$this -> loadModel('BillAuthor');
		$bill_authors = $this -> BillAuthor -> findById($id);
		$this -> BillAuthor -> id = $id;
		$bill = $this -> Bill -> findByAuthId($id, array('id'));
		debug($bill);
		if ($this -> request -> is('get'))
		{
			$this -> request -> data = $this -> BillAuthor -> read();
			$this -> set('membership', $this -> BillAuthor -> read(null, $id));
		}
		else
		{
			if ($this -> BillAuthor -> save($this -> request -> data))
			{
				// After the bill is saved check whether both authors have signed it and send it
				// to the 'Authored' state.
				$fields = array(
					'grad_auth_appr',
					'undr_auth_appr'
				);
				$authors = $this -> BillAuthor -> findById($id, $fields);
				if ($authors['BillAuthor']['grad_auth_appr'] && $authors['BillAuthor']['undr_auth_appr'])
				{
					$bill = $this -> Bill -> findByAuthId($id, array('id'));
					$this -> setBillStatus($bill['Bill']['id'],3, $bill['Bill']['category']);
				}
				$this -> Session -> setFlash('The membership has been saved.');
				//$this -> redirect(array('controller' => 'bills', 'action' => 'view',));
			}
			else
			{
				$this -> Session -> setFlash('Unable to edit the membership.');
			}
		}
		$this -> setAuthorNames($bill_authors['BillAuthor']['grad_auth_id'], $bill_authors['BillAuthor']['undr_auth_id']);
		$this -> set('authors', $bill_authors);
	}

	public function votes($id)
	{
		// Find out if the person has voted.
		// If not then be sure to display a vote option

	}

	public function putOnAgenda($id)
	{
		$this -> setBillStatus($id,4,true);
	}

	private function setBillStatus($id, $state, $redirect = false, $category = null)
	{
		//@formatter:off
		if($id != null && in_array($state, array(1,2,3,4,5,6,7)))//@formatter:on
		{
			$this -> Bill -> id = $id;
			$this -> Bill -> saveField('status', "$state");
			if($state == 3 && $category != null)
			{
				$this -> Bill -> saveField('number', $this -> getValidNumber($category));
			}
			if ($redirect)
			{
				$this -> redirect(array(
					'action' => 'view',
					$id
				));
			}
		}
	}

}
?>
