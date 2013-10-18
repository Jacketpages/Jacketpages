<?php 
class Budget extends AppModel
{
	
	public $recursive = -1;
	public $belongsTo = array('Organization' => array(
			'className' => 'Organization',
			'foreignKey' => 'org_id'
		));
}
?>