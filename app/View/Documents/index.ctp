<?php
$this -> extend('/Common/common');

$this -> assign('title', 'Documents');
$this -> start('middle');
echo $this -> Html -> tableBegin(array('class' => 'listing'));
echo $this -> Html -> tableHeaders(array('Name', ''));
foreach($documents as $document)
{
echo $this -> Html -> tableCells(array($this -> Html -> link($document['Document']['name'], array('controller' => 'documents', 'action' => 'view', $document['Document']['id']))));	
}
echo $this -> Html -> tableEnd();
$this -> end();

?>
