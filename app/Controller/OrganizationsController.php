<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 */
class OrganizationsController extends AppController
{
	/**
	 * Overidden $components, $helpers, and $uses
	 */
	public $helpers = array(
		'Html',
		'Form',
		'Paginator',
		'Js',
		'Permission',
		'Csv'
	);
	public $components = array(
		'Acl',
		'RequestHandler',
		'Session',
		'Csv'
	);

	/**
	 * A table listing of organizations.
	 * @param letter - the first letter of an organization's name for searching.
	 * @param category - an organization category name to be used as a filter.
	 * @param inactive_page - a flag that tells whether or not to display only
	 * inactive organizations or all organizations
	 */
	public function index($letter = null, $category = null, $inactive_page = null)
	{
		// Set page view permissions
		$this -> set('orgCreatePerm', $this -> Acl -> check('Role/' . $this -> Session -> read('USER.LEVEL'), 'orgCreatePerm'));
		$this -> set('orgExportPerm', $this -> Acl -> check('Role/' . $this -> Session -> read('USER.LEVEL'), 'orgExportPerm'));

		// Writes the search keyword to the Session if the request is a POST
		if ($this -> request -> is('post'))
		{
			$this -> Session -> write('Search.keyword', $this -> request -> data['Organization']['keyword']);
			$this -> Session -> write('Search.category', $this -> request -> data['Organization']['category']);
		}
		// Deletes the search keyword if the letter is null and the request is not ajax
		else if (!$this -> RequestHandler -> isAjax() && $letter == null)
		{
			$this -> Session -> delete('Search');
		}
		$org_status = 'Organization.STATUS';
		if ($inactive_page)
		{
			$org_status = $org_status . " =";
		}
		else
		{
			$org_status = $org_status . " !=";
		}

		// Performs a SELECT on the Organization table with the following conditions:
		// WHERE (NAME LIKE '%<KEYWORD>%' OR DESCRIPTION LIKE '%<KEYWORD>%' OR SHORT_NAME
		// LIKE '%<KEYWORD>%')
		// AND NAME LIKE '<LETTER>%' AND STATUS != 'Inactive' AND CATEGORY.NAME LIKE
		// '%<CATEGORY>%'
		$this -> paginate = array(
			'conditions' => array('AND' => array(
					'OR' => array(
						array('Organization.NAME LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%'),
						array('Organization.DESCRIPTION LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%'),
						array('Organization.SHORT_NAME LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%')
					),
					array('Organization.NAME LIKE' => $letter . '%'),
					array($org_status => 'Inactive'),
					array('Category.NAME LIKE' => $this -> Session -> read('Search.category') . '%')
				)),
			'limit' => 20
		);
		// If the request is ajax then change the layout to return just the updated user
		// list
		if ($this -> RequestHandler -> isAjax())
		{
			$this -> layout = 'list';
		}
		// Sets the users variable for the view
		$this -> set('organizations', $this -> paginate('Organization'));
		$orgNames = $this -> Organization -> find('all', array('fields' => 'NAME'));
		// Create the array for the javascript autocomplete
		$just_names = array();
		foreach ($orgNames as $orgName)
		{
			$just_names[] = $orgName['Organization']['NAME'];
		}
		$this -> set('names_to_autocomplete', $just_names);
	}

	/**
	 * Displays a list of inactive organizations
	 * @param letter - the first letter of an organization's name for searching.
	 * @param category - an organization category name to be used as a filter.
	 */
	public function inactive_orgs($letter = null, $category = null)
	{
		$this -> index($letter, $category, true);
	}

	/**
	 * Displays a user's organizations past and current.
	 * @param id - a user's id
	 */
	public function my_orgs($id = null)
	{
		$this -> loadModel('Membership');
		$org_ids = $this -> Membership -> find('list', array(
			'conditions' => array('USER_ID' => $id),
			'fields' => array(
				'ID',
				'ORG_ID'
			)
		));
		debug($org_ids);
		if ($org_ids != null)
		{
			$this -> set('organizations', $this -> Organization -> find('all', array('conditions' => array('IN' => array('ID' => $org_ids)))));
		}
	}

	/**
	 * Views an individual organization's information.
	 * @param id - the id of the Organization to view.
	 */
	public function view($id = null)
	{
		// Set which organization to retrieve from the database.
		$this -> Organization -> id = $id;
		$this -> set('organization', $this -> Organization -> read());
		$this -> loadModel('Membership');
		$president = $this -> Membership -> find('first', array(
			'conditions' => array('AND' => array(
					'Membership.ORG_ID' => $id,
					'Membership.ROLE' => 'President',
					'Membership.START_DATE LIKE' => '2011%'
				)),
			'fields' => array(
				'Membership.ROLE',
				'Membership.NAME',
				'Membership.STATUS',
				'Membership.TITLE'
			)
		));
		$this -> set('president', $president);
		$treasurer = $this -> Membership -> find('first', array(
			'conditions' => array('AND' => array(
					'Membership.ORG_ID' => $id,
					'Membership.ROLE' => 'Treasurer',
					'Membership.START_DATE LIKE' => '2011%'
				)),
			'fields' => array(
				'Membership.ROLE',
				'Membership.NAME',
				'Membership.STATUS',
				'Membership.TITLE'
			)
		));
		$this -> set('treasurer', $treasurer);
		$advisor = $this -> Membership -> find('first', array(
			'conditions' => array('AND' => array(
					'Membership.ORG_ID' => $id,
					'Membership.ROLE' => 'Advisor',
					'Membership.START_DATE LIKE' => '2011%'
				)),
			'fields' => array(
				'Membership.ROLE',
				'Membership.NAME',
				'Membership.STATUS',
				'Membership.TITLE'
			)
		));
		$this -> set('advisor', $advisor);
		$officers = $this -> Membership -> find('all', array(
			'conditions' => array('AND' => array(
					'Membership.ORG_ID' => $id,
					'Membership.ROLE' => 'Officer',
					'Membership.START_DATE LIKE' => '2011%'
				)),
			'fields' => array(
				'Membership.ROLE',
				'Membership.NAME',
				'Membership.STATUS',
				'Membership.TITLE'
			)
		));
		$this -> set('officers', $officers);
		$members = $this -> Membership -> find('all', array('conditions' => array('AND' => array(
					'Membership.ROLE' => 'Member',
					'Membership.ORG_ID' => $id
				))));
		$this -> set('members', $members);
		$pending_members = $this -> Membership -> find('all', array('conditions' => array('AND' => array(
					'Membership.ROLE' => 'Pending',
					'Membership.ORG_ID' => $id
				))));
		$this -> set('pending_members', $pending_members);
	}

	public function add()
	{
		//TODO Implement
	}

	/**
	 * Edits an individual organization's information.
	 * @param id - the id of the Organization to edit.
	 */
	public function edit($id = null)
	{
		$this -> Organization -> id = $id;
		if ($this -> request -> is('get'))
		{
			$this -> request -> data = $this -> Organization -> read();
			$this -> set('organization', $this -> Organization -> read());
		}
		else
		{
			if ($this -> Organization -> save($this -> request -> data))
			{
				CakeLog::write('info', 'User[' . $this -> Session -> read('USER.NAME') . '] has edited Organization[' . $this -> request -> data['Organization']['NAME'] . ']');
				$this -> Session -> setFlash('The organization has been saved.');
				$this -> redirect(array(
					'action' => 'view',
					$this -> request -> data['Organization']['ID']
				));
			}
			else
			{
				CakeLog::write('error', 'User[' . $this -> Session -> read('USER.NAME') . '] was unable to edit Organization[' . $this -> request -> data['Organization']['NAME'] . ']');
				$this -> Session -> setFlash('Unable to edit the organization.');
			}
		}
	}

	/**
	 * Exports the following information for all organizations: name, status,
	 * president, treasurer, advisor, and contact information for each to a csv file.
	 */
	public function export()
	{
		$organizations = $this -> Organization -> find('all', array('fields' => array(
				'Organization.ID',
				'Organization.NAME',
				'Organization.STATUS',
				'Organization.CONTACT_NAME',
				'User.EMAIL'
			)));
		$this -> loadModel('Membership');
		$build_export[] = array(
				"Organization",
				"Status",
				"Organization Contact",
				"Organization Contact's Email",
				"President",
				"President's Email",
				"Treasurer",
				"Treasurer's Email",
				"Advisor",
				"Advisor's Email",
			);
		foreach ($organizations as $organization)
		{
			$roles = array(
				'President',
				'Treasurer',
				'Advisor'
			);
			$president = $this -> Membership -> findByRoleAndOrgId('President', $organization['Organization']['ID'], array(
				'NAME',
				'User.EMAIL'
			));
			$treasurer = $this -> Membership -> findByRoleAndOrgId('Treasurer', $organization['Organization']['ID'], array(
				'NAME',
				'User.EMAIL'
			));
			$advisor = $this -> Membership -> findByRoleAndOrgId('Advisor', $organization['Organization']['ID'], array(
				'NAME',
				'User.EMAIL'
			));
			
			$build_export[] = array(
				$organization['Organization']['NAME'],
				$organization['Organization']['STATUS'],
				$organization['Organization']['CONTACT_NAME'],
				$organization['User']['EMAIL'],
				$president['Membership']['NAME'],
				$president['User']['EMAIL'],
				$treasurer['Membership']['NAME'],
				$treasurer['User']['EMAIL'],
				$advisor['Membership']['NAME'],
				$advisor['User']['EMAIL'],
			);
		}
		$this -> layout = 'csv';
		$this -> set('export', $build_export);
	}

	public function roster()
	{
		
	}

}
?>
