<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 */
class Organization extends AppModel
{
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
