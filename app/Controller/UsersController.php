<?php
	/**
	 * User Controller.
	 *
	 * Manages all methods related to User pages and functionality.
	 *
	 * @author Stephen Roca
	 * @since 03/22/2012
	 */
	class UsersController extends AppController
	{
		/**
		 * Overidden $components, $helpers, and $uses
		 */
		public $helpers = array(
			'Html',
			'Form'
		);
		public $components = array('Session');

		/**
		 * The User index page. A table listing of users.
		 */
		public function index()
		{
			$this -> set('users', $this -> User -> find('all'));
		}

		/**
		 * The User view page. Views an individual user's information.
		 */
		public function view($id = null)
		{
			$this -> User -> id = $id;
			$this -> set('user', $this -> User -> read());
		}
		
		public function add()
		{
			if ($this -> request -> is('post'))
			{
				if ($this -> User -> save($this -> request -> data))
				{
					$this -> Session -> setFlash('The user has been saved.');
					$this -> redirect(array('action' => 'index'));
				}
				else {
					$this -> Session -> setFlash('Unable to add the user.');
				}
			}
		}

	}
?>