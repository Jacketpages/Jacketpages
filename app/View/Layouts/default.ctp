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
        <title><?php echo 'JacketPages';?> | <?php echo $title_for_layout;?></title>
        
<!-- Defines the wr variable as the path to the webroot folder for ddsmoothmenu.js use -->
        <script type="text/javascript">
			var wr = '/Jacketpages/app/webroot/';
        </script>
        <?php
      // Include any meta, css, and script links here.
      echo $this -> Html -> meta('icon');
      echo $this -> Html -> css('icing');
      echo $this -> Html -> css('ddsmoothmenu.css');
      echo $this -> Html -> css('print', 'stylesheet', array('media' => 'print'));
      // Include Jquery for use with JsHelper
      echo $this -> Html -> script('jquery');
      // Drop down menu script
      echo $this -> Html -> script('ddsmoothmenu.js');
      // Needed to make the drop down menus in the utility bar function. (ddsmoothmenu dependecy)
      echo $this -> Html -> script('jquery-1.5.1.min.js');
      // Initializes the ddsmooth menu
      echo $this -> Html -> script('custom.js');

      // Fetch any other meta, css, or script libraries included in views
      echo $this -> fetch('meta');
      echo $this -> fetch('css');
      echo $this -> fetch('script');
        ?>
    </head>
    <body class='links'>
        <!--         <div id="container">  -->
        <div id="header">
            <?php
            // Link the Jacketpages logo to the Jacketpages home page
            echo $this -> Html -> link($this -> Html -> div("", "", array('id' => 'logoWrapper')), "/", array('escape' => false));
            echo $this -> element('utilityBar');

            // Output the Breadcrumbs Trail
            echo $this -> Html -> tag('div', $this -> Html -> tag('div', $this -> Html -> getCrumbs(' > ', 'Home'), array(
               'id' => 'breadCrumbs',
               'escape' => false
            )), array('id' => 'breadCrumbWrapper'));
            ?>
        </div>
        <div id="content">
            <?php echo $this -> Session -> flash();?>
            <?php echo $this -> fetch('content');?>
            <div id="footer"></div>
        </div>
        <?php echo $this -> element('sql_dump');
         echo $this -> Js -> writeBuffer();
        ?>
    </body>
</html>
