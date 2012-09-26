<?php
/**
 * @author Stephen Roca
 * @since 6/22/2012
 */
 
 class SgaPeopleController extends AppController
 {
    public $helpers = array('Form', 'Paginator', 'Html');
    public $components = array('RequestHandler');
    /**
	 * View a list of SgaPeople
	 * @param letter - used to filter SgaPeople's names on thier first letter.
	 */
    // TODO clean up the comments for this function. They still correspond to User
    public function index($letter=null)
    {
       // Writes the search keyword to the Session if the request is a POST
      if ($this -> request -> is('post'))
      {
         $this -> Session -> write('Search.keyword', $this -> request -> data['SgaPerson']['keyword']);
      }
      // Deletes the search keyword if the letter is null and the request is not ajax
      else if (!$this -> RequestHandler -> isAjax() && $letter == null)
      {
         $this -> Session -> delete('Search.keyword');
      }
      // Performs a search on the User table with the following conditions:
      // WHERE (NAME LIKE '%<SEARCH>%' OR GT_USER_NAME LIKE '%<SEARCH>%') AND NAME LIKE '<LETTER>%'
      $this -> loadModel('User');
      $this -> paginate = array(
         'conditions' => array('AND' => array(
               'OR' => array(
                  array($this->User->getVirtualField('NAME') . ' LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%')
                  //array('User.GT_USER_NAME LIKE' => '%' . $this -> Session -> read('Search.keyword') . '%')
               ),
               array($this->User->getVirtualField('NAME') . ' LIKE'=> $letter . '%')
            )),
         'limit' => 20
      );
      // If the request is ajax then change the layout to return just the updated user list
      if ($this -> RequestHandler -> isAjax())
      {
         $this -> layout = 'list';
      }
      // Sets the users variable for the view
      $this -> set('sgapeople', $this -> paginate('SgaPerson'));
    }
    
    /**
	 * View an individual SgaPerson
	 */ 
    public function view()
    {
       // TODO Implement
    }
    
    /**
	 * Add an individual SgaPerson
	 */
    public function add()
    {
       // TODO Implement
    }
    
    /**
	 * Edit an individual SgaPerson
	 */
    public function edit()
    {
       // TODO Implement
    }
 }
?>