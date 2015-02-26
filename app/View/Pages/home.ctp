<!-- Fill out the home page information -->
<div id="middle_full">
    <?php
   	echo $this -> Html -> div('', 'Welcome to JacketPages', array('id'=>'page_title')); 
	if($student && !$gt_member) {
		echo '<div id="notification">';
		echo "Welcome, " . $this->Session->read('User.gt_user_name') . ". It appears that you have not yet created a JacketPages account. Please create a JacketPages profile " . $this->Html->link('here','/users/add') . ".";
		echo '</div>'; 					
	} 
	
   echo $this -> Html -> para(null, 'JacketPages serves the student body at Georgia Tech by connecting students with student organizations and student organizations with your Student Government Association (SGA). This allows you to browse student organizations, to get involved, and, if you\'re already involved, to communicate your needs to SGA. Once you\'re logged in with your Georgia Tech account, depending on your user profile, you can use the menus and toolbar to search organizations, research campus events (and add them to your own calendar), and interact with SGA\'s bill submission system..');
   
   echo $this -> Html -> tag('h1', 'Upcoming Events');
   // Outputs the iframe for the google calendar
   echo '<div style="margin: auto; width: 85%; padding-top: 10px;">';
   echo $this -> Html -> tag('iframe', "", array(
      'src' => "https://www.google.com/calendar/embed?mode=WEEK&src=1qihk52drlsino2r5evgrpup00%40group.calendar.google.com&amp;ctz=America/New_York",
      'width' => '100%',
      'height' => '550'
   ));
   echo '</div>';
    ?>
    
    <p></p>
</div>