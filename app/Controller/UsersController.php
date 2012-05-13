<?php
/**
 * User Controller.
 *
 * Manages all methods related to User pages and functionality.
 *
 * @author Stephen Roca
 * @since 03/22/2012
 */

//App::import('Vendor', 'cas', array('file' => 'CAS-1.2.0' . DS . 'CAS.php'));
class UsersController extends AppController
{
   /**
    * Overidden $components, $helpers, and $uses
    */
   public $helpers = array(
      'Html',
      'Form'
   );
   public $components = array('Acl');

   /**
    * A table listing of users.
    */
   public function index()
   {
      $this -> set('users', $this -> User -> find('all', array('limit' => 50)));
   }

   /**
    * Views an individual user's information.
    * @param id - the id of the User to view. Defaults to null.
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
    * @param id - the id of the User to edit. Defaults to null.
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

   /**
    * Logs in a User using Georgia Tech's CAS login system.
    * Writes often used User variables to the Session.
    */
   /*public function login()
    {
    // Set debug mode
    $this -> phpCAS -> setDebug();
    //Initialize phpCAS
    $this -> phpCAS -> client(CAS_VERSION_2_0, Configure::read('CAS.hostname'), Configure::read('CAS.port'), Configure::read('CAS.uri'), false);
    // No SSL validation for the CAS server
    $this -> phpCAS -> setNoCasServerValidation();
    // Force CAS authentication if required
    $this -> phpCAS -> forceAuthentication();

    $GTUsername = $this -> phpCAS -> getUser();
    if ($GTUsername != '')
    {

    }
    }*/

   public function login()
   {
      if ($this -> request -> is('post'))
      {
         $gtUsername = $this -> request -> data['User']['username'];
         $user = $this -> User -> find('first', array('conditions' => array('User.GT_USER_NAME' => $gtUsername)));
         $this -> Session -> write('Auth.User', $user['User']['LEVEL']);
         $this -> Session -> write('USER.NAME', $user['User']['NAME']);
         $this -> Session -> write('USER.LEVEL', $user['User']['LEVEL']);
         if ($this -> Auth -> login())
         {
            $this -> redirect($this -> Auth -> redirect());
         }
         else
         {
            $this -> Session -> setFlash('Your username/password was incorrect.');
         }
      }
   }

   public function logout()
   {

      $this -> Session -> delete('USER');
      $this -> redirect($this -> Auth -> logout());
   }

}
?>