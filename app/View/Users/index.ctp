<?php
/**
 * Path: Jacketpages/users/index.ctp
 * Passed variables:
 * @param $users - The User Model array for more than one user.
 * @author Stephen Roca
 * @since 03/22/2012
 */
?>

<h1>User Index Page</h1>
<table>
	<?php
echo $this -> Html -> tableHeaders(array('Id', 'Name', 'Email'));

foreach ($users as $user):
	?>
	<tr>
		<td><?php echo $user['User']['ID'];?></td>
		<td><?php echo $this -> Html -> link($user['User']['NAME'], array(
            'controller' => 'users',
            'action' => 'view',
            $user['User']['ID']
         ));
		?></td>
		<td><?php echo $user['User']['EMAIL'];?></td>
	</tr>
	<?php endforeach;?>
</table>