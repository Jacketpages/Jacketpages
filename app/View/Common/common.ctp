<?php

/*
	How the sidebarExists works:
		common is always called with $this->extend
		this makes Cake create an output buffer, ob_start()
		Then sidebar is filled with content in the views using $this->start('sidebar')
		When the view is done anything withing start and end for sidebar is then
			put into fetch('sidebar') on this page.
		Essentially fetch will wait until the view is done to output the sidebar
*/

?>


<?php if($sidebarExists = ($this->fetch('sidebar') != '')){ ?>
<div class="links left-nav" id="sidebar">
        <?php
      echo $this->fetch('sidebar');
      echo $this->element('sidebar');
        ?>
</div>
<?php } ?>

<div id="<?php echo ($sidebarExists)?'middle':'full_middle'; ?>">
    <div id="page_title"><?php echo $this -> fetch('title');?></div>
    <?php echo $this -> fetch('middle');?>
</div>