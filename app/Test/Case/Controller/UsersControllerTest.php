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
		$this -> controller = $this -> generate('Users', array('components' => array(
				'Session' => array(
					'read',
					'setFlash',
					'destroy'
				),
				'Auth' => array(
					'login',
					'logout'
				)
			)));
	}

	public function testLoginAndLogout()
	{
		$this -> controller -> Auth -> Session = $this -> getMock('SessionComponent', array('renew'), array(), '', false);
		$this -> controller -> Auth -> expects($this -> once()) -> method('login') -> will($this -> returnValue(true));
		$this -> testAction('/users/login', array('data' => array('User' => array('username' => 'sroca'))));

		// For some reason the controller needs to be mocked again or expect functions
		// fail...
		$this -> controller = $this -> generate('Users', array('components' => array(
				'Session' => array('destroy'),
				'Auth' => array('logout')
			)));
		$this -> controller -> Session -> expects($this -> once()) -> method('destroy');
		$this -> controller -> Auth -> expects($this -> once()) -> method('logout');
		$this -> testAction('/users/logout');

		// For some reason the controller needs to be mocked again or expect functions
		// fail...
		$this -> controller = $this -> generate('Users', array('components' => array(
				'Session' => array('setFlash'),
				'Auth' => array('login')
			)));
		$this -> controller -> Session -> expects($this -> once()) -> method('setFlash') -> with($this -> stringContains('incorrect'));
		$this -> controller -> Auth -> expects($this -> once()) -> method('login') -> will($this -> returnValue(false));
		$this -> testAction('/users/login', array('data' => array('User' => array('username' => 'sr'))));
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

	public function testAjaxIndex()
	{
		$_ENV['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this -> testAction('/users/index',array('method' => 'ajax'));
		unset($_ENV['HTTP_X_REQUESTED_WITH']);
		$this -> testAction('/users/index',array('method' => 'get'));
	}

	public function testDelete()
	{
		$this -> testAction('/users/delete/1');
		$this -> testAction('/users/view/1');
		$this -> assertEquals('Inactive', $this -> vars['user']['User']['status']);
	}

	public function testView()
	{
		$this -> testAction('/users/logout');
		$this -> testAction('/users/login', array('data' => array('User' => array('username' => 'mellis'))));
		$map = array(
			array(
				'User.level',
				'user'
			),
			array(
				'User.id',
				3
			)
		);
		$this -> controller = $this -> generate('Users', array('components' => array('Session' => array('read'))));
		$this -> controller -> Session -> expects($this -> any()) -> method('read') -> will($this -> returnValueMap($map));
		$this -> testAction('/users/view/3');
		$this -> assertEquals(true, $this -> vars['userEditPerm']);
	}

	public function testInvalidDelete()
	{
		$this -> controller -> Session -> expects($this -> once()) -> method("setFlash") -> with($this -> stringContains("not able"));
		$this -> testAction('/users/delete/');
	}

	public function testAddInvalidUser()
	{
		// setFlash is called from the add action and by the Auth Component. That is why
		// there are two calls.
		$this -> controller -> Session -> expects($this -> once()) -> method('setFlash') -> with($this -> stringContains('Unable'));
		$data = $this -> kevenUser;
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

	public function testEdit()
	{
		$this -> testAction('users/edit/1', array('method' => 'get'));
		$this -> assertEquals('sroca', $this -> vars['user']['User']['gt_user_name']);
	}

	public function testEditNonExistingUser()
	{
		$this -> controller -> Session -> expects($this -> once()) -> method('setFlash') -> with($this -> stringContains('does not exist'));
		$this -> testAction('users/edit/');
	}

	public function testInvalidEdit()
	{
		$this -> controller -> Session -> expects($this -> once()) -> method('setFlash') -> with($this -> stringContains('Unable'));
		$this -> testAction('users/edit/1');
	}

	public function testValidEdit()
	{
		$data = array('User' => array(
				'gt_user_name' => 'sroca',
				'first_name' => 'Edited',
				'id' => 1
			));
		$this -> controller -> Session -> expects($this -> once()) -> method('setFlash') -> with($this -> stringContains('has been saved'));
		$this -> testAction('users/edit/1', array('data' => $data));
		$user = $this -> User -> findById(1);
		$this -> assertEquals('Edited', $user['User']['first_name']);
	}

}
