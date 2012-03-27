<?php
/**
 * @author Stephen Roca
 * @since 03/22/2012
 */
?>

<h1>Add User</h1>
<?php
echo $this -> Form -> create('User');
echo $this -> Form -> input('FIRST_NAME');
echo $this -> Form -> input('LAST_NAME');
echo $this -> Form -> input('GT_USER_NAME');
echo $this -> Form -> input('EMAIL');
echo $this -> Form -> input('PHONE');
echo $this -> Form -> input('LEVEL');
// echo $this -> Form -> input('LOCAL_ADDR.')
echo $this -> Form -> end('Save User');
?>