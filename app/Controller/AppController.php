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
		'Permission',
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
		CakeLog::info("Entering " . $this -> name . "Controller::" . $this -> view);
		// display is the home/base page

		$this -> Auth -> allow('display', 'index', 'view');
		$this -> setPermissions();
		$this -> set('fiscalYear', $this -> getFiscalYear() + 2);
	}

	private function setPermissions()
	{
		$level = $this -> Session -> read('User.level') != "" ? $this -> Session -> read('User.level') : "general";

		$this -> set('general', $this -> Acl -> check("Role/$level", 'general'));
		$this -> set('gt_member', $this -> Acl -> check("Role/$level", 'gt_member'));
		$this -> set('sga_user', $this -> Acl -> check("Role/$level", 'sga_user'));
		$this -> set('sga_exec', $this -> Acl -> check("Role/$level", 'sga_exec'));
		$this -> set('sga_admin', $this -> Acl -> check("Role/$level", 'sga_admin'));
		$this -> set('sofo', $this -> Acl -> check("Role/$level", 'sofo'));
		$this -> set('lace', $this -> Acl -> check("Role/$level", 'lace'));
		$this -> set('admin', $this -> Acl -> check("Role/$level", 'admin'));

		if (!$this -> Acl -> check("Role/$level", "controllers/" . $this -> name . "/" . $this -> params['action']))
		{
			$this -> Session -> setFlash("You do not have permission to access that page.");
			if (strcmp($this -> referer(), "/") == 0)
				$this -> redirect(array(
					'controller' => 'pages',
					'action' => 'home'
				));
			else
				$this -> redirect($this -> referer());
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
		CakeLog::info("Exiting " . $this -> name . "Controller::" . $this -> view);
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
		$this -> set('permitted', $this -> Acl -> check('Role/' . $this -> Session -> read('USER.LEVEL'), 'controllers/' . $this -> viewPath . "/" . $this -> view));
	}

	public function isAuthorized($user)
	{
		return true;
	}

	function getFiscalYear()
	{
		return substr($this -> calculateFiscalYearForDate(date('n/d/y')), -2);
	}

	function calculateFiscalYearForDate($inputDate, $fyStart = "6/1/", $fyEnd = "5/31/")
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
	function roman_numerals($num)
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

}
