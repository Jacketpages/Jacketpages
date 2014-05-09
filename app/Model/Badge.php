<?php

App::uses('AppModel', 'Model');

class Badge extends AppModel
{
	// view_style enum values
	const VIEW_STYLE_DEFAULT = 'DEFAULT';
	const VIEW_STYLE_CUSTOM = 'CUSTOM';
	
	public $order = 'Badge.name';
	
	public $recursive = 0;
	
	public $hasAndBelongsToMany = array(
		'Organizations' => array(
			'counterCache' => true
		)
	);
	
	// validation
	public $validate = array(
		'name' => array(
			'rule' => 'notEmpty'
		)
	);
	
	function validatesIconUpload()
	{
		$this->validate = array(
			'icon' => array(
				array(
					'rule' => array('fileSize', '<=', '200KB'),
					'required' => true,
					'message' => 'Icon should be less than 200 KB.'
				),
				array(
					'rule' => array('extension', array('gif', 'jpeg', 'png', 'jpg')),
					'message' => 'Please supply a valid image type: gif, jpg, jpeg, png.'
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
	
	public function uploadErrorWithMessage($check)
	{
		$check = $check['icon'];

		if (is_array($check) && isset($check['error'])) {
			$check = $check['error'];
		}

		if($check === UPLOAD_ERR_OK){
			return true;
		}
	
		switch ($check) {
	        case UPLOAD_ERR_INI_SIZE:
	            $response = 'Icon file is too large, icon should be less than 200 KB.';// php ini setting
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
