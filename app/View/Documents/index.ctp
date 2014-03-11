<?php
echo $this -> Html -> addCrumb($organization['Organization']['name'], '/organizations/view/' . $organization['Organization']['id']);
echo $this -> Html -> addCrumb('Documents', '/documents/index/' . $organization['Organization']['id']);
$this -> extend('/Common/common');

$this -> assign('title', 'Documents');
$this -> start('middle');
if ($isOfficer || $lace)
{
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		'Name',
		array('Size' => array('width'=>'200px', 'style'=>'text-align:right')),
		array('' => array('width'=>'60px')),
	));
	$totalSize = 0;
	foreach ($documents as $document)
	{
		$totalSize += $document['Document']['filesize'];
		echo $this -> Html -> tableCells(array(
			$this -> Html -> link($document['Document']['name'], array(
				'controller' => 'documents',
				'action' => 'sendFile',
				$document['Document']['id']
			)),
			array(
				$this->Number->toReadableSize($document['Document']['filesize']),
				array('style' => 'text-align:right')
			),
			array(
				$this -> Html -> link("Delete", array(
					'controller' => 'documents',
					'action' => 'delete',
					$document['Document']['id']
				)),
				array('style' => 'text-align:right')
			)
		));
	}
	echo $this -> Html -> tableEnd();
	echo $this -> Html -> div('',
		'Used: '.$this->Number->toReadableSize($totalSize).' of 25 MB',
		array('style'=>'text-align: right; margin-right: 65px; font-size: 14px; margin-top: -5px;')
	);
	echo $this -> Html -> tag('h2', 'Upload File');
	if(!empty($errors)){
		foreach ($errors as $field => $error){
			echo implode('<br />', $error);
        }
	}
	echo $this -> Form -> create('Document', array('type' => 'file'));
	echo $this -> Form -> file('submittedfile');
	echo $this -> Form -> submit();

}
else
{
	echo $this -> Html -> tableBegin(array('class' => 'listing'));
	echo $this -> Html -> tableHeaders(array(
		'Name',
		array('Size' => array('width'=>'100px', 'style'=>'text-align:right'))
	));
	foreach ($documents as $document)
	{
		echo $this -> Html -> tableCells(array(
			$this -> Html -> link($document['Document']['name'], array(
				'controller' => 'documents',
				'action' => 'sendFile',
				$document['Document']['id']
			)),
			array(
				$this->Number->toReadableSize($document['Document']['filesize']),
				array('style' => 'text-align:right')
			),
		));
	}
	echo $this -> Html -> tableEnd();
}
$this -> end();
?>
