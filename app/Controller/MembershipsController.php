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
		$this -> set('isOfficer', $this -> isOfficer($id));
		$this -> set('isMember', $this -> isMember($id));
		if (!($this -> isOfficer($id) || $this -> isMember($id) || $this -> isLace()))
			$this -> redirectHome();
		if ($id == null)
		{
			$this -> Session -> setFlash('Please select your organization to view.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}

		$this -> loadModel('Organization');
		$orgName = $this -> Organization -> field('name', array('id' => $id));
		$this -> set('orgName', $orgName);

		$this -> loadModel('Membership');
		$officers = $this -> getMembers($id, array(
			'Officer',
			'President',
			'Treasurer',
			'Advisor'
		));

		$members = $this -> getMembers($id, array('Member'));
		$count = find('count', $members);
		$pending_members = $this -> getMembers($id, array('Member'), false, array('Pending'));
		$this -> set('officers', $officers);
		$this -> set('members', $members);
		$this -> set('pending_members', $pending_members);
		$this -> set('orgId', $id);
		$this -> set('count', $count);

	}

	/**
	 * Edits an individual Membership
	 * @param id - a membership's id
	 */
	public function edit($id = null)
	{
		$org_id = $this -> Membership -> field('org_id', array('id' => $id));
		if (!($this -> isOfficer($org_id) || $this -> isLace()))
			$this -> redirectHome();
		if ($id == null)
		{
			$this -> Session -> setFlash('Please select your organization to view.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}

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
				$this -> redirect(array(
					'action' => 'index',
					$data['Membership']['org_id']
				));
			}
			else
			{
				$this -> Session -> setFlash('Unable to edit the membership.');
				$this -> redirect(array(
					'action' => 'index',
					$data['Membership']['org_id']
				));
			}
		}
	}

	/**
	 * Add an individual membership
	 */
	function add($id = null)
	{
		if (!($this -> isOfficer($id) || $this -> isLace()))
			$this -> redirectHome();
		if (!$id && empty($this -> data))
		{
			$this -> Session -> setFlash(__('Invalid organization.', true));
			$this -> redirect('/');
		}
		$this -> loadModel('User');
		if ($this -> request -> is('post') && !$this -> User -> exists($this -> request -> data['Membership']['user_id']))
		{
			$this -> Session -> setFlash("Please select a valid JacketPages user to add.");
			$this -> redirect(array(
				'action' => 'index',
				$id
			));
		}
		/*if ($id) {
		 $orgId = $id;
		 $userId = $this -> getUser();
		 $this -> loadModel('User');
		 $this -> loadModel('Organization');
		 $user = $this -> User -> find('all', array('conditions' => array('User.id' =>
		 $userId), 'recursive' => 0));
		 $org = $this -> Organization -> find('all', array('conditions' =>
		 array('Organization.id' => $orgId), 'recursive' => 0));
		 $this -> set('user', $user);
		 $this -> set('org', $org);
		 if (!$this -> isLevel('admin') && !$this -> _isOfficer($orgId)) {
		 $this -> Session -> setFlash(__('You are not an officer of this organization.',
		 true));
		 $this -> redirect(array('controller' => 'organizations', 'action' => 'view',
		 $orgId));
		 }
		 }*/
		$this -> loadModel('Organization');
		$orgName = $this -> Organization -> field('name', array('id' => $id));
		$this -> set('orgName', $orgName);
		$this -> set('orgId', $id);

		if (!empty($this -> data))
		{
			$orgId = $this -> data['Membership']['org_id'];
			$userId = $this -> data['Membership']['user_id'];
			if (($this -> isMember($orgId, $userId) || $this -> isPendingMember($orgId, $userId))
				&& $this -> data['Membership']['role'] == 'Member')
			{
				$this -> Session -> setFlash(__('Membership already exists for that user.', true));				
			}
			else
			{
				$this -> Membership -> create();
				$this -> Membership -> set('status','Active');
				if ($this -> Membership -> save($this -> data))
				{
					$this -> Session -> setFlash(__('The membership has been saved.', true));
					$this -> redirect(array(
						'controller' => 'memberships',
						'action' => 'index',
						$orgId
					));			
				}
				else
				{
					$this -> Session -> setFlash(__('The membership could not be saved. Please try again.', true));
				}	
			}		
		}

	}

	// MRE: Who should be able to delete memberships?
	public function delete($id = null, $orgId = null)
	{
		if (!($this -> isOfficer($orgId) || $this -> isLace()))
			$this -> redirectHome();
		if ($id == null || $orgId == null)
		{
			$this -> Session -> setFlash('Please select your organization to view.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
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

	// Reject a pending membership
	// MRE TO DO: make sure permissions are set...
	public function reject($id = null, $orgId = null)
	{
		if (!($this -> isOfficer($orgId) || $this -> isLace()))
			$this -> redirectHome();
		if ($id == null || $orgId == null)
		{
			$this -> Session -> setFlash('Please select your organization to view.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));

		}

		$this -> Membership -> id = $id;
		if ($this -> Membership -> delete())
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
		if (!($this -> isOfficer($orgId) || $this -> isLace()))
			$this -> redirectHome();
		if ($id == null || $orgId == null)
		{
			$this -> Session -> setFlash('Please select your organization to view.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'my_orgs',
				$this -> Session -> read('User.id')
			));
		}

		$this -> Membership -> id = $id;
		$this -> Membership -> set('status', 'Active');
		$this -> Membership -> set('start_date', date("Y-m-d"));
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

	public function joinOrganization($org_id = null)
	{
		if ($org_id == null)
		{
			$this -> Session -> setFlash('Please select an organization.');
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'index'
			));
		}
		$this -> Membership -> set('org_id', $org_id);
		$this -> Membership -> set('user_id', $this -> Session -> read('User.id'));
		$this -> Membership -> set('role', 'Member');
		$this -> Membership -> set('title', 'Member');
		$this -> Membership -> set('status', 'Pending');
		$this -> Membership -> set('room_reserver', 'No');
		$this -> Membership -> set('start_date', date('Y-m-d'));

		if (!$this -> isPendingMember($org_id))
		{
			if ($this -> Membership -> save())
			{
				$this -> Session -> setFlash('Request sent to join organization.');
			}
			else
			{
				$this -> Session -> setFlash('Unable to join the organization.');
			}
		}
		$this -> redirect($this -> referer());

	}

}
?>