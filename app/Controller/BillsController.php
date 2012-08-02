<?php
/**
 * @author Stephen Roca
 * @since 06/26/2012
 */
class BillsController extends AppController
{
	public $helpers = array('Form', 'Html', 'Session');
	  public function index()
	  {
	     $this -> set('bills', $this -> paginate('Bill'));
	  }
	  
	  public function view($id = null)
	  {
	  	// Set which organization to retrieve from the database.
      $this -> Bill -> id = $id;
      $this -> set('bill', $this -> Bill -> read());
	  }
}
?>
