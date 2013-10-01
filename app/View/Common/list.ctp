<?php
/**
 * @author Stephen Roca
 * @since 03/22/2012
 */
 ?>
 <div class="links left-nav" id="sidebar">
        <?php
      echo $this -> fetch('sidebar');
      echo $this -> element('sidebar');
        ?>
</div>
<div id="middle">
    <h1><?php echo $this -> fetch('title');?></h1>
    <?php 
    echo $this -> fetch('search');
    echo $this -> fetch('listing');
    ?>
</div>