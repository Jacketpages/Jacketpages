<?php
/**
 * @author Stephen Roca
 * @since 10/28/2012
 */

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
class DocumentsController extends AppController
{
	public $helpers = array(
		'Html',
		'Session'
	);

	public function index($id = null)
	{
		$this -> set('isOfficer', $this -> isOfficer($id));
		$this -> set('isMember', $this -> isMember($id));
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
			$documents = $this -> paginate(array('Document.org_id' => $id));
			Document::addFilesizeToDocuments($documents);
			$this -> set('documents', $documents);
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
		
		if ($this -> request -> is('post'))
		{
			// set model data for validation
			$this->request->data['id'] = array('id'=>$id);// add id to the request data so validation can use it
			$this->Document->set($this->request->data);
			
			if ($this->Document->validatesDocumentUpload())
			{
				$documentsFolder = Document::getDocumentsFolderWithId($id);
				$filepath = Document::getFilepathWithIdAndName($id, $this -> request -> data['Document']['submittedfile']['name']);
				
				$dir = new Folder($documentsFolder, true, 0744);
				move_uploaded_file($this->request->data['Document']['submittedfile']['tmp_name'], $filepath);

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
						$this -> Session -> setFlash('The document has been successfully uploaded.');
						$this -> redirect(array(
							'action' => 'index',
							$id
						));
					}
					else
					{
						$this -> Session -> setFlash('The document failed to upload.');
					}
				}
			}
		}
		
		$this->set('errors', $this->Document->validationErrors);
	}

	/**
	 * Fix this function later to where the file is deleted after the database entry
	 * is deleted.
	 */
	public function delete($id)
	{
		if(!($this -> isOfficer($id) || $this -> isLace()))
			$this -> redirectHome();
		if (!$id)
		{
			$this -> Session -> setFlash(__('Invalid ID for document', true));
			$this -> redirect(array('action' => 'index'));
		}
		
		// Find the document to be deleted.
		$doc = $this -> Document -> findById($id);
		$org_id = $doc['Document']['org_id'];
		
		// Create a file object and delete the actual file.
		$filepath = Document::getFilepathWithIdAndName($org_id, $doc['Document']['name']);
		$file = new File($filepath);
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

		$filepath = Document::getFilepathWithIdAndName($file['Document']['org_id'], $file['Document']['name']);
		$this -> response -> file($filepath, array(
			'download' => true,
			'name' => $file['Document']['name']
		));
		
		//Return reponse object to prevent controller from trying to render a view
		return $this -> response;
	}

}
?>
