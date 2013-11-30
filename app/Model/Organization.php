<?php
/**
 * @author Stephen Roca
 * @since 06/08/2012
 */
class Organization extends AppModel
{
	public $order = 'Organization.name';
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'contact_id'
		),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category'
		)
	);
	
	// custom validation for logo uploading
	// this could be moved the inside object, but requires a lot of testing
	public function validatesLogoUpload()
	{
		$this->validate = array(
			'image' => array(
				array(
					'rule' => array('fileSize', '<=', '200KB'),
					'message' => 'Image should be less than 200 KB.'
				),
				array(
					'rule' => array('extension', array('gif', 'jpeg', 'png', 'jpg')),
					'message' => 'Please supply a valid image type: gif, jpg, jpeg, png.'
				),
				array(
					'rule' => 'uploadErrorWithMessage',
					'message' => ''
				)
			)
		);
		
		return $this->validates();
	}
	
	// an extended 
	public function uploadErrorWithMessage($check)
	{
		$check = $check['image'];

		if (is_array($check) && isset($check['error'])) {
			$check = $check['error'];
		}

		if($check === UPLOAD_ERR_OK){
			return true;
		}
	
		switch ($check) {
	        case UPLOAD_ERR_INI_SIZE:
	            $response = 'Image file is too large, image should be less than 200 KB.';// php ini setting
	            break;
	        case UPLOAD_ERR_FORM_SIZE:
	            $response = 'Max file size exceed the form limit.';
	            break;
	        case UPLOAD_ERR_PARTIAL:
	        case UPLOAD_ERR_NO_FILE:
	        case UPLOAD_ERR_NO_TMP_DIR:
	        case UPLOAD_ERR_CANT_WRITE:
	        case UPLOAD_ERR_EXTENSION:
	            $response = 'Could not upload, system error.';
	            break;
	        default:
	            $response = 'Unknown system error.';
	            break;
	    }
	    
	    $this->invalidate('upload', $response);
	    
	    return false;
	}
}
?>
