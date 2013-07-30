<?php echo $this -> Html -> addCrumb($organization['Organization']['name'], '/owner/organizations/view/' . $organization['Organization']['id']);?>
<?php echo $this -> Html -> addCrumb('Add Logo', '/organizations/addlogo/' . $organization['Organization']['id']);

$this -> extend('/Common/common');
$this -> start('sidebar');
 echo $this -> Html -> nestedList(array($this -> Html -> link(__('Remove Logo', true), array(
					'action' => 'deletelogo',
					$organization['Organization']['id']
			), null, sprintf(__('Are you sure you want to delete the logo?', true)))), array(), array('id' => 'underline'));

$this -> end();

$this -> assign('title', 'Edit Logo');
$this -> start('middle');
	?>
<div id="middle">
	<div id="desc">
		<div id="descLeft">
			<?php 
				echo $this -> Form -> create('false', array(
						'url' => array(
								'controller' => 'organizations',
								'action' => 'addlogo',
								$organization['Organization']['id']
						),
						'type' => 'file'
				));
				echo $this -> Form -> file('File.image');
				echo $this -> Form -> submit('Upload', array('url' => array(
							'controller' => 'organizations',
							'action' => 'addlogo',
							$organization['Organization']['id']
					)));
				echo $this -> Form -> end();
			?>
			Image should be less than 20 KB (or 150px x 150px).
		</div>
		<div id="descRight">
			<?php
				if (strlen($organization['Organization']['logo_name']) < 1)
				{
					echo $this -> Html -> image('/img/default_logo.gif', array('width' => '80'));
				}
				else
				{
					echo $this -> Html -> image(array(
							'controller' => 'organizations',
							'action' => 'getLogo',
							$organization['Organization']['id'],
							'admin' => false,
							'owner' => false,
							'officer' => false
					), array('width' => '80'));
				}
			?>
		</div>
	</div>
</div>

<?php $this -> end(); ?>
