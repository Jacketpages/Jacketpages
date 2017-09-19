<?php
/**
 * @author Stephen Roca
 * @since 8/19/2013
 */
$this -> extend('/Common/common');
$this -> assign('title', 'Create Organization');
echo $this->Html->addCrumb('Organizations', '/organizations');
echo $this -> Html -> addCrumb('Create Organization', '/organizations/add');
$this -> start('middle');
echo $this -> Form -> create();
echo $this -> Form -> input('id');
echo $this -> Form -> input('name');
/*echo $this -> Form -> input('status', array(
	'options' => array(
		'Active' => 'Active',
		'Inactive' => 'Inactive',
		'Frozen' => 'Frozen'
	),
	'default' => 'Active'
));*/
echo $this -> Form -> input('tier', array(
	'label' => 'Tier',
	'options' => array(
		'1' => 'I',
		'2' => 'II',
		'3' => 'III'
	),
	'default' => '3'
));
/*echo $this -> Form -> input('category', array(
	'options' => $categories,
	'label' => 'Category'
));
echo $this -> Form -> hidden('contact_id', array('id' => 'contact_id'));
echo $this -> Form -> input('User.name', array(
	'label' => 'Primary Contact Name -- You must select from suggestions when they appear',
	'id' => 'userName'
));*/
/*echo $this -> Form -> input('User.name', array(
 'label' => 'Primary Contact',
 'id' => 'primary_contact'
 ));
 echo $this -> Form -> input('User.email', array(
 'label' => 'Primary Contact Email',
 'id' => 'primary_email'
 ));*/
/*echo $this -> Form -> input('description');*/
echo $this->Form->input('website', array('label' => 'Orgsync Website'));
/*echo $this -> Form -> input('meeting_information');
echo $this -> Form -> input('dues', array('label' => 'Dues', 'id' => 'dues'));*/
echo $this -> Form -> submit('Create');
?>
<script>
	$(document).ready(function() {
		$( "#userName" ).autocomplete({
			minLength: 2,
			source: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'lookupByName'), false); ?>',
			focus: function( event, ui ) {
				$( "#userName" ).val( ui.item.name );
				return false;
			},
			select: function( event, ui ) {
				$( "#userName" ).val( ui.item.name );
				$( "#contact_id" ).val( ui.item.id );
				return false;
			}
		})
		.data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append( "<a>" + item.name + " (" + item.gt_user_name + ")</a>")
				.appendTo( ul );
		};
	});
</script>

<?php $this -> end();
?>
