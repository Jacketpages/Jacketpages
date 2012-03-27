<?php
/**
 * Path: Jacketpages/users/view/$id
 * Passed variables:
 * @param $user - The User Model array for an indivdual user
 * @author Stephen Roca
 * @since 03/22/2012
 */
debug($user);
?>
<h1><?php echo h($user['User']['NAME']); ?></h1>
<p><?php echo h($user['User']['EMAIL']); ?></p>