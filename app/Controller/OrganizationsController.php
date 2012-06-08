<?php
class OrganizationsController extends AppController
{
   /**
    * Overidden $components, $helpers, and $uses
    */
   public $helpers = array(
      'Html',
      'Form',
      'Paginator',
      'Js'
   );
   public $components = array(
      'Acl',
      'RequestHandler',
      'Session'
   );

   public function index()
   {
      $this -> set('organizations', $this -> paginate('Organization'));
      if ($this -> RequestHandler -> isAjax())
      {
         $this -> layout = 'list';
      }
   }

   public function view($id = null)
   {
      // Set which organization to retrieve from the database.
      $this -> Organization -> id = $id;
      $this -> set('organization', $this -> Organization -> read());
      $this -> loadModel('Membership');
      $president = $this -> Membership -> find('first', array(
         'conditions' => array('AND' => array(
               'Membership.ORG_ID' => $id,
               'Membership.ROLE' => 'President',
               'Membership.START_DATE LIKE' => '2011%'
            )),
         'joins' => array( array(
               'table' => 'users',
               'alias' => 'User',
               'type' => 'INNER',
               'conditions' => array('User.ID = Membership.USER_ID', )
            )),
            'fields' => array('Membership.ROLE', 'CONCAT(User.FIRST_NAME," ", User.LAST_NAME) as NAME', 'Membership.STATUS', 'Membership.TITLE')
      ));
      $this -> set('president', $president);
      $treasurer = $this -> Membership -> find('first', array(
         'conditions' => array('AND' => array(
               'Membership.ORG_ID' => $id,
               'Membership.ROLE' => 'Treasurer',
               'Membership.START_DATE LIKE' => '2011%'
            )),
         'joins' => array( array(
               'table' => 'users',
               'alias' => 'User',
               'type' => 'INNER',
               'conditions' => array('User.ID = Membership.USER_ID', )
            )),
            'fields' => array('Membership.ROLE', 'CONCAT(User.FIRST_NAME," ", User.LAST_NAME) as NAME', 'Membership.STATUS', 'Membership.TITLE')
      ));
      $this -> set('treasurer', $treasurer);
      $advisor = $this -> Membership -> find('first', array(
         'conditions' => array('AND' => array(
               'Membership.ORG_ID' => $id,
               'Membership.ROLE' => 'Advisor',
               'Membership.START_DATE LIKE' => '2011%'
            )),
         'joins' => array( array(
               'table' => 'users',
               'alias' => 'User',
               'type' => 'INNER',
               'conditions' => array('User.ID = Membership.USER_ID', )
            )),
            'fields' => array('Membership.ROLE', 'CONCAT(User.FIRST_NAME," ", User.LAST_NAME) as NAME', 'Membership.STATUS', 'Membership.TITLE')
      ));
      $this -> set('advisor', $advisor);
      $officers = $this -> Membership -> find('all', array(
         'conditions' => array('AND' => array(
               'Membership.ORG_ID' => $id,
               'Membership.ROLE' => 'Officer',
               'Membership.START_DATE LIKE' => '2011%'
            )),
         'joins' => array( array(
               'table' => 'users',
               'alias' => 'User',
               'type' => 'INNER',
               'conditions' => array('User.ID = Membership.USER_ID', )
            )),
            'fields' => array('Membership.ROLE', 'CONCAT(User.FIRST_NAME," ",User.LAST_NAME) as NAME', 'Membership.STATUS', 'Membership.TITLE')
      ));
      $this -> set('officers', $officers);
   }

   public function add()
   {
      //TODO Implement
   }

   public function edit()
   {
      //TODO Implement
   }

}
?>
