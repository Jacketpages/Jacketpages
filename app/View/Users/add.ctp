<?php
/**
 * @author Stephen Roca
 * @since 03/22/2012
 */
$this -> Html -> addCrumb('Users', '/users');
$this -> Html -> addCrumb('Add', '/users/add');

$this -> extend('/Common/common');
$this -> assign('title', 'Add User');
$this -> start('middle');
echo '<div id="notification">';
echo "Creating a profile enables you to use more features of JacketPages. Please read the privacy policy before continuing. A link is located in the footer.";
echo '</div>';
echo $this -> Form -> create('User');	
if ($admin || $lace) {
	echo $this -> Form -> input('gt_user_name', array('label' => 'GT User Name'));
} else {
	echo $this -> Form -> input('gt_user_name', array(
		'type' => 'text',
		'label' => 'GT Username*',
		'hidden' => false,
		'readonly' => 'readonly',
		'default' => $this -> Session -> read('User.gt_user_name')
	));
}			
	echo $this -> Form -> input('first_name', array('label' => 'First Name'));
	echo $this -> Form -> input('last_name', array('label' => 'Last Name'));	
	echo $this -> Form -> input('phone', array('label' => 'Phone Number'));
	echo $this -> Form -> input('email', array('label' => 'Email'));
	echo $this -> Form -> end('Save User');
$this -> end();
?>