<?php
/**
 * @author Stephen Roca
 * @since 8/26/2013
 */

class BudgetsController extends AppController {
	public $helpers = array('Form', 'Html', 'Session', 'Number', 'Time');

	public $components = array('RequestHandler', 'Session');

	public function index($org_id = null) {
		if ($org_id != null) {
			$this -> set('orgId', $org_id);
		}
		$this -> set('budgets', $this -> paginate('Budget'));
	}

	public function submit($org_id = null, $redirect = true) {
		if ($this -> request -> is('get')) {
			$this -> loadModel('Membership');
			$this -> loadModel('Organization');
			$this -> Organization -> id = $org_id;
			$organization = $this -> Organization -> read();

			$this -> set('organization', $organization);
			$charter_date = DateTime::createFromFormat("Y-m-d", $organization['Organization']['charter_date']);
			$this -> set('yearsActive', date('Y') - $charter_date -> format("Y"));
			$this -> set('orgName', $organization['Organization']['name']);
			$this -> set('tier', $this -> roman_numerals($organization['Organization']['tier']));

			//$members = $this -> Membership -> find('all', array('conditions' =>
			// array('Organization.id' => $org_id,'Membership.role !=' => 'Member','end_date'
			// => '0000-00-00')));
			$this -> set('member_count', $this -> Membership -> find('count', array('conditions' => array('Organization.id' => $org_id, 'Membership.end_date' => null))));
			$president = $this -> Membership -> findByOrgIdAndRoleAndEndDate($org_id, 'President', null);
			$treasurer = $this -> Membership -> findByOrgIdAndRoleAndEndDate($org_id, 'Treasurer', null);
			$advisor = $this -> Membership -> findByOrgIdAndRoleAndEndDate($org_id, 'Advisor', null);

			$this -> set('president', $president);
			$this -> set('treasurer', $treasurer);
			$this -> set('advisor', $advisor);
			$this -> loadModel('LineItemCategory');
			$categories = $this -> LineItemCategory -> find('all');
			$this -> set('category_names', Hash::extract($categories, '{n}.LineItemCategory.name'));
			$this -> set('category_descriptions', Hash::extract($categories, '{n}.LineItemCategory.description'));
			$this -> set('budgetLineItems', array());
			debug($this -> getFiscalYear());
			$this -> request -> data = $this -> Budget -> findByOrgIdAndFiscalYear($org_id,'20' . ($this -> getFiscalYear()+2));
			//$this ->getStudentType('sroca3');
		} else {
			debug($this -> request -> data);
			$this -> Budget -> data = $this -> request -> data;
			$this -> Budget -> set('status', 'Submitted');
			$this -> Budget -> set('last_mod_by', $this -> Session -> read('User.id'));
			debug($this -> Budget -> data);
			if ($this -> Budget -> save($this -> request -> data)) {
				if ($redirect)
					$this -> redirect(array('controller' => 'budgetlineitems', 'action' => 'edit',$org_id));
			} else {
				$this -> Session -> setFlash('Unable to save budget.');
			}
		}
	}

	private function getStudentType($gtid) {
		$json = file_get_contents('http://m2.cip.gatech.edu/proxy/iam-dev01.iam.gatech.edu/directory/people.json?uid=' . $gtid);
		$info = json_decode($json);
		debug($info);
	}

	public function fundraising() {

	}

	public function expenses() {

	}

	public function assets() {

	}

	public function member_contributions() {

	}

	public function summary() {

	}

	public function view() {

	}

}
?>
