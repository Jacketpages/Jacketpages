<?php

$this -> extend('/Common/common');

$this -> assign('title', 'Badges');

$this -> Html -> addCrumb('Badges', '/badges');

$this -> start('sidebar');
$sidebar = array();
$sidebar[] = $this -> Html -> link('View All Badges', array('action' => '/'));
if($sga_exec){
	$sidebar[] = $this -> Html -> link('Award A Badge', array('action' => 'award'));
	$sidebar[] = $this -> Html -> link('Edit A Badge', array('action' => 'edit'));
	$sidebar[] = $this -> Html -> link('Add New Badge', array('action' => 'add'));
}
echo $this -> Html -> nestedList($sidebar);
$this -> end();

$this -> start('middle');
if(count($badges) > 0){
	// descriptions
	echo $this->Html->div('badgeDescription', 'Hover over a badge to view it\'s description.', array(
		'id' => 'badgeDescription'
	));
	foreach($badges as $badge){
		$description = (strlen(trim($badge['Badge']['description']))==0)?'No description':$badge['Badge']['description'];
		echo $this->Html->div('badgeDescription', $description, array(
			'id' => 'badgeDescription_badge_'.$badge['Badge']['id'],
			'style' => 'display:none'
		));
	}
	
	// badges
	$badgeButtons = '';
	foreach($badges as $badge){
		// check permission
		if($admin){
			// has edit permission
			$badgeButtons .= $this->Html->link(
				$this->element('badges/display', array('badge' => $badge['Badge'])),
				'/badges/award/'.$badge['Badge']['id'],
				array(
					'class' => 'badgeButton',
					'escape' => false,
					'id' => 'badge_'.$badge['Badge']['id']
				)
			);
			
		} else {
			// view only
			$badgeButtons .= $this->Html->div(
				'badgeButton',
				$this->element('badges/display', array('badge' => $badge['Badge'])),
				array(
					'escape' => false,
					'id' => 'badge_'.$badge['Badge']['id']
				)
			);
		}
	}
	
	echo $this->Html->div('badgeButtons', $badgeButtons);
	
} else {
	// no badges
	echo $this->Html->div('notification', 'There are no badges to display. To add a new badge select \'Add New Badge\' on the sidebar.');
}

echo $this->Html->tag('h1', 'Organizations with Badges');

echo $this->Html->tableBegin(array(
	'id' => 'organizationBadgeTable',
	'class' => 'listing'
));
echo $this -> Html -> tableHeaders(array(
		array('Organization' => array('width' => '50%')),
		'Badges'
	),
	array('class' => 'links')
);
// organization list
foreach($organizations as $organization){
	$orgName = $this->Html->link($organization['Organization']['name'],
		array(
			'controller' => 'organizations',
			'action' => 'view',
			$organization['Organization']['id']
		)
	);

	$badgeStr = '';
	foreach($organization['Badges'] as $badge){
		// check permission
		if($admin){
			// has edit permissions
			$badgeStr .= $this->Html->link(
				$this->element('badges/display', array('badge' => $badge)),
				'/badges/award/'.$badge['id'],
				array(
					'class' => 'badgeButton',
					'escape' => false
				)
			);
		
		} else {
			// view only
			$badgeStr .= $this->Html->div('badgeButton',
				$this->element('badges/display', array('badge' => $badge)),
				array(
					'escape' => false
				)
			);
		}		
	}
	
	echo $this->Html->tableCells(array(array(
		$orgName,
		$badgeStr
	)));
}
echo $this->Html->tableEnd();

?>
<script>
$(function(){
	// show the badge description on hover
	$('.badgeButtons .badgeButton').hover(
		function(){
			// on mouse over
			var id = $(this).attr('id');
			$('#badgeDescription_'+id).show();
			// hide the default
			$('#badgeDescription').hide();
			
		}, function(){
			// on mouse out
			var id = $(this).attr('id');
			$('#badgeDescription_'+id).hide();
			// show the default
			$('#badgeDescription').show();
		}
	);
});
</script>
<?php

$this -> end();

?>
