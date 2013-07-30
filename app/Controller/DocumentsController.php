<?php
/**
 * @author Stephen Roca
 * @since 10/28/2012
 */

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
class DocumentsController extends Controller
{
	public $helpers = array(
		'Html',
		'Permission',
		'Session'
	);

	public function index($id = null)
	{
		if ($id != null)
		{
			$this -> loadModel('Organization');
			$org = $this -> Organization -> read(null, $id);
			if ($org['Organization']['status'] == 'Frozen')
			{
				$this -> Session -> setFlash(__('Invalid organization', true));
				$this -> redirect(array(
					'controller' => 'organizations',
					'action' => 'index'
				));
			}
			$this -> set('organization', $org);
			$this -> Document -> recursive = 0;
			$this -> set('documents', $this -> paginate(array('Document.org_id' => $id)));
			$this -> set('id', $id);
		}
		else
		{
			$this -> Session -> setFlash("Invalid organization.");
			$this -> redirect(array(
				'controller' => 'organizations',
				'action' => 'index'
			));
		}
	}

	public function add($id = null)
	{
		if ($this -> request -> is('post'))
		{
			move_uploaded_file($this -> request -> data['Document']['submittedfile']['tmp_name'], APP . DS . "Documents" . DS . $id . DS . $this -> request -> data['Document']['submittedfile']['name']);

			$data = array(
				'org_id' => $id,
				'name' => $this -> request -> data['Document']['submittedfile']['name'],
				'path' => '',
				'last_updated' => 'NOW()'
			);
			if ($this -> request -> is('post'))
			{
				$this -> Document -> create();
				if ($this -> Document -> save($data))
				{
					$this -> Session -> setFlash('The user has been saved.');
					$this -> redirect(array(
						'action' => 'index',
						$id
					));
				}
				else
				{
					$this -> Session -> setFlash('Unable to add the user.');
				}
			}
		}
	}

	/**
	 * Fix this function later to where the file is deleted after the database entry
	 * is deleted.
	 */
	public function delete($id)
	{
		if (!$id)
		{
			$this -> Session -> setFlash(__('Invalid ID for document', true));
			$this -> redirect(array('action' => 'index'));
		}
		// Find the document to be deleted.
		$doc = $this -> Document -> findById($id);
		$org_id = $doc['Document']['org_id'];
		// Create a file object and delete the actual file.
		$file = new File(APP . "Documents" . DS . $org_id . DS . $doc['Document']['name']);
		$file -> delete();

		// Delete the record in the database for the above file
		if ($this -> Document -> delete($id))
		{
			$this -> Session -> setFlash(__('Document deleted.', true));
			$this -> redirect(array(
				'controller' => 'documents',
				'action' => 'index',
				$org_id
			));
		}
		$this -> Session -> setFlash(__('Document was not deleted.', true));
		//$this -> redirect(array('action' => 'index'));
	}

	function sendFile($id)
	{
		$file = $this -> Document -> findById($id);
		$this -> response -> file("Documents" . DS . $file['Document']['org_id'] . DS . $file['Document']['name'], array(
			'download' => true,
			'name' => $file['Document']['name']
		));
		//Return reponse object to prevent controller from trying to render a view
		return $this -> response;
	}

}
?>
