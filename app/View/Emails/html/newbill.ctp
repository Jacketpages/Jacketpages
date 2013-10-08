A new bill was created with you as an author. 

Title: <?php echo $bill['Bill']['title']; ?>

Description: <?php echo $bill['Bill']['description']; ?>

Fundraising: <?php echo $bill['Bill']['fundraising']; ?>

<?php if(isset($undr_name)):?>
Undergraduate Author: <?php echo $undr_name; ?>

<?php endif;
if(isset($grad_name)):
?>
Graduate Author: <?php echo $grad_name; ?>
<?php endif;?>

Submitter: <?php echo $bill['Submitter']['name']; ?>

<?php if(isset($bill['Organization']['name'])):?>
Organization: <?php echo $bill['Organization']['name']; ?>
<?php endif;?>