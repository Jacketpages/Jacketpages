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
			'status' => 'Active',
			'first_name' => 'Stephen',
			'last_name' => 'Roca',
			'email' => 'gimpyroca@gmail.com',
			'level' => 'admin',
			'phone' => '770-316-9464'
		),
		array(
			'id' => '2',
			'gt_user_name' => 'travis',
			'status' => 'Active',
			'first_name' => 'Travis',
			'last_name' => 'Wagner',
			'email' => 'twag@gmail.com',
			'level' => 'admin',
			'phone' => '770-316-9464'
		),
		array(
			'id' => '3',
			'gt_user_name' => 'mellis',
			'status' => 'Active',
			'first_name' => 'Michael',
			'last_name' => 'Ellis',
			'email' => 'melis@gmail.com',
			'level' => 'user',
			'phone' => '770-316-9464'
		));
}
