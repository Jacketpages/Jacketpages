<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 */
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
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
		'Csv',
		'Excel',
		'Text'
	);
	public $components = array(
		'Acl',
		'RequestHandler',
		'Session',
		'Csv'
	);

	/**
	 * @deprecated
	 */
	private function getlogoFiles()
	{
		$orgs = $this -> Organization -> query("SELECT id, logo, logo_name, logo_type from organizations where logo is not null");
		for ($i = 0; $i < count($orgs); $i++)
		{
			$dir = new Folder("../webroot/img/" . $orgs[$i]["organizations"]["id"], true, 0744);
			$file = new File($dir -> pwd() . DS . $orgs[$i]["organizations"]["logo_name"], true, 0744);
			$file -> write(($orgs[$i]['organizations']['logo']));
			$file -> close();
		}
	}

	/**
	 * @deprecated
	 */
	private function getcharterFiles()
	{
		$orgs = $this -> Organization -> query("SELECT organization_id, name, file from charters where id between 900 and 1382 and size != 0");
		for ($i = 0; $i < count($orgs); $i++)
		{
			$dir = new Folder();
			if (!$dir -> cd(".." . DS . "webroot" . DS . "files" . DS . $orgs[$i]["charters"]["organization_id"]))
			{
				$dir = new Folder(".." . DS . "webroot" . DS . "files" . DS . $orgs[$i]["charters"]["organization_id"], true, 0744);
			}
			$file = new File($dir -> pwd() . DS . $orgs[$i]["charters"]["name"], true, 0744);
			$file -> write($orgs[$i]['charters']['file']);
			$file -> close();
		}
	}

	/**
	 * @deprecated
	 */
	private function getBudgetFiles()
	{
		$orgs = $this -> Organization -> query("SELECT organization_id, name, file from budgets where size != 0");
		for ($i = 0; $i < count($orgs); $i++)
		{
			$dir = new Folder();
			if (!$dir -> cd(".." . DS . "webroot" . DS . "files" . DS . $orgs[$i]["budgets"]["organization_id"]))
			{
				$dir = new Folder(".." . DS . "webroot" . DS . "files" . DS . $orgs[$i]["budgets"]["organization_id"], true, 0744);
			}
			$file = new File($dir -> pwd() . DS . $orgs[$i]["budgets"]["name"], true, 0744);
			$file -> write($orgs[$i]['budgets']['file']);
			$file -> close();
		}
	}

	/**
	 *	@deprecated
	 */
	private function updateLogoPaths()
	{
		$orgIds = $this -> Organization -> query("SELECT id from organizations");
		for ($i = 0; $i < count($orgIds); $i++)
		{
			$dir = new Folder();
			if ($dir -> cd($dir -> pwd() . ".." . DS . "webroot" . DS . "img" . DS . $orgIds[$i]["organizations"]["id"]))
			{
				$files = $dir -> read();
				$path = "'/img/" . $orgIds[$i]["organizations"]["id"] . "/" . $files[1][0] . "'";
				$this -> Organization -> query("UPDATE ORGANIZATIONS SET LOGO_PATH = " . $path . " WHERE ID = " . $orgIds[$i]["organizations"]["id"]);
			}
		}
	}

	/**
	 *	@deprecated
	 */
	private function updateDocumentPaths()
	{
		$dir = new Folder();
		if ($dir -> cd($dir -> pwd() . ".." . DS . "webroot" . DS . "files"))
		{
			$folders = $dir -> read();
			for ($i = 0; $i < count($folders[0]); $i++)
			{
				if ($dir -> cd($dir -> pwd() . $folders[0][$i]))
				{
					$files = $dir -> read();
					for ($j = 0; $j < count($files[1]); $j++)
					{
						$path = "/files/" . $folders[0][$i] . "/";
						$this -> Organization -> query("INSERT INTO DOCUMENTS (org_id, name, path, last_updated) VALUES(" . $folders[0][$i] . ",'" . addslashes($files[1][$j]) . "','" . $path . "', NOW())");
					}
					$dir -> cd($dir -> pwd() . DS . "..");
				}
			}

		}
	}

	/**
	 * A table listing of organizations.
	 * @param letter - the first letter of an organization's name for searching.
	 * @param category - an organization category name to be used as a filter.
	 * @param inactive_page - a flag that tells whether or not to display only
	 * inactive organizations or all organizations
	 */
	public function index($letter = null, $category = null, $inactive_page = null)
	{
		// Writes the search keyword to the Session if the request is a POST
		if ($this -> request -> is('post'))
		{
			if (isset($this -> request -> data['Organization']['keyword']))
			{
				$this -> Session -> write('Search.keyword', trim($this -> request -> data['Organization']['keyword']));
				CakeLog::info($this -> request -> data['Organization']['keyword'], 'db');	
			}
			if (isset($this -> request -> data['Organization']['category']))
			{
				$this -> Session -> write('Search.category', $this -> request -> data['Organization']['category']);	
			}						
		}
		// Deletes the search keyword if the letter is null and the request is not ajax
		else if (!$this -> RequestHandler -> isAjax() && $letter == null)
		{
			$this -> Session -> delete('Search');
		}
		$org_status = 'Organization.status';
		if ($inactive_page)
		{
			$org_status = $org_status . " =";
		}
		else
		{
			$org_status = $org_status . " !=";
		}

		// Performs a SELECT on the Organization table with the following conditions:
		// WHERE (name LIKE '%<KEYWORD>%' OR DESCRIPTION LIKE '%<KEYWORD>%' OR SHORT_name
		// LIKE '%<KEYWORD>%')
		// AND name LIKE '<LETTER>%' AND STATUS != 'Inactive' AND CATEGORY.name LIKE
		// '%<CATEGORY>%'
		$this -> paginate = array(
			'conditions' => array('AND' => array(
					'OR' => array(
						array('Organization.name LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%'),
						array('Organization.description LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%'),
						array('Organization.short_name LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%')
					),
					array('Organization.name LIKE' => $letter . '%'),
					array($org_status => 'Inactive'),
					array('Category.name LIKE' => $this -> Session -> read('Search.category') . '%')
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
		$orgnames = $this -> Organization -> find('all', array('fields' => 'name', 'conditions' => array($org_status => 'Inactive')));
		// Create the array for the javascript autocomplete
		$just_names = array();
		foreach ($orgnames as $orgname)
		{
			$just_names[] = $orgname['Organization']['name'];
		}
		$this -> set('names_to_autocomplete', $just_names);
		
		// get all the category names for the select
		$this->loadModel('Category');
		$categories = $this->Category->find('list', array(
			'fields' => array('name', 'name')
		));
		$this->set('categories', $categories);
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
		if ($id == null)
		{
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}

		$org_ids = null;
		$this -> loadModel('Membership');

		$memberships = $this -> Membership -> find('all', array('conditions' => array('user_id' => $id),'order' => 'start_date desc'));
		$this -> set('memberships', $memberships);
	}
	
	/**
	 * Displays a all the silver leaf certified organizations
	 */
	public function silverleaf()
	{
		// The badge_id for silver leaf is hard coded as #1
		$this->paginate = array(
		    'joins' => array(
		        array(
		            'alias' => 'bo',
		            'table' => 'badges_organizations',
		            'type' => 'INNER',
		            'conditions' => 'bo.organization_id = organization.id AND bo.badge_id = 1'
		        )
		    ),
		    'limit' => 20
		);
		$this->set('organizations', $this->paginate());
		
		// on ajax, make sure to use the list template
		if ($this -> request -> is('ajax')){
			$this -> layout = 'list';
		}
	}

	/**
	 * Views an individual organization's information.
	 * @param id - the id of the Organization to view.
	 */
	public function view($id = null)
	{
		$this -> set('isOfficer',$this -> isOfficer($id));
		$this -> set('isMember',$this -> isMember($id));
		if ($id == null)
		{
			$this -> Session -> setFlash('Please select an organization to view.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'index'
			));
		}

		// Set which organization to retrieve from the database.
		$this -> Organization -> id = $id;
		$organization = $this -> Organization -> read();
		
		if (empty($organization))
		{
			// no organization with that id
			$this -> Session -> setFlash('Please select an organization to view.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'index'
			));
		}
		
		// redirect if org is inactive and user doesn't have lace permissions
		if (!$this -> isLace() && $organization['Organization']['status'] == 'Inactive')
		{
			$this -> Session -> setFlash('You do not have permission to view that page.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'index'
			));
		}
		
		$this -> loadModel('Badge');
		$this -> set('organization', $organization);
		$this -> loadModel('Membership');
		$this -> set('presidents', $this -> getMembers($id, array('President')));
		$this -> set('treasurers', $this -> getMembers($id, array('Treasurer')));
		$this -> set('advisors', $this -> getMembers($id, array('Advisor')));
		$this -> set('officers', $this -> getMembers($id, array('Officer')));
		$this -> set('members', $this -> getMembers($id, array('Member')));
		$this -> set('tier', $this -> roman_numerals($organization['Organization']['tier']));

		//MRE moved all of this to just the roster page
		/*$members = $this -> Membership -> find('all', array('conditions' => array('AND'
		 * => array(
		 'Membership.role' => 'Member',
		 'Membership.org_id' => $id
		 ))));
		 $this -> set('members', $members);
		 $pending_members = $this -> Membership -> find('all', array('conditions' =>
		 array('AND' => array(
		 'Membership.status' => 'Pending',
		 'Membership.org_id' => $id
		 ))));
		 $this -> set('pending_members', $pending_members);*/

		$this -> set('orgJoinOrganizationPerm', ($this -> isMember($id) || $this -> isPendingMember($id)));
	}

	public function add()
	{
		//TODO Implement
		if ($this -> request -> is('post'))
		{
			$this -> Organization -> create();
			$this -> Organization -> set('addr_id', 1); //for stupid FK
			if ($this -> Organization -> save($this -> request -> data))
			{
				$this -> Session -> setFlash('This organization has been created successfully.');
				$this -> redirect(array(
					'action' => 'index'			
				));
			}
			else
			{
				$this -> Session -> setFlash('This organization was not able to be created.');
				$this -> redirect(array(
					'action' => 'index'
				));
			}
		}
		
		// get all the category names for the select
		$this->loadModel('Category');
		$categories = $this->Category->find('list');
		$this->set('categories', $categories);
	}

	/**
	 * Edits an individual organization's information.
	 * @param id - the id of the Organization to edit.
	 */
	public function edit($id = null)
	{
		if(!($this -> isOfficer($id) || $this -> isLace()))
			$this -> redirectHome();
		if ($id == null)
		{
			$this -> Session -> setFlash('Please select an organization to view.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'index'
			));
		}

		$this -> Organization -> id = $id;
		if ($this -> request -> is('get'))
		{
			$this -> request -> data = $this -> Organization -> read();
			$this -> set('organization', $this -> Organization -> read());
		}
		else
		{
			$this -> loadModel('User');
			if(!$this -> User -> exists($this -> request -> data['Organization']['contact_id']))
			{
				$this -> Session -> setFlash("Please select a valid JacketPages user to use as the organization contact.");
				$this -> redirect(array('action' => 'index',$id));
			}
			if ($this -> Organization -> save($this -> request -> data))
			{
				CakeLog::write('info', 'User[' . $this -> Session -> read('User.name') . '] has edited Organization[' . $this -> request -> data['Organization']['name'] . ']');
				$this -> Session -> setFlash('The organization has been saved.');
				$this -> redirect(array(
					'action' => 'view',
					$this -> request -> data['Organization']['id']
				));
			}
			else
			{
				CakeLog::write('error', 'User[' . $this -> Session -> read('User.name') . '] was unable to edit Organization[' . $this -> request -> data['Organization']['name'] . ']');
				$this -> Session -> setFlash('Unable to edit the organization.');
			}
		}
		
		// get all the category names for the select
		$this->loadModel('Category');
		$categories = $this->Category->find('list');
		$this->set('categories', $categories);
	}

	/**
	 * Exports the following information for all organizations: name, status,
	 * president, treasurer, advisor, and contact information for each to a csv file.
	 */
	public function export()
	{
		// $this -> Session -> setFlash('UNDER CONSTRUCTION');
		// $this -> redirect('/organizations/index');
		
		$organizations = $this -> Organization -> find('all', array(
			'fields' => array(
				'Organization.id',
				'Organization.name',
				'Organization.status',
				'Organization.contact_id',
				'Organization.alcohol_form',
				'Organization.advisor_date',
				'Organization.constitution_date',
				'Organization.category'
			),
			'recursive' => -1
		));		

		$this -> loadModel('User');
		$this -> loadModel('Budget');
		$this -> loadModel('Category');
		$build_export[] = array(
			"Organization",
			"Status",
			"Alcohol Form Date",
			"Advisor Form Date",
			"Constitution Date",
			"Contact Name",
			"Contact Email",
			"President",
			"President's Email",
			"Treasurer",
			"Treasurer's Email",
			"Advisor",
			"Advisor's Email",
			"Budget State",
			"Category"			
		);
		foreach ($organizations as $organization)
		{
			// get values if they exist
			$president = $this -> getMembersContact($organization['Organization']['id'],array('President'), true);
			$treasurer = $this -> getMembersContact($organization['Organization']['id'],array('Treasurer'), true);
			$advisor = $this -> getMembersContact($organization['Organization']['id'],array('Advisor'), true);
			$contact = $this -> User -> findById($organization['Organization']['contact_id']);
			$budget = $this -> Budget -> find('first', array(
				'fields' => array('Budget.state'),
				'conditions' => array(
					'org_id' => $organization['Organization']['id'],
					'fiscal_year' => '20' . $this -> getFiscalYear() + 2),
				'recursive' => -1
			));
			$category = $this -> Category -> findById($organization['Organization']['category']);
			if(count($contact) == 0)
			{
				$contact = array(
						'User' => array('name' => '','email' => '')
					);
			}
			if(count($budget) == 0)
			{
				$budget = array(
						'Budget' => array('state' => 'Not Submitted')
					);
			}
			$build_export[] = array(
				$organization['Organization']['name'],
				$organization['Organization']['status'],
				$organization['Organization']['alcohol_form'],
				$organization['Organization']['advisor_date'],
				$organization['Organization']['constitution_date'],
				$contact['User']['name'],
				$contact['User']['email'],
				$president['Membership']['name'],
				$president['User']['email'],
				$treasurer['Membership']['name'],
				$treasurer['User']['email'],
				$advisor['Membership']['name'],
				$advisor['User']['email'],
				$budget['Budget']['state'],
				$category['Category']['name']
			);
		}
		$this -> layout = 'csv';
		$this -> set('export', $build_export);
	}

	public function addlogo($org_id = null)
	{
		if(!($this -> isOfficer($org_id) || $this -> isLace())){
			$this -> redirectHome();
		}
		
		if ($this -> request -> is('post'))
		{
			$organization = $this->Organization->find('first', array(
				'fields' => array('name'),
				'conditions' => array('Organization.id' => $org_id),
				'recursive' => -1
			));
		
			// set model data for validation
			$this->Organization->set($this->request->data);
			
			// check validations, for only the logo
			if($this->Organization->validatesLogoUpload()){
				// valid
				$dir = new Folder("../webroot/img/" . $org_id, true);
				
				// rename the logo but keep the extension
				$ext = pathinfo($this->request->data['Organization']['image']['name'], PATHINFO_EXTENSION);
				$logofilename = 'logo.'.$ext;
				
				if (move_uploaded_file($this -> request -> data['Organization']['image']['tmp_name'], 'img/'.$org_id.'/'.$logofilename))
				{
					$this -> Organization -> id = $org_id;
					$this -> Organization -> saveField('logo_path', '/img/'.$org_id.'/'.$logofilename);
				}

				$logo_path = $this -> Organization -> field('logo_path', array('id' => $org_id));
				
				 //MRE What does this do?
				 /*if (strcmp($logo_path, '/img/default_logo.gif') && strcmp($logo_path, '/img/' . $org_id . DS . $this -> request -> data['Logo']['image']['name']))
				{
					$webroot = new Folder("../webroot/" . $org_id . "/");
					$file = new File($webroot -> pwd() . $logo_path);
					$file -> delete();
				}*/
				
				$this -> redirect('/organizations/view/' . $org_id);
			}
			
		} else {
			// GET
			// display an error for image
			$this->Organization->invalidate('image', 'Image should be less than 200 KB.');
		}
		
		$this->set('errors', $this->Organization->validationErrors);
		$this->set('organization', $this -> Organization -> read(null, $org_id));
	}

	function getlogo($id = null)
	{
		if ($id == null)
		{
			$this -> Session -> setFlash('Please select an organization to view.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'index'
			));
		}

		$org = $this -> Organization -> read(null, $id);
		if ($org['Organization']['status'] == 'Frozen' || !$id)
		{
			$this -> Session -> setFlash(__('Invalid organization', true));
			$this -> redirect(array('action' => 'index'));
		}

		$this -> set('inpage', true);

		$file = $this -> Organization -> findById($id);
		$logo = array();
		$logo['name'] = $file['Organization']['logo_name'];
		$logo['type'] = $file['Organization']['logo_type'];
		$logo['data'] = $file['Organization']['logo'];

		if ($logo['data'] == null)
		{
			return false;
		}
		else
		{
			$this -> set('file', $logo);
			$this -> render('download', 'image');
			return true;
		}
	}

	function emailList($org_id = null)
	{
		if ($org_id == null)
		{
			$this -> Session -> setFlash('Please select an organization to view.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'index'
			));
		}

		$this -> loadModel('Membership');
		$members = $this -> Membership -> find('list', array(
			'conditions' => array('Membership.org_id' => $org_id),
			'fields' => array(
				'User.email',
				'User.first_name'
			),
			'recursive' => 1
		));
	}

}
?>
