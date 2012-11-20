<?php
/**
 * The default layout for Jacketpages.
 * @author Stephen Roca
 * @since 4/17/2012
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this -> Html -> charset();?>
        <title><?php echo 'JacketPages';?>|<?php echo $title_for_layout;?></title>
        <?php
      // Include any meta, css, and script links here.
      // echo $this -> Html -> meta('icon');
      echo $this -> Html -> css('icing');
      echo $this -> Html -> css('print', 'stylesheet', array('media' => 'print'));
      echo $this -> Html -> css('ui-lightness');
      // Include Jquery
      echo $this -> Html -> script('jquery-1.7.2.js');
      echo $this -> Html -> script('jquery-ui-1.8.21.min.js');

      // Fetch any other meta, css, or script libraries included in views
      echo $this -> fetch('meta');
      echo $this -> fetch('css');
      echo $this -> fetch('script');

      // echo $this -> Html -> scriptStart();
      // debug($this -> Html -> scriptBlock());
      // echo $this -> Js -> get('#utilityBar');
      // echo $this -> Js -> event('mouseover', $this -> Js -> get('ul') -> effect('show'));
      // echo $this -> Js -> event('mouseout', $this -> Js -> each("$(this).find('ul').css('visibility', 'hidden');"));
      // echo $this -> Html -> scriptEnd();
      // $stuff = $this -> Js -> value('$(this).find("ul").css("visibility", "visible");', array('escape' => 'false'));
      // echo $stuff;
      // debug($stuff);
        ?>
        <script type="text/javascript">
        
			// @TODO Generate this script using the HTML and Js helpers
			$(document).ready(function() {
				/**
				 * Bind the top unorder lists to the open and close functions
				 */
				$('.utilityMenu li').bind('mouseover', openSubMenu);
				$('.utilityMenu ul').bind('mouseout', closeSubMenu);
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
    <body>
        <!--         <div id="container">  -->
        <div id="header">
            <?php
            // Link the Jacketpages logo to the Jacketpages home page
            echo $this -> Html -> link($this -> Html -> div("", "", array('id' => 'logoWrapper')), "/", array('escape' => false));
            
            // Output the utility bar element
            echo $this -> element('utilityBar');

            // Get the BreadCrumbs for the left side of the Breadcrumbs bar
            $breadcrumbTrail = $this -> Html -> tag('div', $this -> Html -> getCrumbs(' > ', 'Home'), array('id' => 'left'));
            // Determine the message to display on the right side of the Breadcrumbs bar
            $message;
            if (strlen($this -> Session -> flash()))
            {
               $message = $this -> Html -> tag('div', $this -> Session -> flash(), array('id' => 'right'));
            }
            else
            if ($this -> Session -> read('User.name') != null)
            {
               $message = $this -> Html -> tag('div', "Welcome, " . $this -> Session -> read('User.name'), array('id' => 'right'));
            }
            else
            {
               $message = $this -> Html -> tag('div', "Welcome, Guest.", array('id' => 'right'));
            }
            
            // Output the Breadcrumbs bar
            echo $this -> Html -> tag('div', $this -> Html -> tag('div', $breadcrumbTrail . $message, array(
               'id' => 'breadCrumbs',
               'escape' => false
            )), array('id' => 'breadCrumbWrapper'));
            ?>
        </div>
        <div id="content">
            <?php echo $this -> Session -> flash('auth');?>
            <?php echo $this -> fetch('content');?>
            <div id="footer">
                <?php
               echo $this -> Html -> para('', date('Y') . ' Georgia Tech Student Government Association');
                ?>
            </div>
        </div>
        <?php echo $this -> element('sql_dump');?>
    </body>
</html>
