<?php

$this -> extend('/Common/common');
$this -> assign('title', 'Add SGA Member');

$this -> Html -> addCrumb('SGA People', '/sga_people');
$this -> Html -> addCrumb('Add SGA Member', '/sga_people/add/');

$this -> start('middle');

echo $this->Form->create('SgaPerson');
		echo $this->Form->hidden('user_id', array('id' => 'user_id'));
		echo $this->Form->input('user_info', array('label' => 'Name or GT ID -- You must select from suggestions when they appear', 'id' => 'userName'));
		echo $this->Form->input('house', array('options' => array('Graduate' => 'Graduate', 'Undergraduate' => 'Undergraduate')));
		echo $this->Form->input('department');
echo $this->Form->end(__('Submit', true));

?>
<script type="text/javascript">
$(document).ready(function() {

$( "#userName" ).autocomplete({
	minLength: 2,
	source: wr+'ajax/userName',
	focus: function( event, ui ) {
		$( "#userName" ).val( ui.item.name );
		return false;
	},
	select: function( event, ui ) {
		$( "#userName" ).val( ui.item.name );
		$( "#user_id" ).val( ui.item.id );
		return false;
	}
})
.data( "autocomplete" )._renderItem = function( ul, item ) {
	return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.name + " (" + item.gtUsername + ")</a>")
		.appendTo( ul );
};
});
</script>

<?php
$this->end();
?>
