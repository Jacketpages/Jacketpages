<?php
/**
 * The default layout for Jacketpages.
 * @author Stephen Roca
 * @since 4/17/2012
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this -> Html -> charset();?>
        <title><?php echo 'JacketPages';?>:<?php echo $title_for_layout;?></title>
        <?php
      // Include any meta, css, and script links here.
      echo $this -> Html -> meta('icon');

      echo $this -> Html -> css('style');
      echo $this -> Html -> css('ddsmoothmenu.css');
      echo $this -> Html -> script('ddsmoothmenu.js');
      echo $this -> fetch('meta');
      echo $this -> fetch('css');
      echo $this -> fetch('script');
        ?>
    </head>
    <body>
        <div id="container"> 
<!--          was container   -->
            <div id="header">
                <?php
               // Link the Jacketpages logo to the Jacketpages home page
               echo $this -> Html -> link($this -> Html -> div("", "", array('id' => 'logoWrapper')), "/", array('escape' => false));
               echo $this -> element('utilityBar');
               
               // Output the Breadcrumbs Trail
               echo $this -> Html -> tag('div', $this -> Html -> tag('div', $this->Html->getCrumbs(' > ', 'Home'), array('id' => 'breadCrumbs', 'escape' => false)), array('id' => 'breadCrumbWrapper'));
               //echo $this->Html->getCrumbs(' > ', 'Home');
                ?>
                </div>
<!--                 <div id='allcontent' -->
                <div id="content">
                    <?php echo $this -> Session -> flash();?>

                    <?php echo $this -> fetch('content');?>
                </div>
<!--                 </div> -->
                <div id="footer"></div>
            </div>
            <?php echo $this -> element('sql_dump');?>
    </body>
</html>
