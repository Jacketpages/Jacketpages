<?php
/**
 * Line Items Controller
 *
 * @author Stephen Roca
 * @since 09/03/2012
 */
class LineItemsController extends AppController
{
	public $helpers = array('Form');
	
	public function add($id = null)
	{
		$this -> loadModel('Bill');
		$this -> set('bill', $this -> Bill -> find('first',array('conditions' => array('Bill.ID' => $id), 'fields' => array('TITLE', 'TYPE'))));
		// If the request is a post attempt to save the line item. 
		// If this fails then log the failure and set a flash message.
		if ($this -> request -> is('post'))
		{
			$this -> Line_Item -> create();
			if ($this -> Line_Item -> saveAssociated($this -> request -> data))
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
	
	public function travel_calculator()
	{
		
	}
}
?>
