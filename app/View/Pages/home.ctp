<!-- Fill out the home page information -->
<div id="middle_full">
    <?php
    echo $this->Html->div('', 'Welcome to JacketPages', array('id' => 'page_title'));
	if($student && !$gt_member) {
		echo '<div id="notification">';
		echo "Welcome, " . $this->Session->read('User.gt_user_name') . ". It appears that you have not yet created a JacketPages account. Please create a JacketPages profile " . $this->Html->link('here','/users/add') . ".";
		echo '</div>'; 					
	}

    /*echo $this->Html->para(null, 'JacketPages serves as the Student Government Association bills system for financial allocations to student organizations and resolutions to represent the student opinion. If you are looking for organization information, please visit the Georgia Tech Orgsync! JacketPages will continue to be updated more over the next few weeks!');*/


    echo '<p>JacketPages serves as the Student Government Association bills system for financial allocations to student organizations and resolutions to represent the student opinion. If you are looking for organization information, please visit the <a href="https://orgsync.com/browse_orgs/864">Georgia Tech OrgSync</a>! Here on JacketPages, you will find all bills considered or to be considered by SGA, resources for requesting funding from SGA, the budget submission tool, and much more!</p>';

    /*   echo $this -> Html -> tag('h1', 'SGA Finance Resources');
       // Outputs the iframe for the google calendar
        echo '<div style="margin: auto; width: 100%; padding-top: 10px;">';
       echo $this -> Html -> tag('iframe', "", array(
          'src' => "http://sgafinance.atspace.cc/",
          'width' => '100%',
          'height' => '2300'
       ));
       echo '</div>';*/

    echo $this->Html->tag('h1', 'JacketPages Overview');
    ?>
    <table>
        <col width="20%">
        <col width="80%">
        <tr>
            <td><img class="home_icon" src="/img/icons_home/bill.jpg"></td>
            <td style="vertical-align:middle">
                <h4>Bills</h4>
                <ul style="padding-left: 1.6em">
                    <li>Description</li>
                    <li>Description</li>
                    <li>Description</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><img src="/img/icons_home/org.jpg"></td>
            <td style="vertical-align:  middle">
                <h4>Organizations</h4>
                <ul style="padding-left: 1.6em">
                    <li>Description</li>
                    <li>Description</li>
                    <li>Description</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><img src="/img/icons_home/resources.png"></td>
            <td style="vertical-align:  middle">
                <h4>Resources</h4>
                <ul style="padding-left: 1.6em">
                    <li>Description</li>
                    <li>Description</li>
                    <li>Description</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><img src="/img/icons_home/sga.png"></td>
            <td style="vertical-align:  middle">
                <h4>SGA</h4>
                <ul style="padding-left: 1.6em">
                    <li>Description</li>
                    <li>Description</li>
                    <li>Description</li>
                </ul>
            </td>
        </tr>
    </table>


    <p></p>
</div>