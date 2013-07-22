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

	public function index($bill_id = null, $state = null)
	{
		date_default_timezone_set('EST5EDT');
		if ($this -> request -> is('get'))
		{
			$lineitems = $this -> LineItem -> find('all', array('conditions' => array(
					'bill_id' => $bill_id,
					'state' => $state
				)));
			$this -> set('lineitems', $lineitems);
		}
		if ($this -> request -> is('post') || $this -> request -> is('put'))
		{
			$conditions = array(
				'conditions' => array(
					'bill_id' => $bill_id,
					'state' => $state
				),
				'recursive' => -1
			);
			$existingLineItems = $this -> LineItem -> find('all', $conditions);
			$existingLineItemIds = Hash::extract($existingLineItems, '{n}.LineItem.id');
			$newLineItems = $this -> request -> data;
			$newLineItemIds = Hash::extract($newLineItems, '{n}.LineItem.id');
			foreach ($existingLineItemIds as $id)
			{
				if (!in_array($id, $newLineItemIds))
				{
					if ($this -> LineItem -> delete($id))
					{
						$this -> updateFieldForLineItemRevisions($id, 'deleted', 1);
						CakeLog::info("Line Item deleted for id: " . $id);
					}
				}
			}

			foreach ($newLineItems as $newLineItem)
			{
				$newLineItem['LineItem']['last_mod_by'] = $this -> Session -> read('User.id');
				if ($newLineItem['LineItem']['id'] != null && $newLineItem['LineItem']['id'] != "")
				{
					$oldLineItem = $this -> LineItem -> findById($newLineItem['LineItem']['id']);
					if (!$this -> compare($newLineItem, $oldLineItem))
					{
						$this -> LineItem -> id = $newLineItem['LineItem']['id'];
						$this -> LineItem -> save($newLineItem);
						$this -> saveLineItemRevision($newLineItem);
					}
				}
				else
				{
					$newLineItem['LineItem']['state'] = $state;
					$newLineItem['LineItem']['type'] = 'General';
					$newLineItem['LineItem']['bill_id'] = $bill_id;
					//set some extra stuff on line item
					if ($this -> LineItem -> save($newLineItem))
					{
						$this -> createLineItemRevision($newLineItem, $this -> LineItem -> getInsertId());
					}
				}
			}
			$this -> redirect(array('controller' => 'bills', 'action' => 'view', $bill_id));

		}
	}

	private function updateFieldForLineItemRevisions($lineItemId, $field, $value)
	{
		$this -> loadModel('LineItemRevision');
		$id = $this -> LineItemRevision -> find('first', array(
			'fields' => array('id'),
			'conditions' => array('line_item_id' => $lineItemId),
			'order' => array('id DESC')
		));
		$this -> LineItemRevision -> id = $id['LineItemRevision']['id'];
		$data = array('LineItemRevision' => array(
				$field => $value,
				'last_mod_by' => $this -> Session -> read('User.id'),
				'last_mod_date' => date('Y-m-d h:i:s')
			));
		$this -> LineItemRevision -> save($data);
	}

	private function createLineItemRevision($data, $id)
	{
		$this -> loadModel('LineItemRevision');
		$this -> LineItemRevision -> create();
		$data['LineItemRevision'] = $data['LineItem'];
		unset($data['LineItem']);
		$data['LineItemRevision']['mod_by'] = $this -> Session -> read('User.id');
		$data['LineItemRevision']['mod_date'] = date('Y-m-d h:i:s');
		$data['LineItemRevision']['revision'] = 1;
		$data['LineItemRevision']['line_item_id'] = $id;
		$this -> LineItemRevision -> save($data);
	}

	private function saveLineItemRevision($data, $id = null)
	{
		$this -> loadModel('LineItemRevision');
		$this -> LineItemRevision -> create();

			$lirid = $this -> LineItemRevision -> find('first', array(
				'fields' => array('id'),
				'conditions' => array('line_item_id' => $data['LineItem']['id']),
				'order' => array('id DESC')
			));
			$lineItemRevision = $this -> LineItemRevision -> findById($lirid['LineItemRevision']['id']);
			$this -> LineItemRevision -> save($this -> prepareRevision($lineItemRevision, $data));
	}
	
	private function prepareRevision($one, $two)
	{
		unset($one['LineItemRevision']['id']);
		$one['LineItemRevision']['name']=$two['LineItem']['name'];
		$one['LineItemRevision']['cost_per_unit']=$two['LineItem']['cost_per_unit'];
		$one['LineItemRevision']['total_cost']=$two['LineItem']['total_cost'];
		$one['LineItemRevision']['amount']=$two['LineItem']['amount'];
		$one['LineItemRevision']['account']=$two['LineItem']['account'];
		$one['LineItemRevision']['type'] = 'General';
		$one['LineItemRevision']['mod_by'] = $this -> Session -> read('User.id');
		$one['LineItemRevision']['mod_date'] = date('Y-m-d h:i:s');
		$one['LineItemRevision']['revision'] = max(1, $one['LineItemRevision']['revision'] + 1);
		return $one;
	}
	
	private function compare($new, $old)
	{
		$flag = true;
		if (strcmp($new['LineItem']['line_number'], $old['LineItem']['line_number']))
		{
			$this -> updateFieldForLineItemRevisions($new['LineItem']['id'], 'line_number', $new['LineItem']['line_number']);
			debug("updated");
		}
		if (strcmp($new['LineItem']['name'], $old['LineItem']['name']))
			$flag = false;
		else if (strcmp($new['LineItem']['cost_per_unit'], $old['LineItem']['cost_per_unit']))
			$flag = false;
		else if (strcmp($new['LineItem']['quantity'], $old['LineItem']['quantity']))
			$flag = false;
		else if (strcmp($new['LineItem']['total_cost'], $old['LineItem']['total_cost']))
			$flag = false;
		else if (strcmp($new['LineItem']['amount'], $old['LineItem']['amount']))
			$flag = false;
		else if (strcmp($new['LineItem']['account'], $old['LineItem']['account']))
			$flag = false;
		return $flag;
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
			$this -> request -> data = $this -> stripDollarSign($this -> request -> data, 'LineItem', array(
				'cost_per_unit',
				'quantity'
			));
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

	// Take out from state and pass it in through the form.
	public function copy($bill_id, $to_state)
	{
		$lineitems = $this -> LineItem -> findAllByBillIdAndState($bill_id, $this -> request -> data['LineItem']['state']);
		for ($i = 0; $i < count($lineitems); $i++)
		{
			$lineitems[$i]['LineItem']['id'] = null;
			$lineitems[$i]['LineItem']['state'] = $to_state;
		}
		if ($this -> LineItem -> saveAll($lineitems))
		{
			$this -> Session -> setFlash('Line Items were copied.');
			$this -> redirect(array(
				'controller' => 'bills',
				'action' => 'view',
				$bill_id
			));
		}
		else
		{
			$this -> Session -> setFlash('Line Items copy failed.');
		}
	}

	private function stripDollarSign($data = array(), $model = "", $fields = array())
	{
		foreach ($fields as $field)
		{
			$data[$model][$field] = str_replace('$', '', $data[$model][$field]);
		}
		return $data;
	}

	public function strikeLineItem($id)
	{
		$lineitem = $this -> LineItem -> findById($id, array('bill_id'));
		$this -> LineItem -> id = $id;
		$this -> LineItem -> saveField('struck', 1);
		$this -> updateFieldForLineItemRevisions($id, 'struck', 1);
		$this -> redirect(array(
			'controller' => 'bills',
			'action' => 'view',
			$lineitem['LineItem']['bill_id']
		));
	}

	public function unstrikeLineItem($id)
	{
		$lineitem = $this -> LineItem -> findById($id, array('bill_id'));
		$this -> LineItem -> id = $id;
		$this -> LineItem -> saveField('struck', 0);
		$this -> updateFieldForLineItemRevisions($id, 'struck', 0);
		$this -> redirect(array(
			'controller' => 'bills',
			'action' => 'view',
			$lineitem['LineItem']['bill_id']
		));
	}

}
?>
