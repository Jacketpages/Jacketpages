<?php
/**
 * @author Michael Ozeryansky
 * @since 10/19/2013
 */
class Document extends AppModel
{
	public $order = 'Document.name';
	
	
	//
	// Document helper methods
	//
	static function getMaxFolderSize()
	{
		return '25 MB';
	}
	
	static function getFilepathWithIdAndName($id, $name)
	{
		return Document::getDocumentsFolder().$id.DS.$name;
	}
	
	static function getDocumentsFolderWithId($id)
	{
		return Document::getDocumentsFolder().$id;
	}
	
	static function getDocumentsFolder()
	{
		return APP.DS."Documents".DS;
	}
	
	// don't place this in afterFind, because the filesize is not always needed
	static function addFilesizeToDocument(&$document)
	{
		$filepath = Document::getFilepathWithIdAndName($document['Document']['org_id'], $document['Document']['name']);
		$file = new File($filepath);
		
		$document['Document']['filesize'] = $file->size();
	}
	
	static function addFilesizeToDocuments(&$documents)
	{
		foreach($documents as &$document){
			Document::addFilesizeToDocument($document);
		}
	}
	


	// custom validation for document uploading
	// this could be moved the inside object, but requires a lot of testing
	public function validatesDocumentUpload()
	{
		$this->validate = array(
			'submittedfile' => array(
				array(
					'rule' => array('fileSize', '<=', '8MB'),
					'required' => true,
					'message' => 'Documents should be less than 8MB.'
				),
				array(
					'rule' => array('maxFolderSize', Document::getMaxFolderSize()),
					'message' => 'You can only upload a total of '.Document::getMaxFolderSize()
				),
				array(
					'rule' => 'uploadErrorWithMessage',
					'required' => true,
					'message' => ''
				)
			)
		);
		
		$validates = $this->validates();
		
		// reset the validation
		$this->validate = array();
		
		return $validates;
	}
	
	public function maxFolderSize($uploadInfo, $maxFolderSize)
	{
		$uploadInfo = $uploadInfo['submittedfile'];
		$id = $this->data['id']['id'];
		
		$maxFolderSize = CakeNumber::fromReadableSize($maxFolderSize);
		
		// get the filesize of the file to be uploaded, bytes
		$uploadedFileSize = $uploadInfo['size'];
		
		// get the current folder size
		$dir = new Folder(APP.DS."Documents".DS.$id, true, 0744);
		$folderSize = $dir->dirsize();
		
		// if the current folder size + the newly added file is <= the max size allowed
		if($folderSize + $uploadedFileSize <= $maxFolderSize){
			// allow the upload
			return true;
		}
		
		return false;
	}
	
	// an extended 
	public function uploadErrorWithMessage($check)
	{
		$check = $check['submittedfile'];
		
		if (is_array($check) && isset($check['error'])) {
			$check = $check['error'];
		}

		if($check === UPLOAD_ERR_OK){
			return true;
		}
	
		switch ($check) {
	        case UPLOAD_ERR_INI_SIZE:
	            $response = 'The file is too large, the file should be less than 8MB.';// php ini setting
	            break;
	        case UPLOAD_ERR_FORM_SIZE:
	            $response = 'Max file size exceed the form limit.';
	            break;
	        case UPLOAD_ERR_NO_FILE:
	            $response = 'Please select a file to upload.';
	            break;
	        case UPLOAD_ERR_PARTIAL:
	        case UPLOAD_ERR_NO_TMP_DIR:
	        case UPLOAD_ERR_CANT_WRITE:
	        case UPLOAD_ERR_EXTENSION:
	            $response = 'Could not upload, system error: '.$check.'.';
	            break;
	        default:
	            $response = 'Unknown system error: '.$check.'.';
	            break;
	    }
	    
	    $this->invalidate('upload', $response);
	    
	    return false;
	}
}
?>
