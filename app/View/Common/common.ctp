<div class="links" id="sidebar">
        <?php
      echo $this -> fetch('sidebar');
      echo $this -> element('sidebar');
        ?>
</div>
<div id="middle">
    <h1><?php echo $this -> fetch('title');?></h1>
    <?php echo $this -> fetch('middle');?>
</div>