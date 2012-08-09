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
		'Js'
	);
	public $components = array(
		'Acl',
		'RequestHandler',
		'Session'
	);

	/**
	 * A table listing of organizations.
	 * @param letter - the first letter of an organization's name for searching.
	 * @param category - an organization category name to be used as a filter.
	 */
	public function index($letter = null, $category = null)
	{
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
					array('Organization.STATUS !=' => 'Inactive'),
					array('Category.NAME LIKE' => $this -> Session -> read('Search.category') . '%')
				)),
			'limit' => 20
		);
		// If the request is ajax then change the layout to return just the updated user list
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
				$this -> Session -> setFlash('The user has been saved.');
				$this -> redirect(array('action' => 'index'));
			}
			else
			{
				$this -> Session -> setFlash('Unable to edit the user.');
			}
		}
	}

}
?>
