<?php
echo $this -> Html -> addCrumb($organization['Organization']['name'], '/organizations/view/' . $organization['Organization']['id']);
echo $this -> Html -> addCrumb('Documents', '/documents/index/' . $organization['Organization']['id']);
$this -> extend('/Common/common');

$this -> assign('title', 'Documents');
$this -> start('middle');
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableHeaders(array(
	'Name',
	''
));
foreach ($documents as $document)
{
	echo $this -> Html -> tableCells(array($this -> Html -> link($document['Document']['name'], array(
			'controller' => 'documents',
			'action' => 'sendFile',
			$document['Document']['id']
		)), array($this -> Html -> link("Delete", array('controller' => 'documents', 'action'=> 'delete', $document['Document']['id'])),array('style' => 'text-align:right'))));
}
echo $this -> Html -> tableEnd();
echo $this -> Html -> tag('h2','Upload File');
echo $this -> Form -> create('Document', array('type' => 'file'));
echo $this->Form->file('Document.submittedfile');
echo $this -> Form -> submit();
$this -> end();
?>
