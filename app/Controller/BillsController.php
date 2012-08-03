<?php
/**
 * @author Stephen Roca
 * @since 06/26/2012
 */
class BillsController extends AppController
{
	public $helpers = array(
		'Form',
		'Html',
		'Session'
	);
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

	public function add()
	{
		if($this -> request -> is('post'))
		{
			$this -> Bill -> create();
			if ($this -> Bill -> saveAssociated($this -> request -> data))
			{
				$this -> Session -> setFlash('The bill has been saved.');
				//$this -> redirect(array('action' => 'index'));
			}
			else
			{
				$this -> log('Unable to add the user.', 'DEBUG');
				$this -> Session -> setFlash('Unable to add bill.');
			}
		}
		// Set the organization drop down list for
		// creating a new bill
		$id = $this -> Session -> read('USER.ID');
		$this -> loadModel('Membership');
		$orgs[''] = 'Select Organization';
		$ids = $this -> Membership -> find('all', array(
			'fields' => array('Membership.ORG_ID'),
			'conditions' => array('Membership.USER_ID' => $id)
		));
		$this -> loadModel('Organization');
		$orgs['My Organizations'] = $this -> Organization -> find('list', array(
			'fields' => array('NAME'),
			'conditions' => array('Organization.ID' => Set::extract('/Membership/ORG_ID', $ids))
		));
		$na_id = key($this -> Organization -> find('list', array(
			'fields' => array('NAME'),
			'conditions' => array('NAME' => 'N/A')
		)));
		$orgs['My Organizations'][$na_id] = 'N/A';
		$orgs['All'] = $this -> Organization -> find('list', array('fields' => array('NAME')));
		$this -> set('organizations', $orgs);

		// Set the graduate authors drop down list
		// for creating a new bill
		$this -> loadModel('SgaPerson');
		$sga_graduate = $this -> SgaPerson -> find('list', array(
			'fields' => array('NAME_DEPARTMENT'),
			'conditions' => array(
				'STATUS' => 'Active',
				'HOUSE' => 'Graduate'
			),
			'recursive' => 0
		));
		$gradAuthors[''] = "Unknown";
		$gradAuthors['SGA'] = $sga_graduate;
		$this -> set('gradAuthors', $gradAuthors);
		$sga_undergraduate = $this -> SgaPerson -> find('list', array(
			'fields' => array('NAME_DEPARTMENT'),
			'conditions' => array(
				'STATUS' => 'Active',
				'HOUSE' => 'Undergraduate'
			),
			'recursive' => 0
		));
		$underAuthors[''] = "Unknown";
		$underAuthors['SGA'] = $sga_undergraduate;
		$this -> set('underAuthors', $underAuthors);
	}

	public function edit()
	{

	}

}
?>
