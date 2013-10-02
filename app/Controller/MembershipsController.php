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

	// Add in or condition to check dates greater than today.
	public function index($id = null)
	{
		
		$this -> loadModel('Organization');
		$orgName = $this -> Organization -> field('name', array('id' => $id));
		$this -> set('orgName', $orgName);
		
		$this -> loadModel('Membership');
		$db = ConnectionManager::getDataSource('default');		
		$officers = $this -> Membership -> find('all', array(
			'conditions' => array('AND' => array(
					'Membership.org_id' => $id,
					'Membership.role <>' => 'Member',
					'OR' => array(
						$db -> expression('Membership.end_date >= NOW()'),
						'Membership.end_date' => '0000-00-00'
					)
				)),
		));
		
		$members = $this -> Membership -> find('all', array(
			'conditions' => array('AND' => array(
					'Membership.org_id' => $id,
					'Membership.role' => 'Member',
					'OR' => array(
						$db -> expression('Membership.end_date >= NOW()'),
						'Membership.end_date' => '0000-00-00'
					)
				)),
		));
		$pending_members = $this -> Membership -> find('all', array('conditions' => array('AND' => array(
					'Membership.status' => 'Pending',
					'Membership.org_id' => $id
				))));
		$this -> set('officers', $officers);
		$this -> set('members', $members);
		$this -> set('pending_members', $pending_members);
		$this -> set('orgId', $id);
		
	}

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
			$data = $this -> request -> data;
			if ($this -> Membership -> save($data))
			{
				$this -> Session -> setFlash('The membership has been saved.');
				$this -> redirect(array('action' => 'index', $data['Membership']['org_id']));
			}
			else
			{
				$this -> Session -> setFlash('Unable to edit the membership.');
				$this -> redirect(array('action' => 'index', $data['Membership']['org_id']));
			}
		}
	}
	
	// MRE: Who should be able to delete memberships?
	public function delete($id = null, $orgId = null)
	{
		if (!$id)
		{
			$this -> Session -> setFlash(__('Invalid ID for membership', true));
			$this -> redirect(array(
				'controller' => 'memberships',
				'action' => 'index',
				$orgId
			));
		}
		$this -> Membership -> id = $id;
		if ($this -> Membership -> saveField('end_date', date("Y-m-d")))
		{
			$this -> Session -> setFlash(__('Membership deleted.', true));
			$this -> redirect(array(
				'controller' => 'memberships',
				'action' => 'index',
				$orgId
			));
		}
		$this -> Session -> setFlash(__('Membership was not deleted.', true));
		$this -> redirect(array(
			'controller' => 'memberships',
			'action' => 'index',
			$orgId
		));
	}

	public function accept($id = null, $orgId = null)
	{
		if (!$id)
		{
			$this -> Session -> setFlash(__('Invalid ID for membership', true));
			$this -> redirect(array(
				'controller' => 'memberships',
				'action' => 'index'
			));
		}
		$this -> Membership -> id = $id;
		$this -> Membership -> set('status', 'Active');
		$this -> Membership -> set('start_date',date("Y-m-d"));
		if ($this -> Membership -> save())
		{
			$this -> Session -> setFlash('The member has been accepted.');
			$this -> redirect(array(
				'controller' => 'memberships',
				'action' => 'index',
				$orgId
			));
		}
		else
		{
			$this -> Session -> setFlash('Unable to accept the member.');
		}

	}

	public function joinOrganization($org_id)
	{
		$this -> Membership -> set('org_id', $org_id);
		$this -> Membership -> set('user_id', $this -> Session -> read('User.id'));
		$this -> Membership -> set('role', 'Member');
		$this -> Membership -> set('title', 'Member');
		$this -> Membership -> set('status', 'Pending');
		$this -> Membership -> set('room_reserver', 'No');

		if ($this -> Membership -> save())
			$this -> redirect($this -> referer());
	}

}
?>