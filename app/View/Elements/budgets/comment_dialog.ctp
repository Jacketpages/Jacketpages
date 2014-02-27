<?php
echo $this -> Form -> create("Comment", array('id' => "CommentDialogForm"));
echo $this -> Form -> label("DialogFormName","Line Item Name: ", array('id' =>'DialogFormName'));
echo $this -> Form -> label("DialogFormName","Line Item State: ", array('id' =>'DialogFormState'));
echo $this -> Form -> input("Comment", array('label' => 'Comment:','type' => 'textarea', 'id' => "DialogFormComment"));
echo $this -> Form -> end();
?>