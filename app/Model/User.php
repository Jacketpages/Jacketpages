<?php
/**
 * @author Stephen Roca
 * @since 3/22/2012
 * @formatter:off
            Array
             (
                [User] => Array
                    (
                        [ID] => 
                        [GT_USER_NAME] =>
                        [PHONE] => 
                        [EMAIL] =>
                        [FIRST_NAME] => 
                        [LAST_NAME] => 
                        [LEVEL] => 
                        [LOCAL_ADDR] => 
                        [HOME_ADDR] => 
                        [NAME] => 
                    )
            
                [LOCAL_ADDR] => Array
                    (
                        [ID] => 
                        [ADDR_LINE_1] => 
                        [ADDR_LINE_2] => 
                        [COUNTRY] => 
                        [CITY] => 
                        [STATE] => 
                        [ZIP] => 0
                    )
            
                [HOME_ADDR] => Array
                    (
                        [ID] => 
                        [ADDR_LINE_1] => 
                        [ADDR_LINE_2] => 
                        [COUNTRY] => 
                        [CITY] => 
                        [STATE] => 
                        [ZIP] => 
                    )
            
            )
 * @formatter:on
 */
class User extends AppModel
{
   var $name = 'User';
   var $virtualFields = array('NAME' => 'CONCAT(USER.FIRST_NAME, " ", USER.LAST_NAME)');
   // var $hasOne;
 //public $hasMany = array('Location' => array('className' => 'Location', 'foreignKey' => 'ID'));
      public $belongsTo = array(
      'LOCAL_ADDR' => array(
      'className' => 'Location',
      'foreignKey' => 'LOCAL_ADDR'
      ),
      'HOME_ADDR' => array(
      'className' => 'Location',
      'foreignKey'
    => 'HOME_ADDR'
      )
   );
   public $validate = array(
      'GT_USER_NAME' => array('rule' => 'notEmpty'),
      'FIRST_NAME' => array('rule' => 'notEmpty'),
      'LAST_NAME' => array('rule' => 'notEmpty'),
   );
   // var $order;
}
?>