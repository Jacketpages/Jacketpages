<?php
/**
 * Path: Jacketpages/users/index.ctp
 * Passed variables:
 * @param $users - The User Model array for more than one user.
 * @author Stephen Roca
 */
?>

<h1>User Index Page</h1>

<?php // @TODO replace the following with $this ->Html ->tableheaders(); ?>
<table>
	<tr>
		<td>Id</td>
		<td>Name</td>
		<td>Email</td>
	</tr>
	<?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo $user['User']['ID'];?></td>
		<td><?php echo $this -> Html -> link($user['User']['NAME'], array('controller' => 'users', 'action' => 'view', $user['User']['ID']));?></td>
		<td><?php echo $user['User']['EMAIL'];?></td>
	</tr>
	<?php endforeach; ?>
</table>