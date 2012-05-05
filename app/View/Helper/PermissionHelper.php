<?php
//App::uses('AppHelper', 'View/Helper');

//@TODO include comments
class PermissionHelper extends AppHelper 
{
   public $helpers = array('Session');
   // @TODO probably not need. isUser will give the same result
   public function isLoggedIn()
   {
      if ($this -> Session -> read('User.level') != '')
      {
         return true;
      }
      return false;
   }
   
   public function isUser()
   {
      return $this -> isLevel('user');
   }
   
   public function isMember($user_id, $org_id)
   {
      /**
       * @TODO After implementing memberships and organizations
       * Load the membership model and do a find for that org_id
       * and user_id combination
       */
    
   }
   
   public function isSGA()
   {
      return $this -> isLevel('sga');
   }
   
   public function isAdmin()
   {
      return $this -> isLevel('admin');
   }
   
   private function isLevel($level)
   {
      if ($this -> Session -> read('User.level') == $level)
      {
         return true;
      }
      return false;
   }
}