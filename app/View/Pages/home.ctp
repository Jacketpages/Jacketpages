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
    <!--TODO - Clean up this CSS-->
    <table>
        <col width="20%">
        <col width="80%">
        <tr>
            <td style="vertical-align:middle"><img class="home_icon" src="/img/icons_home/bill.jpg"></td>
            <td style="vertical-align:middle">
                <h4>Bills</h4>
                <ul style="padding-left: 1.6em">
                    <li>Review financial bills allocating the Student Activity Fee to student organizations that have
                        been voted on or will be voted on soon
                    </li>
                    <li>Check out resolutions voted on affirming the student body opinion</li>
                    <li>Submit a financial request bill for your organization or a resolution supporting your cause</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:middle"><img src="/img/icons_home/org.jpg"></td>
            <td style="vertical-align:middle">
                <h4>Organizations</h4>
                <ul style="padding-left: 1.6em">
                    <li>Examine what bills have been submitted by organizations on campus</li>
                    <li>View the financial allocations received by student organizations</li>
                    <li>Follow the links to access organizations' information on OrgSync</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:middle"><img src="/img/icons_home/resources.png"></td>
            <td style="vertical-align:middle">
                <h4>Resources</h4>
                <ul style="padding-left: 1.6em">
                    <li>Learn more about the methods SGA uses to allocate funding to student organizations, bills and
                        budgets, and learn how to submit financial requests
                    </li>
                    <li>Take a look at the budgets allocated for the current and prior fiscal years</li>
                    <li>Calculate the maximum funding you can receive for travel using the Travel Calculator</li>
                    <li>Get answers to your frequently asked questions</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:middle"><img src="/img/icons_home/sga.png"></td>
            <td style="vertical-align: middle">
                <h4>SGA</h4>
                <ul style="padding-left: 1.6em">
                    <li>Get to know your Undergraduate Representatives and Graduate Student Senators</li>
                    <li>Contact SGA members for further information or support</li>
                    <li>Access the SGA website for more information on SGA initiatives</li>
                </ul>
            </td>
        </tr>
    </table>


    <p></p>
</div>