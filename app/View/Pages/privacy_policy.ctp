<?php
/**
 * @author Stephen Roca
 * @since 8/9/2013
 */
$this -> extend("/Common/common");
$this -> assign('title', 'Privacy Policy');

echo $this -> Html -> tag('h1', 'Privacy Policy');
$this -> start('middle');
echo "The privacy of the users of JacketPages is of the highest priority. 
This privacy policy explains what information we collect from you and how we may use it. 
Note that the specific policies below are superseded, when applicable, 
by those provided by Georgia Tech's Office of Information Technology. 
Georgia Tech's policies can be found at " . $this -> Html -> link("http://gatech.edu/support/legal.html");

echo $this -> Html -> tag('h1', 'Information Collection And Use');

echo "If you use JacketPages, we request that you provide certain personal identifying information 
(such as name, email, and phone). We ask for and store this information 
only to help in communicating with you information directly related to JacketPages and/or your involvement 
with student organizations. We take precautions to keep this information secure. We do not disclose this 
information to third parties or other entities.";

echo $this -> Html -> tag('h1', 'Links to Other Web Sites');

echo "This privacy policy applies only to JacketPages. 
Links on JacketPages may direct you to other Web sites that we do not control. 
We are not responsible for the privacy practices, policies, actions, Web content, 
services or products of non-JacketPages sites to which we link. These links are not intended to, 
nor do they constitute, an endorsement of the linked materials.";

echo $this -> Html -> tag('h1', 'Use of Cookies');

echo "This Web site uses cookies to collect certain information. 
Cookies are small pieces of data that are sent by our Web site to your Web browser. 
They are stored on your computer and used to help us understand general traffic patterns on JacketPages and improve our Web site. 
Accepting a cookie does not give us access to your computer or any personal information about you.";
$this -> end();
?>