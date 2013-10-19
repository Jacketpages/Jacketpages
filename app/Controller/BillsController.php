<?php
/**
 * @author Stephen Roca
 * @since 06/26/2012
 */
App::uses('CakeEmail', 'Network/Email');

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

		if ($this -> Session -> read('Bill.from') == null)
		{
			$this -> request -> data = array('Bill' => array(
					'from' => 1,
					'to' => 7,
					'category' => 'All'
				));
		}
		// Writes the search keyword to the Session if the request is a POST
		if ($this -> request -> is('post') && !$this -> request -> is('ajax'))
		{
			$this -> Session -> write('Search.keyword', $this -> request -> data['Bill']['keyword']);
		}
		// Deletes the search keyword if the letter is null and the request is not ajax
		else if (!$this -> request -> is('ajax') && $letter == null)
		{
			$this -> Session -> delete('Search');
		}
		if ($this -> request -> is('ajax'))
		{
			$this -> layout = 'list';
		}

		if (isset($this -> request -> data['Bill']))
		{
			if (isset($this -> request -> data['Bill']['from']) && $this -> request -> data['Bill']['to'])
			{
				$this -> Session -> write('Bill.from', $this -> data['Bill']['from']);
				$this -> Session -> write('Bill.to', $this -> data['Bill']['to']);
			}
			if (isset($this -> data['Bill']['category']))
			{
				$this -> Session -> write('Bill.category', $this -> data['Bill']['category']);
			}
		}

		if (strcmp($this -> Session -> read('Bill.category'), 'All') == 0)
		{
			$categories = array(
				'Joint',
				'Undergraduate',
				'Graduate',
				'Conference'
			);
		}
		else
		{
			$categories = array($this -> Session -> read('Bill.category'));
		}
		$keyword = $this -> Session -> read('Search.keyword');
		
		if ($id == null)
		{
			$this -> loadModel('User');
			$authorId = Hash::extract($this -> User -> findByName($keyword), 'User.sga_id');
		}
		
		$this -> paginate = array(
			'conditions' => array(
				'Bill.status BETWEEN ? AND ?' => array(
					$this -> Session -> read('Bill.from'),
					$this -> Session -> read('Bill.to')
				),
				'Bill.category' => $categories,
				'OR' => array(
					array('Bill.title LIKE' => "%$keyword%"),
					array('Bill.description LIKE' => "%$keyword%"),
					array('Bill.number LIKE' => "%$keyword%"),
					array('(CONCAT(first_name, " ", last_name)) LIKE' => "%$keyword%"),
					//array('Authors.grad_auth_id' => $authorId),
					//array('Authors.undr_auth_id' => $authorId)
				),
				array('Bill.title LIKE' => $letter . '%')
			),
			'order' => 'submit_date desc',
			'limit' => 20
		);
		// If given a user's id then filter to show only that user's bills
		if ($id != null)
		{
			$this -> set('bills', $this -> paginate('Bill',
				// seperate this group of conditions from the previous
				array('AND' => array(
					// OR these together
					array('OR' => array(
						array('submitter' => $id),
						array('Authors.grad_auth_id' => $id),
						array('Authors.undr_auth_id' => $id)
						)
					)
				))));
		}
		else
		{
			$this -> set('bills', $this -> paginate('Bill'));
		}
	}

	/**
	 * Finance ledger showing all bills and budgets
	 */
	public function ledger($org_id = null)
	{
		$this -> paginate = array(
			'conditions' => array('Bill.org_id' => $org_id),
			'limit' => 20,
			'order' => 'submit_date desc'
		);
		$this -> set('bills', $this -> paginate('Bill'));
		$this -> loadModel('Organization');
		$org_name = $this -> Organization -> field('name', array('id' => $org_id));
		$this -> set('org_id', $org_id);
		$this -> set('org_name', $org_name);
	}

	/**
	 * View an individual bill's information
	 * @param id - the bill's id
	 */
	public function view($id = null)
	{
		if ($id == null)
		{
			$this -> Session -> setFlash('Please select a bill to view.');
			$this -> redirect(array(
				'controller' => 'bills',
				'action' => 'index'
			));
		}

		// Set which bill to retrieve from the database.
		$this -> Bill -> id = $id;
		$level = $this -> Session -> read('User.level') != "" ? $this -> Session -> read('User.level') : "general";
		$bill = $this -> Bill -> read();
		switch ($bill['Bill']['status'])
		{
			case $this -> CREATED :
				if (!($this -> isSubmitter($id) || $this -> isSGAExec()))
					$this -> redirect($this -> referer());
				break;
			case $this -> AWAITING_AUTHOR :
				if (!($this -> isAuthor($id) || $this -> isSubmitter($id) || $this -> isSGA()))
					$this -> redirect($this -> referer());
				break;
		}
		$this -> setAuthorNames($bill['Authors']['grad_auth_id'], $bill['Authors']['undr_auth_id']);
		$this -> setSignatureNames($bill['Authors']);
		$this -> set('bill', $bill);
		// Set the lineitem arrays for the different states to
		// pass to the view.
		$this -> loadModel('LineItem');
		$this -> set('submitted', $this -> LineItem -> find('all', array(
			'conditions' => array(
				'bill_id' => $id,
				'state' => 'Submitted'
			),
			'order' => 'line_number asc'
		)));
		$this -> set('jfc', $this -> LineItem -> find('all', array(
			'conditions' => array(
				'bill_id' => $id,
				'state' => 'JFC'
			),
			'order' => 'line_number asc'
		)));
		$this -> set('graduate', $this -> LineItem -> find('all', array(
			'conditions' => array(
				'bill_id' => $id,
				'state' => 'Graduate'
			),
			'order' => 'line_number asc'
		)));
		$this -> set('undergraduate', $this -> LineItem -> find('all', array(
			'conditions' => array(
				'bill_id' => $id,
				'state' => 'Undergraduate'
			),
			'order' => 'line_number asc'
		)));
		$this -> set('conference', $this -> LineItem -> find('all', array(
			'conditions' => array(
				'bill_id' => $id,
				'state' => 'Conference'
			),
			'order' => 'line_number asc'
		)));
		$this -> set('all', $this -> LineItem -> find('all', array(
			'conditions' => array('bill_id' => $id),
			'order' => array("FIELD(STATE, 'Submitted','JFC', 'Graduate', 'Undergraduate', 'Conference', 'Final')")
		)));
		$this -> set('final', $this -> LineItem -> find('all', array(
			'conditions' => array(
				'bill_id' => $id,
				'state' => 'Final'
			),
			'order' => 'line_number asc'
		)));
		// Set the amounts for prior year, capital outlay, and total
		$totals = $this -> LineItem -> find('all', array(
			'fields' => array(
				"SUM(IF(account = 'PY' AND state = 'Submitted',amount, 0)) AS PY_SUBMITTED",
				"SUM(IF(ACCOUNT = 'CO' AND STATE = 'Submitted',amount, 0)) AS CO_SUBMITTED",
				"SUM(IF(STATE = 'Submitted',amount, 0)) AS TOTAL_SUBMITTED",
				"SUM(IF(ACCOUNT = 'PY' AND STATE = 'JFC',amount, 0)) AS PY_JFC",
				"SUM(IF(ACCOUNT = 'CO' AND STATE = 'JFC',amount, 0)) AS CO_JFC",
				"SUM(IF(STATE = 'JFC',amount, 0)) AS TOTAL_JFC",
				"SUM(IF(ACCOUNT = 'PY' AND STATE = 'Graduate',amount, 0)) AS PY_GRADUATE",
				"SUM(IF(ACCOUNT = 'CO' AND STATE = 'Graduate',amount, 0)) AS CO_GRADUATE",
				"SUM(IF(STATE = 'Graduate',amount, 0)) AS TOTAL_GRADUATE",
				"SUM(IF(ACCOUNT = 'PY' AND STATE = 'Undergraduate',amount, 0)) AS PY_UNDERGRADUATE",
				"SUM(IF(ACCOUNT = 'CO' AND STATE = 'Undergraduate',amount, 0)) AS CO_UNDERGRADUATE",
				"SUM(IF(STATE = 'Undergraduate',amount, 0)) AS TOTAL_UNDERGRADUATE",
				"SUM(IF(ACCOUNT = 'PY' AND STATE = 'Final',amount, 0)) AS PY_FINAL",
				"SUM(IF(ACCOUNT = 'CO' AND STATE = 'Final',amount, 0)) AS CO_FINAL",
				"SUM(IF(STATE = 'Final',amount, 0)) AS TOTAL_FINAL",
				"SUM(IF(ACCOUNT = 'PY' AND STATE = 'Conference',amount, 0)) AS PY_CONFERENCE",
				"SUM(IF(ACCOUNT = 'CO' AND STATE = 'Conference',amount, 0)) AS CO_CONFERENCE",
				"SUM(IF(STATE = 'Conference',amount, 0)) AS TOTAL_CONFERENCE"
			),
			'conditions' => array(
				'bill_id' => $id,
				'struck <>' => 1
			)
		));
		$this -> set('totals', $totals[0][0]);
		/* Create an array of states to easily loop through and display the
		 * totals for the individual accounts
		 */
		$this -> set('states', $this -> LineItem -> find('all', array(
			'fields' => array('DISTINCT state'),
			'conditions' => array('state !=' => 'Final')
		)));
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
			if ($this -> request -> data['Authors']['grad_auth_id'] == null || $this -> request -> data['Bill']['category'] == 'Undergraduate')
			{
				$this -> request -> data['Authors']['grad_auth_id'] = 0;
			}
			if ($this -> request -> data['Authors']['undr_auth_id'] == null || $this -> request -> data['Bill']['category'] == 'Graduate')
			{
				$this -> request -> data['Authors']['undr_auth_id'] = 0;
			}
			$this -> request -> data['Authors']['category'] = $this -> request -> data['Bill']['category'];

			if ($this -> Bill -> saveAll($this -> request -> data, array('validate' => 'only')))
			{
				$this -> Bill -> saveAssociated($this -> request -> data, array('validate' => 'false'));
				$this -> Session -> setFlash('The bill has been saved.');
				$bill = $this -> Bill -> find('first', array(
					'conditions' => array('submitter' => $this -> Session -> read('User.id')),
					'order' => 'Bill.id DESC'
				));
				if ($this -> request -> data[0]['LineItem']['name'] != "")
					$this -> requestAction('/line_items/index/' . $bill['Bill']['id'] . '/Submitted', array('data' => $this -> removeBillInformation($this -> request -> data)));
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
				'SgaPerson.status' => 'Active',
				'house' => 'Graduate'
			),
			'recursive' => 0
		));
		//$gradAuthors[''] = "Unknown";
		$gradAuthors['SGA'] = $sga_graduate;
		$this -> set('gradAuthors', $gradAuthors);
		$sga_undergraduate = $this -> SgaPerson -> find('list', array(
			'fields' => array('name_department'),
			'conditions' => array(
				'SgaPerson.status' => 'Active',
				'house' => 'Undergraduate'
			),
			'recursive' => 0
		));
		//$underAuthors[''] = "Unknown";
		$underAuthors['SGA'] = $sga_undergraduate;
		$this -> set('underAuthors', $underAuthors);
	}

	private function removeBillInformation($data)
	{
		unset($data['Bill']);
		unset($data['Authors']);
		return $data;
	}

	/**
	 * Edit an existing bill
	 * @param id - the id of the bill to edit
	 */
	public function edit_index($id = null)
	{
		if ($id == null)
		{
			$this -> Session -> setFlash('Please select a bill to view.');
			$this -> redirect(array(
				'controller' => 'bills',
				'action' => 'index'
			));
		}

		$this -> Bill -> id = $id;
		$bill = $this -> Bill -> read();
		switch ($bill['Bill']['status'])
		{
			case $this -> CREATED :
				if (!($this -> isSubmitter($id) || $this -> isSGAExec()))
					$this -> redirect($this -> referer());
				break;
			case $this -> AWAITING_AUTHOR :
				if (!($this -> isAuthor($id) || $this -> isSGAExec()))
					$this -> redirect($this -> referer());
				break;
			case $this -> AUTHORED :
			case $this -> AGENDA :
			case $this -> PASSED :
			case $this -> FAILED :
			case $this -> TABLED :
			case $this -> CONFERENCE :
				if (!$this -> isSGAExec())
					$this -> redirect($this -> referer());
				break;
		}
		$this -> Bill -> id = $id;
		$this -> Bill -> set('last_mod_date', date('Y-m-d h:i:s'));
		$this -> Bill -> set('last_mod_by', $this -> Session -> read('User.id'));
		$this -> setOrganizationNames();
		if ($this -> request -> is('get'))
		{
			$this -> Bill -> id = $id;
			$this -> request -> data = $bill = $this -> Bill -> read();
			$this -> setAuthorNames($bill['Authors']['grad_auth_id'], $bill['Authors']['undr_auth_id']);
			$this -> set('bill', $this -> Bill -> read());
			// Set the graduate authors drop down list
			// for creating a new bill
			$this -> loadModel('SgaPerson');
			$sga_graduate = $this -> SgaPerson -> find('list', array(
				'fields' => array('name_department'),
				'conditions' => array(
					'SgaPerson.status' => 'Active',
					'house' => 'Graduate'
				),
				'recursive' => 0
			));
			//$gradAuthors[''] = "Unknown";
			$gradAuthors['SGA'] = $sga_graduate;
			$this -> set('gradAuthors', $gradAuthors);
			$sga_undergraduate = $this -> SgaPerson -> find('list', array(
				'fields' => array('name_department'),
				'conditions' => array(
					'SgaPerson.status' => 'Active',
					'house' => 'Undergraduate'
				),
				'recursive' => 0
			));
			//$underAuthors[''] = "Unknown";
			$underAuthors['SGA'] = $sga_undergraduate;
			$this -> set('underAuthors', $underAuthors);
		}
		else
		{
			// MRE removed this check because it breaks everything
			if (/*$this -> validateStatusAndSignatures($this -> request -> data, $id)*/1)
			{
				// If Graduate author or Undergraduate author are set as Unknown
				// then set them to a place holder author.
				if ($this -> request -> data['Authors']['grad_auth_id'] == null || $this -> request -> data['Bill']['category'] == 'Undergraduate')
				{
					$this -> request -> data['Authors']['grad_auth_id'] = 0;
				}
				if ($this -> request -> data['Authors']['undr_auth_id'] == null || $this -> request -> data['Bill']['category'] == 'Graduate')
				{
					$this -> request -> data['Authors']['undr_auth_id'] = 0;
				}
				if ($this -> Bill -> saveAssociated($this -> request -> data, array('deep' => true)))
				{
					$this -> Session -> setFlash('The bill has been saved.');
				}
				else
				{
					$this -> Session -> setFlash('Unable to save the bill.');
				}

				$this -> redirect(array(
					'action' => 'view',
					$id
				));
			}
		}
	}

	//TODO Add Signature check
	private function validateStatusAndSignatures($data, $id)
	{
		$valid = true;
		if ($data['Bill']['status'] != 4)
		{
			$this -> loadModel('LineItem');
			$hasLineItemsInFinalState = $this -> LineItem -> find('count', array('conditions' => array(
					'state' => 'Final',
					'bill_id' => $id
				)));
			$hasLineItemsInGradOrUndrState = $this -> LineItem -> find('count', array('conditions' => array(
					'state' => array(
						'Undergraduate',
						'Graduate'
					),
					'bill_id' => $id
				)));
			$hasLineItemsInJFCState = $this -> LineItem -> find('count', array('conditions' => array(
					'state' => 'JFC',
					'bill_id' => $id
				)));
			if (!$hasLineItemsInFinalState || !$hasLineItemsInJFCState || !$hasLineItemsInGradOrUndrState)
			{
				$valid = false;
			}
			if (!$hasLineItemsInFinalState)
				$this -> Session -> setFlash('There are no line items in the Final tab.');
			else if (!$hasLineItemsInJFCState)
				$this -> Session -> setFlash('There are no line items in the JFC tab.');
			else if (!$hasLineItemsInGradOrUndrState)
				$this -> Session -> setFlash('There are no line items in the Undergraduate or Graduate tabs.');

			$signatures = array(
				'grad_pres_id',
				'grad_secr_id',
				'undr_pres_id',
				'undr_secr_id',
				'vp_fina_id'
			);
			if ($valid)
			{
				foreach ($signatures as $signature)
				{
					if (!$data['Authors'][$signature])
						$valid = false;
				}
				if (!$valid)
					$this -> Session -> setFlash("The bill is lacking one or more signatures.");
			}
		}
		return $valid;
	}

	public function delete($id = null)
	{
		$state = $this -> Bill -> field('status', array('id' => $id));
		switch ($state)
		{
			case $this -> CREATED :
				if (!($this -> isSubmitter($id) || $this -> isSGAExec()))
					$this -> redirectHome();
				break;
			case $this -> AWAITING_AUTHOR :
			case $this -> AUTHORED :
				if (!($this -> isAuthor($id) || $this -> isSGAExec()))
					$this -> redirectHome();
				break;
			case $this -> AGENDA :
			case $this -> PASSED :
			case $this -> FAILED :
			case $this -> TABLED :
				if ($this -> Session -> read('User.level') != 'admin')
					$this -> redirectHome();
				break;
		}
		if ($this -> Bill -> exists($id))
		{
			$this -> Bill -> id = $id;
			$bill = $this -> Bill -> read();
			if ($bill['Bill']['status'] < $this -> AGENDA)
			{
				if ($this -> Bill -> delete($id, true))
				{
					$this -> Session -> setFlash(__('Bill deleted.', true));
					$this -> redirect(array(
						'controller' => 'bills',
						'action' => 'index'
					));
				}
				else
				{
					$this -> Session -> setFlash(__('Unable to delete bill.', true));
					$this -> redirect(array(
						'controller' => 'bills',
						'action' => 'index'
					));
				}
			}
			else
			{
				$this -> Bill -> saveField('status', $this -> TABLED);
				$this -> redirect(array('action' => 'index'));
			}
		}
		else
		{
			$this -> Session -> setFlash(__('This bill does not exist.', true));
			$this -> redirect(array('action' => 'index'));
		}

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
		$gradAuthor = $this -> User -> find('first', array(
			'fields' => array(
				'name',
				'email'
			),
			'conditions' => array('User.sga_id' => $grad_id)
		));
		if ($gradAuthor == null)
		{
			$gradAuthor = array("User" => array("name" => "Awaiting Author Selection"));
		}
		$undrAuthor = $this -> User -> find('first', array(
			'fields' => array(
				'name',
				'email'
			),
			'conditions' => array('User.sga_id' => $undr_id)
		));
		if ($undrAuthor == null)
		{
			$undrAuthor = array("User" => array("name" => "Awaiting Author Selection"));
		}
		$this -> set('GradAuthor', $gradAuthor);

		$this -> set('UnderAuthor', $undrAuthor);

	}

	public function onAgenda($letter = null, $id = null)
	{
		$this -> request -> data['Bill']['from'] = 4;
		$this -> request -> data['Bill']['to'] = 4;
		$this -> index($letter, $id, true);
	}

	// TODO Add email functionality to email authors.
	public function submit($id)
	{
		$submitter_id = $this -> Bill -> field('submitter', array('id' => $id));
		if ($this -> Session -> read('User.id') == $submitter_id || $this -> isSGAExec())
		{
			$this -> Session -> setFlash('The bill has been submitted to the authors.');
			$this -> updateBillOwners($id);
			$this -> setBillStatus($id, 2, true);
		}
		$this -> redirect($this -> referer());
	}

	public function general_info($bill_id = null)
	{
		$this -> edit_index($bill_id);
	}

	public function authors_signatures($id = null)
	{
		if ($id == null)
		{
			$this -> Session -> setFlash('Please select a bill to view.');
			$this -> redirect(array(
				'controller' => 'bills',
				'action' => 'index'
			));
		}

		$this -> loadModel('BillAuthor');
		$bill_authors = $this -> BillAuthor -> findById($id);
		$this -> BillAuthor -> id = $id;
		$bill = $this -> Bill -> findByAuthId($id, array('id'));

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
					$this -> setBillStatus($bill['Bill']['id'], 3, $bill['Bill']['category']);
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

	public function votes($bill_id = null, $organization = null, $votes_id = null)
	{
		$state = $this -> Bill -> field('status', array('id' => $bill_id));
		$this -> set('bill_id', $bill_id);
		if ($this -> isSGAExec() && $state >= $this -> AGENDA)
		{
			{
				$this -> Session -> setFlash('Please select a bill to view.');
				$this -> redirect(array(
					'controller' => 'bills',
					'action' => 'index'
				));
			}


			$this -> loadModel('BillVotes');
			if ($this -> request -> is('get'))
			{
				$this -> BillVotes -> id = $votes_id;
				$this -> request -> data = $this -> BillVotes -> read();
			}
			else
			{
				if ($votes_id == null)
				{
					$this -> BillVotes -> create();
					$this -> BillVotes -> set('date', date('Y-m-d'));
					if ($this -> BillVotes -> save($this -> request -> data))
					{
						$this -> Bill -> id = $bill_id;
						if ($this -> Bill -> saveField($organization, $this -> BillVotes -> getInsertID()))
						{
							$this -> Session -> setFlash('Votes have been updated.');
						}
						else
						{
							$this -> Session -> setFlash('There was an error updating the votes.');
						}
						$this -> redirect(array(
							'controller' => 'bills',
							'action' => 'view',
							$bill_id
						));
					}
				}
				else
				{
					$this -> BillVotes -> id = $votes_id;
					if ($this -> BillVotes -> save($this -> request -> data))
					{
						$this -> Session -> setFlash('Votes have been updated.');
					}
					else
					{
						$this -> Session -> setFlash('There was an error updating the votes.');
					}
					$this -> redirect(array(
						'controller' => 'bills',
						'action' => 'view',
						$bill_id
					));
				}
			}
		}
		else
		{
			$this -> redirect(array(
				'controller' => 'bills',
				'action' => 'view',
				$bill_id
			));
		}
	}

	public function putOnAgenda($id)
	{
		$bill = $this -> Bill -> findById($id);
		if ($bill['Bill']['status'] == $this -> AUTHORED && $this -> isSGAExec())
		{
			$this -> setBillStatus($id, $this -> AGENDA, true, $bill['Bill']['category']);
		}
		else
		{
			$this -> redirect(array(
				'action' => 'view',
				$id
			));
		}

	}

	private function setBillStatus($id, $state, $redirect = false, $category = null)
	{
		//@formatter:off
		if ($id != null && in_array($state, array(1,2,3,4,5,6,7,8)))//@formatter:on
		{
			$this -> Bill -> id = $id;
			$this -> Bill -> saveField('status', $state);
			if ($state == $this -> AGENDA && $category != null)
			{
				if (!$this -> Bill -> saveField('number', $this -> getValidNumber($category)))
				{
					$this -> Session -> setFlash('There was an error updating the bill.');
				}
			}
			if ($redirect)
			{
				$this -> redirect(array(
					'action' => 'view',
					$id
				));
			}
		}
		else
		{
			$this -> log("Bill id null or bill state incorrect. Bill id: $id Bill state: $state", 'debug');
		}
	}

	public function email()
	{
		$email = new CakeEmail();
		$email -> config('default');
		$email -> from(array('gtsgacampus@gmail.com' => 'JacketPages'));
		$email -> template('removedFromOrg');
		$email -> emailFormat('html');
		$email -> to('gimpyroca@gmail.com');
		$email -> subject('Subject');
		$email -> send();
	}

	// TODO decide whether this method is unnecessary and if so delete it.
	public function process($id = null)
	{
		//Decide status based on number of votes
		//Check whether the final lineitems tab is populated.
		$this -> Bill -> id = $id;
		$this -> loadModel('LineItem');
		$finalLineItems = $this -> LineItem -> find('count', array('conditions' => array(
				'state' => 'Final',
				'bill_id' => $id
			)));
		if ($finalLineItems == null)
		{
			//Update the bill status
		}
		else
		{
			$this -> Session -> flash('Messed up.');
		}
	}

	public function authorSign($bill_id, $field, $value)
	{
		$state = $this -> Bill -> field('status', array('id' => $bill_id));
		if ($this -> isAuthor($bill_id) && ($state == $this -> AWAITING_AUTHOR || $state == $this -> AUTHORED))
		{
			$auth_id = $this -> getAuthorIdFromBillId($bill_id);
			$this -> BillAuthor -> id = $auth_id;
			$this -> BillAuthor -> saveField($field, $value);
			$authors = $this -> BillAuthor -> findById($auth_id, array(
				'undr_auth_appr',
				'grad_auth_appr'
			));
			$this -> Bill -> id = $bill_id;
			$bill = $this -> Bill -> read();
			if ($bill['Bill']['category'] == 'Joint')
			{
				if($authors['BillAuthor']['undr_auth_appr'] && $authors['BillAuthor']['grad_auth_appr'])
				{		
					$this -> Bill -> saveField('status', $this -> AUTHORED);
				}
				else
				{
					$this -> Bill -> saveField('status', $this -> AWAITING_AUTHOR);	
				}
			}
			else
			{
				if($authors['BillAuthor']['undr_auth_appr'] || $authors['BillAuthor']['grad_auth_appr'])
				{		
					$this -> Bill -> saveField('status', $this -> AUTHORED);
				}
				else
				{
					$this -> Bill -> saveField('status', $this -> AWAITING_AUTHOR);	
				}
			}
			$this -> redirect($this -> referer());

		}
		else
		{
			$this -> Session -> setFlash('You cannot sign this bill.');
			$this -> redirectHome();
		}
	}

	public function sign($bill_id, $sig_field, $sig_value)
	{
		$this -> BillAuthor -> id = $this -> getAuthorIdFromBillId($bill_id);
		$this -> BillAuthor -> saveField($sig_field, $sig_value);
		$this -> BillAuthor -> saveField(str_replace("id", "tmsp", $sig_field), date('Y-m-d h:i:s'));
		$this -> redirect(array(
			'controller' => 'bills',
			'action' => 'view',
			$bill_id
		));
	}

	private function getAuthorIdFromBillId($bill_id)
	{
		$this -> Bill -> id = $bill_id;
		$this -> loadModel('BillAuthor');
		$auth_id = $this -> Bill -> findById($bill_id);
		return $auth_id['Authors']['id'];
	}

	private function setSignatureNames($data)
	{
		$signee_names = array();
		$this -> loadModel('User');
		if ($data['grad_pres_id'] != 0)
		{
			$gpres = $this -> User -> findBySgaId($data['grad_pres_id']);
			$signee_names['grad_pres'] = $gpres['User']['name'];
		}
		else
		{
			$signee_names['grad_pres'] = 'Unsigned';
		}
		if ($data['grad_secr_id'] != 0)
		{
			$gpres = $this -> User -> findBySgaId($data['grad_secr_id']);
			$signee_names['grad_secr'] = $gpres['User']['name'];
		}
		else
		{
			$signee_names['grad_secr'] = 'Unsigned';
		}
		if ($data['undr_pres_id'] != 0)
		{
			$gpres = $this -> User -> findBySgaId($data['undr_pres_id']);
			$signee_names['undr_pres'] = $gpres['User']['name'];
		}
		else
		{
			$signee_names['undr_pres'] = 'Unsigned';
		}
		if ($data['undr_secr_id'] != 0)
		{
			$gpres = $this -> User -> findBySgaId($data['undr_secr_id']);
			$signee_names['undr_secr'] = $gpres['User']['name'];
		}
		else
		{
			$signee_names['undr_secr'] = 'Unsigned';
		}
		if ($data['vp_fina_id'] != 0)
		{
			$gpres = $this -> User -> findBySgaId($data['vp_fina_id']);
			$signee_names['vp_fina'] = $gpres['User']['name'];
		}
		else
		{
			$signee_names['vp_fina'] = 'Unsigned';
		}
		$this -> set('signee_names', $signee_names);
	}

	public function allocations($user_id = null)
	{
		$bills = $this -> Bill -> find('all', array('conditions' => array('submitter' => $user_id)));
		// Hash::extract all of the bill ids
		// find all of the line items
		// sum the totals for each account
		// set view variables for the totals
	}

	/**
     * Creates and sends an email to all of the owners of a bill.
     */
    private function updateBillOwners($id)
    {
        $bill = $this -> Bill -> findById($id);
        $this -> loadModel('User');
        $submitter = $this -> User -> findById($bill['Bill']['submitter']);
        $authors = array();
		// MRE added this check as an extra safeguard to prevent
		// random people from receiving emails. Emails were sent
		// erroneously before because a null was making it into
		// the author id field, and non-sga user have a null sga id
        if ($bill['Authors']['grad_auth_id'] != null)
        {
                $gradAuthor = $this -> User -> findBySgaId($bill['Authors']['grad_auth_id']);
                if(isset($gradAuthor['User']['id']))
                {
                        $authors[] = $gradAuthor['User']['email'];
                        $this -> set('grad_name', $gradAuthor['User']['name']);
                }
        }
        if ($bill['Authors']['undr_auth_id'] != null)
        {
                $undrAuthor = $this -> User -> findBySgaId($bill['Authors']['undr_auth_id']);
                if(isset($undrAuthor['User']['id']))
                {
                        $authors[] = $undrAuthor['User']['email'];
                        $this -> set('undr_name', $undrAuthor['User']['name']);
                }
        }
        $authors[] = $submitter['User']['email'];
        $this -> set('bill', $bill);
        $email = new CakeEmail();
        $email -> config('default');
        $email -> from(array('gtsgacampus@gmail.com' => 'JacketPages'));
        $email -> to($authors);
        $email -> subject('New Bill');
        $email -> template('newbill');
        $email -> emailFormat('html');       
        $email -> viewVars(array(
                'bill' => $bill,
                'grad_name' => (isset($gradAuthor['User']['name'])) ? $gradAuthor['User']['name'] : '',
                'undr_name' => (isset($undrAuthor['User']['name'])) ? $undrAuthor['User']['name'] : ''
        ));
        $email -> send();
    }

}
?>
