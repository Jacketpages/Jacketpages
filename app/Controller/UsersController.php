<?php
/**
 * User Controller.
 *
 * Manages all methods related to User pages and functionality.
 *
 * @author Stephen Roca
 * @since 03/22/2012
 */

//App::import('Vendor', 'cas', array('file' => 'CAS-1.2.0' . DS . 'CAS.php'));
class UsersController extends AppController
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
	 * A table listing of users.
	 * @param letter - the first letter of a User's name for searching
	 */
	public function index($letter = null)
	{
		// Writes the search keyword to the Session if the request is a POST
		if ($this -> request -> is('post'))
		{
			$this -> Session -> write('Search.keyword', $this -> request -> data['User']['keyword']);
		}
		// Deletes the search keyword if the letter is null and the request is not ajax
		else if (!$this -> RequestHandler -> isAjax() && $letter == null)
		{
			$this -> Session -> delete('Search.keyword');
		}
		// Performs a SELECT on the User table with the following conditions:
		// WHERE (NAME LIKE '%<KEYWORD>%' OR GT_USER_NAME LIKE '%<KEYWORD>%') AND NAME
		// LIKE
		// '<LETTER>%'
		$this -> paginate = array(
			'conditions' => array('AND' => array(
					'OR' => array(
						array('User.name LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%'),
						array('User.gt_user_name LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%')
					),
					array('User.name LIKE' => $letter . '%')
				)),
			'limit' => 20,
			'fields' => array(
				'User.id',
				'User.name',
				'User.gt_user_name',
				'User.email',
				'User.level',
				'User.phone'
			)
		);
		// If the request is ajax then change the layout to return just the updated user
		// list
		if ($this -> RequestHandler -> isAjax())
		{
			$this -> layout = 'list';
		}
		// Sets the users variable for the view
		$this -> set('users', $this -> paginate('User'));
	}

	/**
	 * Views an individual user's information.
	 * @param id - the id of the User to view. Defaults to null.
	 */
	public function view($id = null)
	{
		$this -> set('userDeletePerm', $this -> Acl -> check('Role/' . $this -> Session -> read('User.level'), 'userDelete'));

		$userEditPerm = $this -> Acl -> check('Role/' . $this -> Session -> read('User.level'), 'userEditPerm');
		if (!$userEditPerm && $id == $this -> Session -> read('User.id'))
		{
			$userEditPerm = true;
		}
		$this -> set('userEditPerm', $userEditPerm);

		// Set which user to retrieve from the database.
		$this -> User -> id = $id;
		$this -> set('user', $this -> User -> read());
		// Find all of the past memberships for this user
		// along with the organization.
		$this -> loadModel('Membership');
		$memberships = $this -> Membership -> find('all', array(
			'conditions' => array('AND' => array(
					'Membership.user_id' => $id,
					'Membership.end_date =' => '0000-00-00'
				)),
			'fields' => array(
				'Organization.name',
				'Organization.id',
				'Membership.role',
				'Membership.title',
				'Membership.start_date'
			)
		));
		$this -> set('memberships', $memberships);
	}

	/**
	 * Allows the addition of a User.
	 */
	public function add()
	{
		// If the request is a post attempt to save the user and his
		// associated location information. If this fails then log
		// the failure and set a flash message.
		if ($this -> request -> is('post'))
		{
			$this -> User -> create();
			if ($this -> User -> saveAssociated($this -> request -> data, array('validate' => true)))
			{
				$this -> Session -> setFlash('The user has been saved.');
				return $this -> redirect(array('action' => 'index'));
			}
			else
			{
				$this -> Session -> setFlash('Unable to add the user.');
			}
		}
	}

	public function delete($id = null)
	{
		$this -> User -> id = $id;
		if ($this -> User -> saveField('status', 'Inactive'))
		{
			$this -> Session -> setFlash(__('User deleted.', true));
			return $this -> redirect(array(
				'controller' => 'users',
				'action' => 'index'
			));
		}
		$this -> Session -> setFlash(__('User was not able to be deleted.', true));
		return $this -> redirect(array(
			'controller' => 'users',
			'action' => 'index'
		));
	}

	/**
	 * Allows the editing of a specific User.
	 * @param id - the id of the User to edit. Defaults to null.
	 */
	public function edit($id = null)
	{
		$this -> User -> id = $id;
		if ($this -> request -> is('get'))
		{
			$this -> request -> data = $this -> User -> read();
			$this -> set('user', $this -> User -> read());
		}
		else
		{
			if ($this -> User -> save($this -> request -> data))
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

	/**
	 * Logs in a User using Georgia Tech's CAS login system.
	 * Writes often used User variables to the Session.
	 */
	/*public function login()
	 {
	 // Set debug mode
	 $this -> phpCAS -> setDebug();
	 //Initialize phpCAS
	 $this -> phpCAS -> client(CAS_VERSION_2_0, Configure::read('CAS.hostname'),
	 Configure::read('CAS.port'), Configure::read('CAS.uri'), false);
	 // No SSL validation for the CAS server
	 $this -> phpCAS -> setNoCasServerValidation();
	 // Force CAS authentication if required
	 $this -> phpCAS -> forceAuthentication();

	 $GTUsername = $this -> phpCAS -> getUser();
	 if ($GTUsername != '')
	 {

	 }
	 }*/

	/**
	 * Logs a User into JacketPages using Cakephp's Auth Component
	 * with no interfacing with CAS
	 */
	public function login()
	{
		if ($this -> request -> is('post'))
		{
			$gtUsername = $this -> request -> data['User']['username'];
			$user = $this -> User -> find('first', array('conditions' => array('User.gt_user_name' => $gtUsername)));
			$this -> Session -> write('Auth.User', $user['User']['level']);
			$this -> Session -> write('User.name', $user['User']['name']);
			$this -> Session -> write('User.level', $user['User']['level']);
			$this -> Session -> write('User.id', $user['User']['id']);
			$this -> Session -> write('Sga.id', $user['User']['sga_id']);
			if ($this -> Auth -> login())
			{
				$this -> redirect($this -> Auth -> redirect());
			}
			else
			{
				$this -> Session -> setFlash('Your username/password was incorrect.');
			}
		}
	}

	/**
	 * Logs a User out of JacketPages using Cakephp's Auth Component
	 * with no interfacing with CAS
	 */
	public function logout()
	{
		$this -> Session -> destroy();
		$this -> redirect($this -> Auth -> logout());
	}

}
?>