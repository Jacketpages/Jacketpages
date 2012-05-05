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
        <title><?php echo 'JacketPages';?>|<?php echo $title_for_layout;?></title>
        <?php
         // Include any meta, css, and script links here.
         echo $this -> Html -> meta('icon');
         echo $this -> Html -> css('icing');
         echo $this -> Html -> css('print', 'stylesheet', array('media' => 'print'));
         // Include Jquery
         echo $this -> Html -> script('jquery-1.7.2.js');
   
         // Fetch any other meta, css, or script libraries included in views
         echo $this -> fetch('meta');
         echo $this -> fetch('css');
         echo $this -> fetch('script');
        ?>
        <script type="text/javascript">
        // @TODO Generate this script using the HTML and Js helpers
			$(document).ready(function() {
/**
 * Bind the top unorder lists to the open and close functions
 */
				$('.utilityMenu > ul').bind('mouseover', openSubMenu);
				$('.utilityMenu > ul').bind('mouseout', closeSubMenu);
/**
 * Find the nested unordered lists and make them visible or hidden depending
 * on the event.
 */
				function openSubMenu() {
					$(this).find('ul').css('visibility', 'visible');
				};

				function closeSubMenu() {
					$(this).find('ul').css('visibility', 'hidden');
				};
			});

        </script>
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
            <?php echo $this -> Session -> flash('auth');?>
            <?php echo $this -> fetch('content');?>
            <div id="footer"></div>
        </div>
        <?php echo $this -> element('sql_dump');?>
    </body>
</html>
