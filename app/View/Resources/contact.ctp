<?php

$this->extend('/Common/common');
$this->Html->addCrumb('Resources', '/resources');
$this->Html->addCrumb('Contact Us', '/resources/contact');
$this->assign('title', 'Contact Us');
$this->start('middle');

echo $this->Form->create('Contact', array('onsubmit' => 'return validateForm()'));
echo $this->Form->input('name', array(
    'label' => 'Name',
    'default' => $name));
echo $this->Form->input('email', array(
    'label' => 'Email',
    'type' => 'email',
    'default' => $email));
echo $this->Form->input('organization', array('label' => 'Organization'));
echo $this->Form->input('position', array('label' => 'Position (President, Treasurer, etc.)'));
echo $this->Form->input('subject', array(
    'label' => 'Subject',
    'options' => array(
        'Requesting Funding' => 'Requesting Funding',
        'Current Bill/Budget' => 'Current Bill/Budget',
        'Feedback' => 'Feedback',
        'Other' => 'Other'
    )
));
echo $this->Form->input('message', array(
    'label' => 'Message',
    'type' => 'textarea'
));
echo $this->Form->submit('Send', array(
    'formnovalidate',
    'onclick' => 'openToolTips()'
));

/*echo '<div id="notification">';
echo "If applicable, please include at least one line item. Should you intend on submitting many line items, you may want to continue adding line items using the link on the next page and saving your updates periodically since your login session may time out. ";
echo '</div>';*/

$this->end();
?>
