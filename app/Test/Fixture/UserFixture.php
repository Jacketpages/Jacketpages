<?php
/**
 * @author Stephen Roca
 * @since 6/19/2013
 */

class UserFixture extends CakeTestFixture
{
	public $import = 'User';
	public $records = array( array(
			'id' => '1',
			'gt_user_name' => 'sroca',
			'first_name' => 'Stephen',
			'last_name' => 'Roca',
			'email' => 'gimpyroca@gmail.com',
			'level' => 'admin',
			'phone' => '770-316-9464'
		),
		array(
			'id' => '2',
			'gt_user_name' => 'travis',
			'first_name' => 'Travis',
			'last_name' => 'Wagner',
			'email' => 'twagner@gmail.com',
			'level' => 'admin',
			'phone' => '770-316-9464'
		));
}
