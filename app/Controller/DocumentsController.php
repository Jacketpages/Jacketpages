<?php
/**
 * @author Stephen Roca
 * @since 10/28/2012
 */
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
class DocumentsController extends Controller
{
	public $helpers = array('Html', 'Permission');
	
	public function testing()
	{
		$dir = new Folder('C:\wamp\www\Jacketpages\app\View\LineItems');
		debug($dir);
		$dir -> create($dir -> path . DS .'testing');
		debug($dir -> path);
		$file = new File($dir -> path . DS . 'testing.pdf');
		$data = $this -> Document -> findById('5');
		$file -> open('w');
		$file -> write($data['Document']['file']);
		$file -> close();
	}
	
	public function index($id = null)
	{
		if ($id != null)
		{
			$this -> loadModel('Organization');
			$org = $this->Organization->read ( null, $id );
			if ($org ['Organization'] ['status'] == 'Frozen')
			{
				$this->Session->setFlash ( __ ( 'Invalid organization', true ) );
				$this->redirect ( array ('controller' => 'organizations', 'action' => 'index') );
			}
			$this->set ( 'organization', $org );
			$this->Document->recursive = 0;
			$this->set ( 'documents', $this->paginate ( array ('Document.org_id' => $id) ) );
		}
		else
		{
			$this->Session->setFlash ( "Invalid organization." );
			$this->redirect ( array ('controller' => 'organizations', 'action' => 'index') );
		}
	}
	
	public function view($id = null)
	{
		if (! $id)
		{
			$this->Session->setFlash ( __ ( 'Invalid file.', true ) );
			$this->redirect ( array ('action' => '/') );
		}
		$this->set ( 'inpage', false );
		//Configure::write('debug', 0);
		$file = $this->Document->findById ( $id );
		$document = array ();
		$document ['name'] = $file ['Document'] ['name'];
		$document ['type'] = $file ['Document'] ['type'];
		$document ['data'] = $file ['Document'] ['file'];
		
		if ($document ['data'] == null)
		{
			$this->Session->setFlash ( __ ( 'Invalid file.', true ) );
			$this->redirect ( array ('action' => '/') );
		}
		else
		{
			$this->set ( 'file', $document );
			return $this->render ( 'download', 'document' );
			
		}
	}
}

?>
