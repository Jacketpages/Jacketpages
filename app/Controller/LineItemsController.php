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

	public function beforeFilter()
	{
		//$this -> Security -> unlockedActions = array('index');
		//$this->Security->csrfCheck = false;
		//$this->Security->validatePost = false;
	}

	public function index($id = null, $state = null)
	{
		debug($this -> request -> data);
		debug($_POST);
		if ($this -> request -> is('get'))
		{
			$lineitems = $this -> LineItem -> find('all', array('conditions' => array(
					'bill_id' => $id,
					'state' => $state
				)));
			$this -> set('lineitems', $lineitems);
		}

		if ($this -> request -> is('post'))
		{
			$oldLineItems = $this -> LineItem -> find('all', array('conditions' => array(
					'bill_id' => $id,
					'state' => $state
				)));
			$newLineItems = $this -> request -> data;
			$this -> loadModel('LineItemRevisions');
			foreach ($newLineItems as $lineitem)
			{
				// If it's a new line item then save it.
				// Changelog insert is managed by a MySQL Trigger
				if ($lineitem['LineItem']['id'] == null)
				{
					$this -> LineItem -> create();
					//if (!$this -> LineItem -> saveAll($lineitem))
					{
						//log error, set flash message, and get out of method
					}
				}
				else
				{
					foreach ($oldLineItems as $oldlineitem)
					{
						if ($lineitem['LineItem']['id'] == $oldlineitem['LineItem']['id'])
						{
							if (!compare($lineitem, $oldlineitem))
							{
								$this -> LineItem -> id = $lineitem['LineItem']['id'];
								$this -> LineItem -> save($lineitem);
								$this -> LineItemRevision -> create();
								$this -> LineItemRevision -> set('revision', $this -> LineItemRevision -> query("SELECT MAX(REVISION) FROM LINE_ITEM_REVISIONS WHERE LINE_ITEM_ID = " . $lineitem['LineItem']['id'] . ";"));
								$this -> LineItemRevision -> set('deleted', 0);
								$this -> LineItemRevision -> save($lineitem);
								break;
							}
						}
					}
				}
			}
		}
	}

	public function view($id = null)
	{
		$this -> set('lineitem', $this -> LineItem -> findById($id));
	}

	/**
	 * Adds a new line item
	 * @param id - the id of the bill that the line item is added to
	 */
	public function add($id = null)
	{
		$this -> loadModel('Bill');
		$this -> set('bill', $this -> Bill -> find('first', array(
			'conditions' => array('Bill.id' => $id),
			'fields' => array(
				'title',
				'type',
				'id'
			)
		)));
		// If the request is a post attempt to save the line item.
		// If this fails then log the failure and set a flash message.
		if ($this -> request -> is('post'))
		{
			$this -> LineItem -> create();
			if ($this -> LineItem -> saveAssociated($this -> request -> data))
			{
				$this -> Session -> setFlash('The user has been saved.');
				$this -> redirect(array(
					'controller' => 'bills',
					'action' => 'view',
					$id
				));
			}
			else
			{
				$this -> Session -> setFlash('Unable to add the user.');
			}
		}
	}

	public function delete($id = null)
	{
		$lineitem = $this -> LineItem -> findById($id, array('bill_id'));
		if ($this -> LineItem -> delete($id))
		{
			$this -> Session -> setFlash(__('Line Item deleted.', true));
			$this -> redirect(array(
				'controller' => 'bills',
				'action' => 'view',
				$lineitem['LineItem']['bill_id']
			));
		}
		$this -> Session -> setFlash(__('Line Item was not deleted.', true));
	}

	//TODO Doesn't work yet. Still putting it together.
	public function edit($id)
	{
		$this -> loadModel('Bill');
		$this -> set('bill', $this -> Bill -> find('first', array(
			'conditions' => array('Bill.id' => $id),
			'fields' => array(
				'title',
				'type',
				'id'
			)
		)));
		$this -> LineItem -> id = $id;
		if ($this -> request -> is('get'))
		{
			$this -> request -> data = $this -> LineItem -> read();
			$this -> set('membership', $this -> LineItem -> read(null, $id));
		}
		else
		{
			if ($this -> LineItem -> save($this -> request -> data))
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

	/**
	 * Displays a travel calculator to calculate the cost of traveling
	 * and how much can be allocated for the travel through a bill.
	 */
	public function travel_calculator()
	{

	}

	private function compare($one, $other)
	{
		$flag = true;
		if (strcmp($one['LineItem']['name'], $other['LineItem']['name']))
			$flag[] = false;
		else if (strcmp($one['LineItem']['cost_per_unit'], $other['LineItem']['cost_per_unit']))
			$flag[] = false;
		else if (strcmp($one['LineItem']['quantity'], $other['LineItem']['quantity']))
			$flag[] = false;
		else if (strcmp($one['LineItem']['total_cost'], $other['LineItem']['total_cost']))
			$flag[] = false;
		else if (strcmp($one['LineItem']['amount'], $other['LineItem']['amount']))
			$flag[] = false;
		else if (strcmp($one['LineItem']['account'], $other['LineItem']['account']))
			$flag[] = false;
		return $flag;
	}

	// Take out from state and pass it in through the form.
	public function copy($bill_id, $from_state, $to_state)
	{
		debug($this -> request -> data);
		$lineitems = $this -> LineItem -> findAllByBillIdAndState($bill_id, $this -> request ->data['LineItem']['state']);
		for ($i = 0; $i < count($lineitems); $i++)
		{
			$lineitems[$i]['LineItem']['id'] = null;
			$lineitems[$i]['LineItem']['state'] = $to_state;
		}
		if ($this -> LineItem -> saveAll($lineitems))
		{
			$this -> Session -> setFlash('Line Items were copied.');
			$this -> redirect(array('controller' => 'bills', 'action'=>'view', $bill_id));
		}
		else
		{
			$this -> Session -> setFlash('Line Items copy failed.');
		}
	}

}
?>
