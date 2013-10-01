<?php
$this -> extend('/Common/common');

$this -> assign('title', 'Documents');
$this -> start('sidebar');
echo $this -> Html -> nestedList(array(
	$this -> Html -> link("Add File", array('action' => 'add', $id))),
	array()
);
$this -> end();
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
		)), $this -> Html -> link("Delete", array('controller' => 'documents', 'action'=> 'delete', $document['Document']['id']))));
}
echo $this -> Html -> tableEnd();
$this -> end();
?>
