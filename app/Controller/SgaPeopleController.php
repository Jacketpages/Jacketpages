<?php
/**
 * @author Stephen Roca
 * @since 6/22/2012
 */

class SgaPeopleController extends AppController
{
	public $helpers = array(
		'Form',
		'Paginator',
		'Html'
	);
	public $components = array('RequestHandler');
	/**
	 * View a list of SgaPeople
	 * @param letter - used to filter SgaPeople's names on thier first letter.
	 */
	// TODO clean up the comments for this function. They still correspond to User
	public function index($letter = null)
	{
		// Set page view permissions
		//$this -> set('sgaStatusEditDelete', $this -> Acl -> check('Role/' . $this -> Session -> read('User.level'), 'sgaStatusEditDelete'));

		// Writes the search keyword to the Session if the request is a POST
		if ($this -> request -> is('post'))
		{
			$this -> Session -> write('Search.keyword', $this -> request -> data['SgaPerson']['keyword']);
		}
		// Deletes the search keyword if the letter is null and the request is not ajax
		else if (!$this -> RequestHandler -> isAjax() && $letter == null)
		{
			$this -> Session -> delete('Search.keyword');
		}
		// Performs a search on the User table with the following conditions:
		// WHERE (NAME LIKE '%<SEARCH>%' OR GT_USER_NAME LIKE '%<SEARCH>%') AND NAME LIKE
		// '<LETTER>%'
		$this -> loadModel('User');
		$this -> paginate = array(
			'conditions' => array('AND' => array(
					'OR' => array( array($this -> User -> getVirtualField('name') . ' LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%')
						//array('User.GT_USER_NAME LIKE' => '%' . $this -> Session ->
						// read('Search.keyword') . '%')
					),
					array($this -> User -> getVirtualField('name') . ' LIKE' => $letter . '%')
				)),
			'limit' => 20,
			'order' => array('status' => 'ASC', 'house' => 'ASC', 'department' => 'ASC')
		);
		// If the request is ajax then change the layout to return just the updated user
		// list
		if ($this -> RequestHandler -> isAjax())
		{
			$this -> layout = 'list';
		}
		// Sets the users variable for the view
		$this -> set('sgapeople', $this -> paginate('SgaPerson'));
	}

	/**
	 * View an individual SgaPerson
	 */
	public function view()
	{
		// TODO Implement	
	}

	/**
	 * Add an individual SgaPerson
	 */
	public function add()
	{
		// TODO
		// if(user has permissions){}
		$this -> loadModel('User');
		if($this -> request -> is('post') && !$this -> User -> exists($this -> request -> data['SgaPerson']['user_id']))
		{
			$this -> Session -> setFlash("Please select a valid JacketPages user to add.");
			$this -> redirect(array('action' => 'index',$id));
		}
		$this -> loadModel('User');
		if ($this -> request -> is('post'))
		{
			$this -> SgaPerson -> create();
			if ($this -> SgaPerson -> save($this -> data))
			{
				$user = $this -> User -> read(null, $this -> data['SgaPerson']['user_id']);
				$this -> User -> set('level', 'sga_user');
				$this -> User -> set('sga_id', $this -> SgaPerson -> getInsertID());
				$this -> User -> save();
				$this -> Session -> setFlash(__('The user has been added to SGA.', true));
				$this -> redirect(array('action' => 'index'));
			}
			else
			{
				$this -> Session -> setFlash(__('Invalid user. User may already be assigned a role in SGA. Please try again.', true));
			}
		}
	}

	/**
	 * Edit an individual SgaPerson
	 */
	public function edit($id = null)
	{
		if($id == null){
			$this->Session->setFlash('Please select a person to edit.');
			$this->redirect(array('controller' => 'sga_people', 'action' => 'index'));
		}
		
		// TODO
		// if(user has permissions){}
		
		if (!$this -> SgaPerson -> exists($id))
		{
			$this->Session->setFlash('Please select a person to edit.');
			$this->redirect(array('controller' => 'sga_people', 'action' => 'index'));
		}
		
		// if form submited
		if (!$this -> request -> is('get'))
		{
			// use saveAll to create a transactional update for the belongsTo
			
			$this->loadModel('User');
			// get the associated user id and level
			$user = $this -> User -> find('first', array(
				'fields' => array('User.id', 'User.level'),
				'joins' => array(array(
						'table' => 'sga_people',
						'conditions' => array('sga_people.user_id = User.id')
				)),
				'conditions' => array('sga_people.id' => $id)
			));
			
			$this -> request -> data['SgaPerson']['id'] = $id;// needed for saveAll
			
			// if their status will be Inactive
			if($this->request->data['SgaPerson']['status'] === 'Inactive'){
				// if they had sga_ level
				if($user['User']['level'] === 'sga_exec' ||
				   $user['User']['level'] === 'sga_user' ||
				   $user['User']['level'] === 'sga_admin')
				{
					$this -> request -> data['User']['id'] = $user['User']['id'];
					$this -> request -> data['User']['level'] = 'gt_member';
				}
				
			} else if($this->request->data['SgaPerson']['status'] === 'Active'){
				// status will be Active
				
				// if the user is not already an sga_ level
				if($user['User']['level'] !== 'sga_exec' &&
				   $user['User']['level'] !== 'sga_user' &&
				   $user['User']['level'] !== 'sga_admin')
				{
					$this -> request -> data['User']['id'] = $user['User']['id'];
					$this -> request -> data['User']['level'] = 'sga_user';
				}
			}
		
			if ($this -> SgaPerson -> saveAll($this -> request -> data))
			{
				$this -> Session -> setFlash(__('The user has been edited.', true));
				$this -> redirect(array('action' => 'index'));
			}
			else
			{
				$this -> Session -> setFlash(__('Error. Please try again.', true));
			}
		}
		
		$this -> SgaPerson -> id = $id;
		$sgaPerson = $this -> SgaPerson -> read();
		
		$this -> request -> data = $sgaPerson;
		$this -> set('sgaPerson', $sgaPerson);
	}

}
?>