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
    <div id="page_title"><?php echo $this -> fetch('title');?></div>
    <?php 
    echo $this -> fetch('search');
    echo $this -> fetch('listing');
    ?>
</div>