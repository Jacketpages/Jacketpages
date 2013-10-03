A new bill was created with you as an author. Title:
<?php echo $bill['Bill']['title']; ?>

Description:
<?php echo $bill['Bill']['description']; ?>

Dues Collected $
<?php echo $bill['Bill']['dues']; ?>

Fundraising:
<?php echo $bill['Bill']['fundraising']; ?>
<?php if(isset($bill['UndergradAuthor']['User']['name'])):?>
Undergraduate Author:
<?php echo $bill['UndergradAuthor']['User']['name']; ?>
<?php endif;
if(isset($bill['GraduateAuthor']['User']['name'])):
?>
Graduate Author:
<?php echo $bill['GraduateAuthor']['User']['name']; ?>
<?php endif;?>
Submitter:
<?php echo $bill['Submitter']['name']; ?>
<?php if(isset($bill['Organization']['name'])):?>
Organization:
<?php echo $bill['Organization']['name']; ?>
<?php endif;?>