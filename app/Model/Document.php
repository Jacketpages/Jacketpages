<?php
/**
 * @author Michael Ozeryansky
 * @since 10/19/2013
 */
class Document extends AppModel
{
	public $order = 'Document.name';
	
	// custom validation for document uploading
	// this could be moved the inside object, but requires a lot of testing
	public function validatesDocumentUpload()
	{
		$this->validate = array(
			'submittedfile' => array(
				array(
					'rule' => array('fileSize', '<=', '3MB'),
					'message' => 'Documents should be less than 3MB.'
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
	            $response = 'Image file is too large, image should be less than 3MB.';// php ini setting
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
