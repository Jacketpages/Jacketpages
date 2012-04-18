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
echo $this -> Form -> input('LOCAL_ADDR.ADDR_LINE_1', array('label' => 'Local Address Line 1'));
echo $this -> Form -> input('LOCAL_ADDR.ADDR_LINE_2');
echo $this -> Form -> input('LOCAL_ADDR.COUNTRY');
echo $this -> Form -> input('LOCAL_ADDR.CITY');
echo $this -> Form -> input('LOCAL_ADDR.STATE');
echo $this -> Form -> input('LOCAL_ADDR.ZIP');
echo $this -> Form -> input('HOME_ADDR.ADDR_LINE_1');
echo $this -> Form -> input('HOME_ADDR.ADDR_LINE_2');
echo $this -> Form -> input('HOME_ADDR.COUNTRY');
echo $this -> Form -> input('HOME_ADDR.CITY');
echo $this -> Form -> input('HOME_ADDR.STATE');
echo $this -> Form -> input('HOME_ADDR.ZIP');
echo $this -> Form -> end('Save User');
?>