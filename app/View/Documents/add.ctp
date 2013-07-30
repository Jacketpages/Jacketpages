<?php
/**
 * @author Stephen Roca
 * @since 4/11/2013
 */
echo $this -> Form -> create('Document', array('type' => 'file'));
echo $this->Form->file('Document.submittedfile');
echo $this -> Form -> submit();

?>