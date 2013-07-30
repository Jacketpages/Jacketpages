<?php
/**
 * @author Stephen Roca
 * @since 6/19/2013
 */

App::uses("AppController", "Controller");
App::uses("SessionComponent", "Component");
class UsersControllerTest extends ControllerTestCase
{
	public $fixtures = array('app.user');

	public function testIndex()
	{
		$this -> testAction('/users/index/');
		debug($this -> vars);
	}

}
