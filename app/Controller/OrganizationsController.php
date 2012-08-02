<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 */
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

   //TODO this method needs some cleanup still
   public function index($letter = null, $category = null)
   {
      // Writes the search keyword to the Session if the request is a POST
      if ($this -> request -> is('post'))
      {
         $this -> Session -> write('Search.keyword', $this -> request -> data['Organization']['keyword']);
         $this -> Session -> write('Search.category', $this -> request -> data['Organization']['category']);
      }
      // Deletes the search keyword if the letter is null and the request is not ajax
      else if (!$this -> RequestHandler -> isAjax() && $letter == null)
      {
         
         $this -> Session -> delete('Search');
      }
      // Performs a search on the Organization table with the following conditions:
      // WHERE 
      $this -> paginate = array(
         'conditions' => array('AND' => array(
               'OR' => array(
                  array('Organization.NAME LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%'),
                  array('Organization.DESCRIPTION LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%'),
                  array('Organization.SHORT_NAME LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%')
               ),
               array('Organization.NAME LIKE' => $letter . '%'),
               array('Organization.STATUS' => 'Active'),
               array('Category.NAME LIKE' => $this -> Session -> read('Search.category') . '%')
            )),
         'limit' => 20
      );
      // If the request is ajax then change the layout to return just the updated user list
      if ($this -> RequestHandler -> isAjax())
      {
         $this -> layout = 'list';
      }
      // Sets the users variable for the view
      $this -> set('organizations', $this -> paginate('Organization'));
      $orgNames = $this -> Organization -> find('all', array('fields' => 'NAME'));
	  // Create the array for the javascript autocomplete
      $just_names = array();
      foreach($orgNames as $orgName)
      {
         $just_names[] = $orgName['Organization']['NAME'];
      }
      $this -> set('names_to_autocomplete', $just_names);
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

   public function edit($id = null)
   {
      $this -> Organization -> id = $id;
      if ($this -> request -> is('get'))
      {
         $this -> request -> data = $this -> Organization -> read();
         $this -> set('organization', $this -> Organization -> read());
      }
      else
      {
         if ($this -> Organization -> save($this -> request -> data))
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
