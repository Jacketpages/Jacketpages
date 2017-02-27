<!-- Fill out the home page information -->
<div id="middle_full">
    <?php
   	echo $this -> Html -> div('', 'Welcome to BuzzBills (formerly JacketPages)', array('id'=>'page_title'));
	if($student && !$gt_member) {
		echo '<div id="notification">';
		echo "Welcome, " . $this->Session->read('User.gt_user_name') . ". It appears that you have not yet created a JacketPages account. Please create a JacketPages profile " . $this->Html->link('here','/users/add') . ".";
		echo '</div>'; 					
	} 
	
   echo $this -> Html -> para(null, 'BuzzBills serves as the Student Government Association bills system for financial allocations to student organizations and resolutions to represent the student opinion.');
   
   echo $this -> Html -> tag('h1', 'SGA Finance Resources');
   // Outputs the iframe for the google calendar
   echo '<div style="margin: auto; width: 85%; padding-top: 10px;">';
   echo $this -> Html -> tag('iframe', "", array(
      'src' => "http://sgafinance.atspace.cc/",
      'width' => '100%',
      'height' => '2300'
   ));
   echo '</div>';
    ?>
    
    <p></p>
</div>