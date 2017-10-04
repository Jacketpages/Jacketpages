<?php

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 */
class AppController extends Controller
{
	/**
	 * Bill Statuses
	 */
	public $CREATED = 1;
	public $AWAITING_AUTHOR = 2;
	public $AUTHORED = 3;
	public $AGENDA = 4;
	public $CONFERENCE = 5;
	public $PASSED = 6;
	public $FAILED = 7;
	public $TABLED = 8;

	/**
	 * Bill categories
	 */
	public $JOINT = 'Joint';
	public $UNDERGRADUATE = 'Undergraduate';
	public $GRADUATE = 'Graduate';

	public $helpers = array(
		'Js',
		'Session',
		'Html'
	);
	public $components = array(
		'Acl',
		'Auth' => array(
			'loginRedirect' => array(
				'controller' => 'pages',
				'action' => 'display'
			),
			'logoutRedirect' => array(
				'controller' => 'pages',
				'action' => 'display'
			),
			'authError' => "You cannot access that page",
			'authorize' => array(
				'Controller',
				'Actions' => array('actionPath' => 'controllers')
			)
		),
		'Session'
	);

	/**
	 * Users that aren't logged in have access to the following actions.
	 */
	public function beforeFilter()
	{
        CakeLog::debug("Entering " . $this->name . "Controller::" . $this->view);
		// display is the home/base page

		$this -> Auth -> allow('display', 'index', 'view', 'loginAsOtherUser');
		$this -> setPermissions();
		$this -> set('fiscalYear', $this -> getFiscalYear() + 2);
	}

	private function setPermissions()
	{
		$level = $this -> Session -> read('User.level') != "" ? $this -> Session -> read('User.level') : "general";

		$this -> set('general', $this -> Acl -> check("Role/$level", 'general'));
		$this -> set('student', $this -> Acl -> check("Role/$level", 'student'));
		$this -> set('gt_member', $this -> Acl -> check("Role/$level", 'gt_member'));
		$this -> set('sga_user', $this -> Acl -> check("Role/$level", 'sga_user'));
		$this -> set('sga_exec', $this -> Acl -> check("Role/$level", 'sga_exec'));
        $this->set('sga_admin', $this->Acl->check("Role/$level", 'sga_admin'));;
        $this->set('president', $this->Acl->check("Role/$level", 'president'));
        $this->set('sec', $this->Acl->check("Role/$level", 'sec'));
        $this->set('treasurer', $this->Acl->check("Role/$level", 'treasurer'));
		$this -> set('sofo', $this -> Acl -> check("Role/$level", 'sofo'));
		$this -> set('lace', $this -> Acl -> check("Role/$level", 'lace'));
		$this -> set('admin', $this -> Acl -> check("Role/$level", 'admin'));

		if (!$this -> Acl -> check("Role/$level", "controllers/" . $this -> name . "/" . $this -> params['action']))
		{
			$this -> Session -> setFlash("You do not have permission to access that page.");
			$this -> redirect(array(
				'controller' => 'pages',
				'action' => 'home'
			));
		}
		switch($this -> name)
		{
			case 'Organizations' :
				$this -> setOrganizationPermissions($level);
				break;
			case 'LineItems' :
				$this -> setLineItemPermissions($level);
		}
	}

	private function setOrganizationPermissions($level)
	{
		switch ($this -> params['action'])
		{
			case 'view' :
				$this -> set('orgEditPerm', $this -> Acl -> check('Role/' . $level, 'orgEditPerm'));
				$this -> set('orgViewDocumentsPerm', $this -> Acl -> check('Role/' . $level, 'orgViewDocumentsPerm'));
				$this -> set('orgAdminPerm', $this -> Acl -> check('Role/' . $level, 'orgAdminPerm'));
				break;
			case 'inactive_orgs' :
			case 'index' :
			case 'silverleaf':
				$this -> set('orgCreatePerm', $this -> Acl -> check('Role/' . $level, 'orgCreatePerm'));
				$this -> set('orgExportPerm', $this -> Acl -> check('Role/' . $level, 'orgExportPerm'));
				$this -> set('orgAdminView', $this -> Acl -> check('Role/' . $level, 'orgAdminView'));
				$this -> set('orgEditDeletePerm', $this -> Acl -> check('Role/' . $level, 'orgEditDeletePerm'));
				break;
		}
	}

	public function setLineItemPermissions($level)
	{
		switch ($this -> params['action'])
		{
			case 'view' :
				$this -> set('sgaExec', $this -> Acl -> check('Role/' . $level, 'sgaExec'));
				break;
		}
	}

	public function afterFilter()
	{
        CakeLog::debug("Exiting " . $this->name . "Controller::" . $this->view);
	}

	public function beforeRender()
	{
		$this -> set('CREATED', $this -> CREATED);
		$this -> set('AWAITING_AUTHOR', $this -> AWAITING_AUTHOR);
		$this -> set('AUTHORED', $this -> AUTHORED);
		$this -> set('AGENDA', $this -> AGENDA);
		$this -> set('CONFERENCE', $this -> CONFERENCE);
		$this -> set('PASSED', $this -> PASSED);
		$this -> set('FAILED', $this -> FAILED);
		$this -> set('TABLED', $this -> TABLED);
	}

	public function isAuthorized($user)
	{
		return true;
	}

	protected function getFiscalYear()
	{
		return substr($this -> calculateFiscalYearForDate(date('n/d/y')), -2);
	}

	protected function calculateFiscalYearForDate($inputDate, $fyStart = "6/1/", $fyEnd = "5/31/")
	{
		$date = strtotime($inputDate);
		$inputyear = strftime('%y', $date);

		$startdate = strtotime($fyStart . $inputyear);
		$enddate = strtotime($fyEnd . $inputyear);

		if ($date > $startdate)
		{
			$fy = intval($inputyear);
		}
		else
		{
			$fy = intval(intval($inputyear) - 1);
		}
		return $fy;
	}

	// A function to return the Roman Numeral, given an integer
	protected function roman_numerals($num)
	{
		// Make sure that we only use the integer portion of the value
		$n = intval($num);
		$result = '';

		// Declare a lookup array that we will use to traverse the number:
		$lookup = array(
			'M' => 1000,
			'CM' => 900,
			'D' => 500,
			'CD' => 400,
			'C' => 100,
			'XC' => 90,
			'L' => 50,
			'XL' => 40,
			'X' => 10,
			'IX' => 9,
			'V' => 5,
			'IV' => 4,
			'I' => 1
		);

		foreach ($lookup as $roman => $value)
		{
			// Determine the number of matches
			$matches = intval($n / $value);

			// Store that many characters
			$result .= str_repeat($roman, $matches);

			// Substract that from the number
			$n = $n % $value;
		}

		// The Roman numeral should be built, return it
		return $result;
	}

	protected function isOfficer($org_id)
	{
		$this -> loadModel('Membership');
		$db = ConnectionManager::getDataSource('default');
		return $this -> Membership -> find('count', array(
			'fields' => 'DISTINCT Membership.role',
			'conditions' => array('AND' => array(
					'org_id' => $org_id,
					'user_id' => $this -> Session -> read('User.id'),
					'OR' => array(
						$db -> expression('Membership.end_date >= NOW()'),
						'Membership.end_date' => null
					),
					'OR' => array('role' => array(
							'Officer',
							'President',
							'Treasurer',
							'Advisor'
						))
				)),
			'recursive' => -1
		));
	}

	protected function isMember($org_id, $user_id = null)
	{
		if ($user_id == null)
			$user_id = $this -> Session -> read('User.id');
		$this -> loadModel('Membership');
		$db = ConnectionManager::getDataSource('default');
		return (strcmp($this -> Membership -> field('role', array(
			'org_id' => $org_id,
			'user_id' => $user_id,
			'status' => 'Active',
			'OR' => array(
				$db -> expression('Membership.end_date >= NOW()'),
				'Membership.end_date' => null
			)
		)), 'Member') == 0);
	}

	protected function isPendingMember($org_id, $user_id = null)
	{
		if ($user_id == null)
			$user_id = $this -> Session -> read('User.id');
		$this -> loadModel('Membership');
		$db = ConnectionManager::getDataSource('default');
		return (strcmp($this -> Membership -> field('role', array(
			'org_id' => $org_id,
			'user_id' => $user_id,
			'status' => 'Pending',
			'OR' => array(
				$db -> expression('Membership.end_date >= NOW()'),
				'Membership.end_date' => null
			)
		)), 'Member') == 0);
	}

	public function getMembers($org_id = null, $roles = array(), $single = false, $statuses = array('Active'))
	{
		$this -> loadModel('Membership');
		$members = null;
		if (isset($org_id) && count($roles))
		{
			$db = ConnectionManager::getDataSource('default');
			if (!$single)
			{
				$members = $this -> Membership -> find('all', array('conditions' => array('AND' => array(
							'Membership.org_id' => $org_id,
							'Membership.role' => $roles,
							'Membership.status' => $statuses,
							'OR' => array(
								$db -> expression('Membership.end_date >= NOW()'),
								'Membership.end_date' => null
							)
						))));
			}
			else
			{
				$members = $this -> Membership -> find('first', array(
					'conditions' => array('AND' => array(
							'Membership.org_id' => $org_id,
							'Membership.role' => $roles,
							'Membership.status' => $statuses,
							'OR' => array(
								$db -> expression('Membership.end_date >= NOW()'),
								'Membership.end_date' => null
							)
						)),
					'order' => 'Membership.start_date desc'
				));
				if (count($members) == 0)
				{
					$members = array(
						'Membership' => array('name' => ''),
						'User' => array('email' => '')
					);
				}
			}
		}
		return $members;
	}

	// A more efficient version of getMembers, only returning name and email
	public function getMembersContact($org_id = null, $roles = array(), $single = false, $statuses = array('Active'))
	{
		$this -> loadModel('Membership');
		$this -> Membership -> Behaviors -> load('Containable');
		$members = null;
		if (isset($org_id) && count($roles))
		{
			$db = ConnectionManager::getDataSource('default');
			if (!$single)
			{
				$members = $this -> Membership -> find('all', array(
					'conditions' => array(
						'AND' => array(
							'Membership.org_id' => $org_id,
							'Membership.role' => $roles,
							'Membership.status' => $statuses,
							'OR' => array(
								$db -> expression('Membership.end_date >= NOW()'),
								'Membership.end_date' => null
							)
						)
					),
					'fields' => array(
						'name'
					),
					'contain' => array(
						'User.email'
					)
				));
			}
			else
			{
				$members = $this -> Membership -> find('first', array(
					'conditions' => array(
						'AND' => array(
							'Membership.org_id' => $org_id,
							'Membership.role' => $roles,
							'Membership.status' => $statuses,
							'OR' => array(
								$db -> expression('Membership.end_date >= NOW()'),
								'Membership.end_date' => null
							)
						)
					),
					'order' => 'Membership.start_date desc',
					'fields' => array(
						'name'
					),
					'contain' => array(
						'User.email'
					)
				));
				if (count($members) == 0)
				{
					$members = array(
						'Membership' => array('name' => ''),
						'User' => array('email' => '')
					);
				}
			}
		}
		return $members;
	}

	protected function isSubmitter($bill_id)
	{
		$this -> loadModel('Bill');
		$submitter_id = $this -> Bill -> field('submitter', array('id' => $bill_id));
		return $this -> Session -> read('User.id') == $submitter_id;
	}

	protected function isAuthor($bill_id)
	{
		$this -> loadModel('Bill');
		$bill = $this -> Bill -> findById($bill_id);
		return ($this -> Session -> read('Sga.id') == $bill['Authors']['grad_auth_id'] || $this -> Session -> read('Sga.id') == $bill['Authors']['undr_auth_id']);
	}

	protected function isSGA()
	{
		$level = $this -> Session -> read('User.level') != "" ? $this -> Session -> read('User.level') : "general";
		return $this -> Acl -> check("Role/$level", 'sga_user');
	}

	protected function isSGAExec()
	{
		$level = $this -> Session -> read('User.level') != "" ? $this -> Session -> read('User.level') : "general";
		return $this -> Acl -> check("Role/$level", 'sga_exec');
	}

	protected function isLace()
	{
		$level = $this -> Session -> read('User.level') != "" ? $this -> Session -> read('User.level') : "general";
		return $this -> Acl -> check("Role/$level", 'lace');
	}

	protected function redirectHome()
	{
		$this -> redirect(array(
			'controller' => 'pages',
			'action' => 'home'
		));
	}

}
