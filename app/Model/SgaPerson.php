<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 */

class SgaPerson extends AppModel
{
   public $belongsTo = array(
      'User' => array(
         'className' => 'User',
         'foreignKey' => 'USER_ID'
      ));
}

?>