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

	echo $this -> Html -> image($organization['Organization']['logo_path'], array(
		'id' => 'logo',
		'style' => 'height:160px;'
	));
	echo "<br>";
	echo "Image should be less than 200 KB.";
	echo "<br>";
	echo "<br>";
	echo $this -> Form -> create('false', array(
		'url' => array(
			'controller' => 'organizations',
			'action' => 'addlogo',
			$organization['Organization']['id']
		),
		'type' => 'file'
	));
	echo $this -> Form -> file('File.image');
	echo $this -> Form -> submit('Upload', array('url' => array(
			'controller' => 'organizations',
			'action' => 'addlogo',
			$organization['Organization']['id']
		)));
	echo $this -> Form -> end();
	$this -> end();
?>
