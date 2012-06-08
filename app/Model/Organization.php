<?php 
class Organization extends AppModel
{
   public $belongsTo = array(
      'User' => array(
         'className' => 'User',
         'foreignKey' => 'CNTCT_ID'
      )
   );
}
?>
