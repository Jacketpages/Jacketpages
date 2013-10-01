<?php
/**
 * @author Stephen Roca
 * @since 03/22/2012
 */

/* How the sidebarExists is in Common/common */
?>


<?php if($sidebarExists = ($this->fetch('sidebar') != '')){ ?>
<div class="links left-nav" id="sidebar">
        <?php
      echo $this -> fetch('sidebar');
      echo $this -> element('sidebar');
        ?>
</div>
<?php } ?>

<div id="<?php echo ($sidebarExists)?'middle':'full_middle'; ?>">
    <h1><?php echo $this -> fetch('title');?></h1>
    <?php 
    echo $this -> fetch('search');
    echo $this -> fetch('listing');
    ?>
</div>