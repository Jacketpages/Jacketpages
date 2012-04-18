<?php
/**
 * User Controller.
 *
 * Manages all methods related to User pages and functionality.
 *
 * @author Stephen Roca
 * @since 03/22/2012
 */
class UsersController extends AppController
{
   /**
    * Overidden $components, $helpers, and $uses
    */
   public $helpers = array(
      'Html',
      'Form'
   );
   public $components = array('Session');

   /**
    * A table listing of users.
    */
   public function index()
   {
      $this -> set('users', $this -> User -> find('all'));
   }

   /**
    * Views an individual user's information.
    */
   public function view($id = null)
   {
      $this -> User -> id = $id;
      $this -> set('user', $this -> User -> read());
   }

   /**
    * Allows the addition of a User.
    */
   public function add()
   {
      if ($this -> request -> is('post'))
      {
         $this -> User -> create();
         debug($this -> request -> data);
         if ($this -> User -> saveAssociated($this -> request -> data))
         {
            $this -> Session -> setFlash('The user has been saved.');
            $this -> redirect(array('action' => 'index'));
         }
         else
         {
            $this -> log('Unable to add the user.', 'DEBUG');
            $this -> Session -> setFlash('Unable to add the user.');
         }
      }
   }

   /**
    * Allows the editing of a specific User.
    */
   public function edit($id = null)
   {
      $this -> User -> id = $id;
      if ($this -> request -> is('get'))
      {
         $this -> request -> data = $this -> User -> read();
      }
      else
      {
         if ($this -> User -> save($this -> request -> data))
         {
            $this -> Session -> setFlash('The user has been saved.');
            $this -> redirect(array('action' => 'index'));
         }
         else
         {
            $this -> Session -> setFlash('Unable to edit the user.');
         }
      }
   }

}
?>