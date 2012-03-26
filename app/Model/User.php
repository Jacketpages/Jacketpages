<?php
	class User extends AppModel
	{
		var $name = 'User';
		var $virtualFields = array('NAME' => 'CONCAT(USER.FIRST_NAME, " ", USER.LAST_NAME)');
		// var $hasOne;
		// var $hasMany;
		// var $validate;
		// var $order;
	}
?>