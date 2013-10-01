<?php

// Determine the message to display on the right side of the Breadcrumbs bar
$message = $this->Session->flash();
if ($message == null){
	$message = $this->Session->flash('auth');
}

if (strlen($message)){
	// custom message
	// do nothing
	
} else if ($this->Session->read('User.name') != null) {
	// user is logged in
	$message = "Welcome, ".$this->Session->read('User.name');

} else {
	// guest
	$message = "Welcome, Guest.";
}

?>

<div id="breadcrumb" class="hide-for-mobile">
	<div class="row clearfix">
		<ul style="float: right">
			<li class="last">
				<a><?php echo $message?></a>
			</li>
		</ul>
	<?php
		echo $this->Html->getCrumbList(
			array(
				'firstClass' => 'breadcrumb-item first',
				'lastClass' => 'breadcrumb-item last'
			),
			'Home');
	?>
	</div>
</div><!-- /#breadcrumb -->