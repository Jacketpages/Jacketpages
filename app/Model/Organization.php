<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 */
class Organization extends AppModel
{
	public $order = 'Organization.NAME';
	public $virtualFields = array('CONTACT_NAME' => 'CONCAT(User.FIRST_NAME, " ", User.LAST_NAME)');
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'CONTACT_ID'
		),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'CATEGORY'
		)
	);
}
?>
