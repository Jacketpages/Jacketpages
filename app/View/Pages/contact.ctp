<?php
/**
 * This will be the Contact Jacketpages information page. It will describe the different reasons/types
 * of messages that should be sent to the Jacketpages email address.
 * @author Stephen Roca
 * @since 5/16/2012
 */

$this -> extend('/Common/common');
$this -> assign('title', 'Contact Jacketpages');

$this -> start('middle');
echo $this -> Html -> para(null, "The JacketPages' contact email is " . $this -> Html -> link('gtsgacampus@gmail.com', 'mailto:gtsgacampus@gmail.com') .
". To sort through the various reasons that students may need to contact JacketPages and to assign priorities to those emails, please include one of the ".
"following categories in the Subject of your email. The four different categories, (Administrative, Bug/Defect, Enhancement, Miscellaneous) are explained in more detail below.");

echo $this -> Html -> tag('h2', 'Administrative');
echo $this -> Html -> para(null, "<i>What to Include in the Subject:</i> \"Administrative\"");
echo $this -> Html -> para(null, "<i>How to Use this Category:</i> This category should be used when any \"Administrative\" issues arise. For example, when the previous " .
"officers of an organization are no longer reachable and the new officers have no way to add themselves as officers of that organization. Anything that deals with " .
"the maintenance of an account, an organization, or a bill where functionality is unavailiable to the user.");
echo $this -> Html -> para(null, "<i>What else to Include:</i> When referring to specific users and organizations, please include the names of all that are involved.");

echo $this -> Html -> tag('h2', 'Bugs and Defects');
echo $this -> Html -> para(null, "<i>What to Include in the Subject:</i> \"Bug\" or \"Defect\" or both");
echo $this -> Html -> para(null, "<i>How to Use this Category:</i> This category should be used for technical problems. Some technical problems could be a broken link ".
"a element, such as a bill, updates incorrectly. Any functionality that exists and is available that produces a wrong or illogical result.");
echo $this -> Html -> para(null, "<i>What else to Include:</i> The URL or transaction in which the problem arose, the user or users involved, and steps to reproduce the issue.");

echo $this -> Html -> tag('h2', 'Enhancements');
echo $this -> Html -> para(null, "<i>What to Include in the Subject:</i> \"Enhancement\"");
echo $this -> Html -> para(null, "<i>How to Use this Category:</i> This category should be used for any new features that would be helpful for JacketPages to implement. " .
"Try to be realistic and keep in mind that development takes time and the most needed feature will most likely be developed first.");
echo $this -> Html -> para(null, "<i>What else to Include:</i> Any helpful resources whether that be straight logic or links to sites that implement said feature.");

echo $this -> Html -> tag('h2', 'Miscellaneous');
echo $this -> Html -> para(null, "<i>What to Include in the Subject:</i> \"Miscellaneous\"");
echo $this -> Html -> para(null, "<i>How to Use this Category:</i> This category should be used for everything else. If there some annoying spelling mistake on the home ".
"page or something you want to tell the developers then use this category.");
echo $this -> Html -> para(null, "<i>What else to Include:</i> Anything you want. No spam please.");

$this -> end();
?>
