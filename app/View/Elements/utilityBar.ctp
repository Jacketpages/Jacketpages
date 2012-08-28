<?php
/**
 * @author Stephen Roca
 * @since 03/22/2012
 * This file defines the utility bar's menu options/links.
 */
?>

<div id="utilityBarWrapper">
   <div class="utilityMenu" id="utilityBar">

<!-- Define links as null so they don't appear as undefined when debug is on -->
<?php
   $jphome = $profile = $account = $submitBill = $viewMyBills = $viewBills = null;
   $bills = $viewMyOrgs = $agendaBills = $viewBudgets = $adminSga = $adminUsers = null;
   $logout = $adminOrgs = $loginOther = $submitBillOther = $postMsg = $admin = null;
   $login = $loginTopLevel = null;
?>

<!-- Define all top level list items that appear on the utility Bar -->
<?php
$orgs = $this -> Html -> link('Organizations', '#');
$sga = $this -> Html -> link('Student Government', '#');
if (!$this -> Permission -> isLoggedIn())
{
   $loginTopLevel = $this -> Html -> link('Login', '#');
}
else
{
   $account = $this -> Html -> link('My Account', '#');
   $bills = $this -> Html -> link('Bills', '#');
}

if ($this -> Permission -> isAdmin())
{
   $admin = $this -> Html -> link('Administration', '#');
}
?>
<!-- Define the inner level list items for the above list items -->
<?php
$viewOrgs = $this -> Html -> link('View All Organizations', array(
   'admin' => false,
   'controller' => 'organizations',
   'action' => 'index'
));
$viewSga = $this -> Html -> link('View SGA Members', array(
   'admin' => false,
   'controller' => 'sga_people',
   'action' => 'index'
));

if (!$this -> Permission -> isLoggedIn())
{
   $login = $this -> Html -> link(__('Login', true), array(
      'controller' => 'users',
      'action' => 'login'
   ));
}
else
{
   $profile = $this -> Html -> link('Account Profile', array(
      'owner' => true,
      'controller' => 'users',
      'action' => 'view',
      $this -> Session -> read('USER.ID')
   ));
   $logout = $this -> Html -> link('Logout', array(
      'admin' => false,
      'controller' => 'users',
      'action' => 'logout'
   ));
   $jphome = $this -> Html -> link('JacketPages Home', '/');
   $submitBill = $this -> Html -> link('Submit Bill', array(
      'admin' => false,
      'controller' => 'bills',
      'action' => 'add'
   ));
   $viewMyBills = $this -> Html -> link('View My Bills', array(
      'owner' => true,
      'controller' => 'bills',
      'action' => 'index'
   ));
   $viewBills = $this -> Html -> link('View All Bills', array(
      'controller' => 'bills',
      'action' => 'index',
      'All'
   ));

   $viewMyOrgs = $this -> Html -> link('View My Organizations', array(
      'controller' => 'organizations',
      'action' => 'my_orgs'
   ));
}

if ($this -> Permission -> isSGA())
{
   $viewBudgets = $this -> Html -> link('View Budgets', array(
      'controller' => 'budgets',
      'action' => 'index',
      'admin' => true
   ));

   $agendaBills = $this -> Html -> link('View Bills on Agenda', array(
      'controller' => 'bills',
      'action' => 'index',
      'Agenda',
      'admin' => true
   ));
}

if ($this -> Permission -> isAdmin())
{
   $adminBills = $this -> Html -> link('Administer All Bills', array(
      'admin' => true,
      'controller' => 'bills',
      'action' => 'index'
   ));
   $adminSga = $this -> Html -> link('Administer SGA Members', array(
      'admin' => true,
      'controller' => 'sga_people',
      'action' => 'index'
   ));
   $adminUsers = $this -> Html -> link('Administer Users', array(
      'admin' => true,
      'controller' => 'users',
      'action' => 'index'
   ));
   $adminOrgs = $this -> Html -> link('Administer Organizations', array(
      'controller' => 'organizations',
      'action' => 'index',
      'admin' => true
   ));
   $loginOther = $this -> Html -> link('Login as Other User', array(
      'admin' => true,
      'controller' => 'users',
      'action' => 'login'
   ));
   $submitBillOther = $this -> Html -> link('Submit Bill as Other User', array(
      'admin' => true,
      'controller' => 'bills',
      'action' => 'add'
   ));
   $postMsg = $this -> Html -> link('Post Message', '/Messages/message');
}
?>
<!-- Define all of the drop down lists -->
<?php
// Login
$loginList = array($loginTopLevel => array(
      $login,
      $jphome
   ));

// My Account
$accountList = array($account => array(
      $profile,
      $logout,
      $jphome
   ));
   
// Bills
$billList = array($bills => array(
      $submitBill,
      $viewMyBills,
      $viewBills
   ));
   
// Organizations
$orgList = array($orgs => array(
      $viewOrgs,
      $viewMyOrgs
   ));

// Student Government
$sgaList = array($sga => array(
      $agendaBills,
      $viewBudgets,
      $viewSga
   ));

$adminList = array($admin => array(
      $adminSga,
      $adminUsers,
      $adminOrgs,
      $loginOther,
      $submitBillOther,
      $postMsg
   ));
?>
<!-- Print all of the drop down lists -->
<?php
if (!$this -> Permission -> isLoggedIn())
{
   echo $this -> Html -> nestedList($loginList);
}
else
{
   echo $this -> Html -> nestedList($accountList);
   echo $this -> Html -> nestedList($billList);
}
echo $this -> Html -> nestedList($orgList);
echo $this -> Html -> nestedList($sgaList);

if ($this -> Permission -> isAdmin())
{
   echo $this -> Html -> nestedList($adminList);
}
?>
</div>
</div> 