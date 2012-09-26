<?php
/**
 * Memberships Controller
 *
 * @author Stephen Roca
 * @since 06/08/2012
 */
class MembershipsController extends AppController
{
	public $helpers = array(
		'Form',
		'Html'
	);
	
	/**
	 * Edits an individual Membership
	 * @param id - a membership's id
	 */
	public function edit($id = null)
	{
		$this -> Membership -> id = $id;
		if ($this -> request -> is('get'))
		{
			$this -> request -> data = $this -> Membership -> read();
			$this -> set('membership', $this -> Membership -> read(null, $id));
		}
		else
		{
			if ($this -> Membership -> save($this -> request -> data))
			{
				$this -> Session -> setFlash('The membership has been saved.');
				$this -> redirect(array('action' => 'index'));
			}
			else
			{
				$this -> Session -> setFlash('Unable to edit the membership.');
			}
		}
	}

}
?>