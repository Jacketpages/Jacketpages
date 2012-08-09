<?php
class Membership extends AppModel
{
   public $name = 'Membership';
   public $virtualFields = array('NAME' => 'CONCAT(User.FIRST_NAME, " ", User.LAST_NAME)');
    public $belongsTo = array(
    'Organization' => array('className' => 'Organization', 'foreignKey' => 'ORG_ID'),
   'User' => array('className' => 'User', 'foreignKey' => 'USER_ID'));
}
?>