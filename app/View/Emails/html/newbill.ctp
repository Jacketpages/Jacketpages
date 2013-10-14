A new bill was created with you as an author: <?php echo $this -> Html -> link('http://jacketpages.gatech.edu/bills/view/' . $bill['Bill']['id']); ?>
<br><br>
Title:
<?php echo $bill['Bill']['title']; ?>
<br><br>
Description:
<?php echo nl2br($bill['Bill']['description']); ?>
<br><br>
Fundraising:
<?php echo nl2br($bill['Bill']['fundraising']); ?>
<br><br>
<?php if(isset($undr_name)):?>
Undergraduate Author:
<?php echo $undr_name; ?>
<br><br>
<?php endif;
if(isset($grad_name)):
?>
Graduate Author:
<?php echo $grad_name; ?>
<br><br>
<?php endif;?>
Submitter:
<?php echo $bill['Submitter']['name']; ?>
<br><br>
<?php if(isset($bill['Organization']['name'])):?>
Organization:
<?php echo $bill['Organization']['name']; ?>
<br><br>
Dues Collected: $
<?php echo $bill['Bill']['dues']; ?>
<br><br>
<?php endif;?>