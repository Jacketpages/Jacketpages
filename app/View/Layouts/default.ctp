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
        <?php echo $this -> Html -> charset(); ?>
        <title><?php echo 'JacketPages'; ?> | <?php echo $title_for_layout; ?></title>
        <?php

		echo $this -> fetch('meta');
		echo $this -> Html -> script('//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js');
		echo $this -> Html -> script('http://code.jquery.com/jquery-1.9.1.js');
		echo $this -> Html -> script('http://code.jquery.com/ui/1.10.3/jquery-ui.js');
		echo $this -> fetch('script');
		echo $this -> Html -> css('icing');
		//echo $this -> Html -> css('print', 'stylesheet', array('media' => 'print'));
		echo $this -> Html -> css('jquery-ui-1.10.3.custom');
		echo $this -> fetch('css');
        ?>
        <script type="text/javascript">
			// @TODO Generate this script using the HTML and Js helpers
			$(document).ready(function()
			{
				/**
				 * Bind the top unorder lists to the open and close functions
				 */
				$('.utilityMenu li').bind('mouseover', openSubMenu);
				$('.utilityMenu ul').bind('mouseout', closeSubMenu);
				/**
				 * Find the nested unordered lists and make them visible or hidden depending
				 * on the event.
				 */
				function openSubMenu()
				{
					$(this).find('ul').css('visibility', 'visible');
				};

				function closeSubMenu()
				{
					$(this).find('ul').css('visibility', 'hidden');
				};

			});

			function openHelp()
			{
				$("#help").attr("style", "");
			}

			function closeHelp()
			{
				$("#help").attr("style", "display:none;");
			}

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
			$message = $this -> Session -> flash();
			if ($message == null)
			{
				$message = $this -> Session -> flash('auth');
			}
			if (strlen($message))
			{
				$message = $this -> Html -> tag('div', $message, array('id' => 'right'));
			}
			else if ($this -> Session -> read('User.name') != null)
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
			<div class="ui-overlay" id="help" style="display:none;">
				<div class="ui-widget-overlay"></div>
				<div class="ui-corner-all" id="overlay" style="width: 100%; height: 100%; position: absolute;">
					<?php
					if (!strcmp($this -> fetch('helppage'), ""))
						echo "This page is currently empty. Please let us know what would be most needed/helpful to put here.";
					echo $this -> fetch('helppage');
					echo $this -> Form -> button("X", array('onclick' => 'closeHelp()', 'style' => 'float:right;'));
				?></div>
			</div>

            <?php echo $this -> Session -> flash('auth'); ?><?php echo $this -> fetch('content'); ?>
            				<div id="footer">
                <?php
				echo $this -> Html -> link("Privacy Policy", array(
					'controller' => 'pages',
					'action' => 'privacy_policy'
				), array('style' => 'display:inline;float:left;text-decoration:none;color:#666;'));
				echo $this -> Html -> para('', date('Y') . ' Georgia Tech Student Government Association', array('style' => 'display:inline;'));
                ?>
					</div>
					</div>
        <?php echo $this -> element('sql_dump'); ?>
			</body>
			</html>
