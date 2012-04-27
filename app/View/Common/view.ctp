<div class="actions" id="sidebars">
    <ul>
        <?php
      echo $this -> fetch('sidebar');
      echo $this -> element('sidebar');
        ?>
    </ul>
</div>
<div id="middle">
    <h1><?php echo $this -> fetch('title');?></h1>
</div>