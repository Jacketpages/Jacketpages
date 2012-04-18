<?php
/**
 * @author Stephen Roca
 * @since 3/27/2012
 */
?>


<h1>Edit User</h1>
<?php
   echo $this -> Form -> create('User');
   echo $this -> Form -> hidden('ID');
   echo $this -> Form -> input('FIRST_NAME');
   echo $this -> Form -> input('LAST_NAME');
   echo $this -> Form -> input('EMAIL');
   echo $this -> Form -> end('Save User');
?>
