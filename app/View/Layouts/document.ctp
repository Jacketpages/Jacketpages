<?php
header('Content-type: ' . $file['type']);
if(!$inpage) header('Content-Disposition: attachment; filename="'.$file['name'].'"');
echo $content_for_layout;
die();
?>