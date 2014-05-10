<?php echo $this -> Html -> addCrumb($organization['Organization']['name'], '/organizations/view/' . $organization['Organization']['id']); ?>
<?php echo $this -> Html -> addCrumb('Add Logo', '/organizations/addlogo/' . $organization['Organization']['id']);

	$this -> extend('/Common/common');
	// $this -> start('sidebar');
	// echo $this -> Html -> nestedList(array($this -> Html -> link(__('Remove Logo',
	// true), array(
	// 'action' => 'deletelogo',
	// $organization['Organization']['id']
	// ), null, sprintf(__('Are you sure you want to delete the logo?', true)))),
	// array());
	//
	// $this -> end();

	$this -> assign('title', 'Edit Logo');
	$this -> start('middle');

	echo $this -> Html -> tag('span', 
		'<span class="center_helper"></span>'.$this -> Html -> image($organization['Organization']['logo_path'],
		array('id' => 'logo')
	), array('class'=>'org_logo', 'style'=>'float: none'));
	
	echo "<br /><br />";
	if(!empty($errors)){
		foreach ($errors as $field => $error){
			echo implode('<br />', $error);
        }
	}
	echo "<br>";
	echo $this -> Form -> create('Organization', array('type' => 'file'));
	echo $this -> Form -> file('image');
	echo $this -> Form -> submit('Upload');
	$this -> end();
?>
