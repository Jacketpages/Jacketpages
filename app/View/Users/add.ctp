<?php
/**
 * @author Stephen Roca
 * @since 03/22/2012
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Add User');
$this -> start('middle');
echo $this -> Form -> create('User');
echo $this -> Form -> input('FIRST_NAME', array('label' => 'First Name'));
echo $this -> Form -> input('LAST_NAME', array('label' => 'Last Name'));
echo $this -> Form -> input('GT_USER_NAME', array('label' => 'Georgia Tech User Name (Tsquare ID)'));
echo $this -> Form -> input('EMAIL', array('label' => 'Email'));
echo $this -> Form -> input('PHONE', array('label' => 'Phone Number'));
echo $this -> Form -> input('LEVEL', array('label' => 'Level', 'options' => array('user' => 'Normal', 'power' => 'SGA Power', 'admin' => 'Administrator')));
echo $this -> Form -> input('LOCAL_ADDR.ADDR_LINE_1', array('label' => 'Local Address Line 1'));
echo $this -> Form -> input('LOCAL_ADDR.ADDR_LINE_2', array('label' => 'Local Address Line 2'));
echo $this -> Form -> input('LOCAL_ADDR.COUNTRY', array('label' => 'Country'));
echo $this -> Form -> input('LOCAL_ADDR.CITY', array('label' => 'City'));
echo $this -> Form -> input('LOCAL_ADDR.STATE', array('label' => 'State'));
echo $this -> Form -> input('LOCAL_ADDR.ZIP', array('label' => 'Zip Code'));
echo $this -> Form -> input('HOME_ADDR.ADDR_LINE_1', array('label' => 'Home Address Line 1'));
echo $this -> Form -> input('HOME_ADDR.ADDR_LINE_2', array('label' => 'Home Address Line 2'));
echo $this -> Form -> input('HOME_ADDR.COUNTRY', array('label' => 'Country'));
echo $this -> Form -> input('HOME_ADDR.CITY', array('label' => 'City'));
echo $this -> Form -> input('HOME_ADDR.STATE', array('label' => 'State'));
echo $this -> Form -> input('HOME_ADDR.ZIP', array('label' => 'Zip Code'));
echo $this -> Form -> end('Save User');
$this -> end();
?>