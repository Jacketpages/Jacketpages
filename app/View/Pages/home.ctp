<!-- Fill out the home page information -->
<div id="middle_full">
    <?php
   echo $this -> Html -> div('', 'Welcome to JacketPages', array('id'=>'page_title'));
   echo $this -> Html -> para(null, 'JacketPages serves the student body at Georgia Tech by connecting students with student organizations and student organizations with your Student Government Association (SGA). This allows you to browse student organizations, to get involved, and, if you\'re already involved, to communicate your needs to SGA.');
   echo $this -> Html -> para(null, 'Once you\'re logged in with your Georgia Tech account, depending on your user profile, you can use the menus and toolbar to search organizations, research campus events (and add them to your own calendar), and interact with SGA\'s bill submission system.');
   
   echo $this -> Html -> tag('h1', 'Upcoming Events');
   // Outputs the iframe for the google calendar
   echo '<div style="margin: auto; width: 85%; padding-top: 10px;">';
   echo $this -> Html -> tag('iframe', "", array(
      'src' => "https://www.google.com/calendar/embed?showTitle=0&amp;showNav=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;mode=AGENDA&amp;height=125&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=4sjikgsht1i1rch9e03atfe588%40group.calendar.google.com&amp;color=%23691426&amp;ctz=America%2FNew_York",
      'width' => '100%',
      'height' => '350'
   ));
   echo '</div>';
    ?>
    
    <p></p>
</div>