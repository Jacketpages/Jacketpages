<?php
/**
 * User Controller.
 *
 * Manages all methods related to User pages and functionality.
 *
 * @author Stephen Roca
 * @since 03/22/2012
 */

App::import('Vendor', 'cas', array('file' => 'CAS-1.2.0' . DS . 'CAS.php'));
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
		if ($this -> request -> is('post'))
		{
			$this -> Session -> write('Search.keyword', $this -> request -> data['User']['keyword']);
		}
		else if (!$this -> request -> is('ajax') && $letter == null)
		{
			$this -> Session -> delete('Search.keyword');
		}
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

		if ($this -> request -> is('ajax'))
			$this -> layout = 'list';
		$this -> set('users', $this -> paginate('User'));
	}

	/**
	 * Views an individual user's information.
	 * @param id - the id of the User to view. Defaults to null.
	 */
	public function view($id = null)
	{
		if($id == null){
			$this->redirect(array('controller' => 'users', 'action' => 'view', $this -> Session -> read('User.id')));
		}
		
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
		if ($this -> request -> is('post'))
		{
			$this -> User -> create();
			if ($this -> User -> saveAssociated($this -> request -> data))
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
		if (!($this -> Session -> read('User.level') == 'admin')) {
			$this -> redirect($this -> referer());
        }
		if($id == null){
			$this->Session->setFlash('Invalid request.');
			$this->redirect(array('controller' => 'users', 'action' => 'view', $this -> Session -> read('User.id')));
		}
		
		$this -> User -> id = $id;
		if ($this -> User -> exists() && $this -> User -> saveField('status', 'Inactive'))
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
		if($id == null){
			$this->Session->setFlash('Invalid request.');
			$this->redirect(array('controller' => 'users', 'action' => 'view', $this -> Session -> read('User.id')));
		}
		
		if ($this -> User -> exists($id)&& $this -> Session -> read('User.id') == $id)
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
					CakeLog::error("Unable to edit user: $id.");
					$this -> Session -> setFlash('Unable to edit the user.');
				}
			}
		}
		else
		{
			CakeLog::warning("User doesn't exist for user id: $id.");
			$this -> Session -> setFlash("Unable to edit user. User does not exist.");
			$this -> redirect($this -> referer());
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
// public function login()
		// {
			// // Set debug mode
			// phpCAS::setDebug();
			// //Initialize phpCAS
			// phpCAS::client(CAS_VERSION_2_0, Configure::read('CAS.hostname'), Configure::read('CAS.port'), Configure::read('CAS.uri'), false);
			// // No SSL validation for the CAS server
			// phpCAS::setNoCasServerValidation();
			// // Force CAS authentication if required
			// phpCAS::forceAuthentication();
			// $gtUsername = phpCAS::getUser();
			// $user = $this -> User -> find('first', array('conditions' => array('User.gt_user_name' => $gtUsername)));
			// if (!empty($user))
			// {
				// $this -> Session -> write('User.gt_user_name', $gtUsername);
				// $this -> Session -> write('Auth.User', $user['User']['level']);
				// $this -> Session -> write('User.name', $user['User']['name']);
				// $this -> Session -> write('User.level', $user['User']['level']);
				// $this -> Session -> write('User.id', $user['User']['id']);
				// $this -> Session -> write('Sga.id', $user['User']['sga_id']);
			// }
			// else {
				// $this -> Session -> write('User.level', 'student');
			// }
			// if ($this -> Auth -> login())
			// {
				// CakeLog::info("Login successful for user: $gtUsername.");
				// return $this -> redirect($this -> Auth -> redirect());
			// }
			// else
			// {
				// CakeLog::warning("Login failed for user: $gtUsername.");
				// $this -> Session -> setFlash('Your username/password was incorrect.');
			// }
// 			
		// }
	 
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
			if (!empty($user))
			{
				$this -> Session -> write('User.gt_user_name', $gtUsername);
				$this -> Session -> write('Auth.User', $user['User']['level']);
				$this -> Session -> write('User.name', $user['User']['name']);
				$this -> Session -> write('User.level', $user['User']['level']);
				$this -> Session -> write('User.id', $user['User']['id']);
				$this -> Session -> write('Sga.id', $user['User']['sga_id']);
			}
			if ($this -> Auth -> login())
			{
				CakeLog::info("Login successful for user: $gtUsername.");
				return $this -> redirect($this -> Auth -> redirect());
			}
			else
			{
				CakeLog::warning("Login failed for user: $gtUsername.");
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
		CakeLog::info("Logging out user: " . $this -> Session -> read('User.gt_user_name'));
		$this -> Session -> destroy();
		$this -> redirect($this -> Auth -> logout());
	}

	public function lookupByName()
	{
		$this->viewClass = 'Json';
	
		$input = filter_var(($_REQUEST['term']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
		$p = $this -> User -> find('all', array(
				'limit' => 5,
				'recursive' => 0,
				'fields' => array(
						'User.first_name',
						'User.last_name',
						'User.id',
						'User.gt_user_name',
						'User.email'
				),
				'conditions' => array("or" => array(
							'User.first_name LIKE' => '%' . $input . '%',
							'User.last_name LIKE' => '%' . $input . '%',
							'User.name LIKE' => '%' . $input . '%',
							'User.gt_user_name LIKE' => '%' . $input . '%'
					))
		));
		$options = array();
		while ($user = current($p))
		{
			$options[] = array(
					'name' => $user['User']['first_name'].' '.$user['User']['last_name'],
					'gt_user_name' => $user['User']['gt_user_name'],
					'id' => $user['User']['id'],
					'email' => $user['User']['email']
			);
			next($p);
		}
		$this->set('options', $options);
		$this->set('_serialize', 'options');
	}
}
?>