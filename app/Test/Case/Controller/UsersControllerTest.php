<?php
/**
 * @author Stephen Roca
 * @since 6/19/2013
 */

App::uses("AppController", "Controller");
App::uses("SessionComponent", "Controller/Component");
App::uses("SessionHelper", "Helper");
App::uses("User", "Model");
class UsersControllerTest extends ControllerTestCase
{
	public $fixtures = array(
		'app.User',
		'app.Membership',
		'app.Organization'
	);

	public $kevenUser = array('User' => array(
			'id' => 600000,
			'first_name' => 'Keven',
			'last_name' => 'Guebert',
			'gt_user_name' => 'kguebert'
		));

	public function setUp()
	{
		parent::setUp();
		$this -> User = ClassRegistry::init('User');
		$this -> controller = $this -> generate('Users', array('components' => array('Session' => array(
					'write',
					'read',
					'setFlash'
				))));
	}

	public function testIndexWithBlankSearch()
	{
		$data = array('User' => array('keyword' => ''));
		$this -> testAction('/users/index/', array('data' => $data));
		$this -> assertEquals($this -> User -> find('count'), count($this -> vars['users']));
	}

	public function testIndexWithSearchStephen()
	{
		$this -> controller -> Session -> expects($this -> any()) -> method('read') -> with($this -> equalTo('Search.keyword')) -> will($this -> returnValue('Stephen'));
		$data = array('User' => array('keyword' => 'Stephen'));
		$this -> testAction('/users/index/', array('data' => $data));
		$this -> assertEquals($this -> User -> find('count', array('conditions' => array('name LIKE' => '%Stephen%'))), count($this -> vars['users']));
	}

	public function testDelete()
	{
		$this -> testAction('/users/delete/1');
		$result = $this -> testAction('/users/view/1');
		$this -> assertEquals('Inactive', $this -> vars['user']['User']['status']);
	}

	public function testAddInvalidUser()
	{
		// setFlash is called from the add action and by the Auth Component. That is why
		// there are two calls.
		$this -> controller -> Session -> expects($this -> exactly(2)) -> method('setFlash');
		$data = $this ->kevenUser;
		unset($data['User']['gt_user_name']);
		$origUserCount = $this -> User -> find('count');
		$this -> testAction('users/add', array('data' => $data));
		$this -> assertEquals($origUserCount, $this -> User -> find('count'));
	}

	public function testAddValidUser()
	{
		$origUserCount = $this -> User -> find('count');
		$this -> testAction('users/add', array('data' => $this -> kevenUser));
		$this -> assertEquals($origUserCount + 1, $this -> User -> find('count'));
	}

}
