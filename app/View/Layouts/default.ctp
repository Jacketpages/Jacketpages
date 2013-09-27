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
		
		// new gt theme css files
		echo $this -> Html -> css('gtv2/reset.css');
		echo $this -> Html -> css('gtv2/default.css');
		echo $this -> Html -> css('gtv2/fonts.css');
		echo $this -> Html -> css('gtv2/typography.css');
		echo $this -> Html -> css('gtv2/layout.css');
		echo $this -> Html -> css('gtv2/blocks.css');
		echo $this -> Html -> css('gtv2/content.css');
		echo $this -> Html -> css('gtv2/static.css');
		
		echo $this -> Html -> css('icing');
		
		
		//echo $this -> Html -> css('print', 'stylesheet', array('media' => 'print'));
		echo $this -> Html -> css('jquery-ui-1.10.3.custom');
		echo $this -> fetch('css');
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

			function openHelp() {
				$("#help").attr("style", "");
			}

			function closeHelp() {
				$("#help").attr("style", "display:none;");
			}

        </script>
		<script>
			(function(i, s, o, g, r, a, m) {
				i['GoogleAnalyticsObject'] = r;
				i[r] = i[r] ||
				function() {
					(i[r].q = i[r].q || []).push(arguments)
				}, i[r].l = 1 * new Date();
				a = s.createElement(o), m = s.getElementsByTagName(o)[0];
				a.async = 1;
				a.src = g;
				m.parentNode.insertBefore(a, m)
			})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

			ga('create', 'UA-43065423-1', 'gatech.edu');
			ga('send', 'pageview');

</script>
    </head>
    <body>
    <div id="page">
        <!--         <div id="container">  -->
        
        
        
        
		<div id="masthead">
		  
		  <div id="identity" class="clearfix">
		    <div id="identity-wrapper">
		      <h1 id="gt-logo">
		        <a href="/" rel="home" title="Georgia Tech" ><img alt="Georgia Tech" src="http://comm-whdev1.gatech.edu/sites/all/themes/gt/images/logos/logo-gt.png" /></a>
		      	<a href="/" rel="home" title="Georgia Tech" ><img style="float:right;" alt="Georgia Tech" src="/Jacketpages/img/jacketpages.png" /></a>     
		      </h1>
		    </div>
		  </div><!-- /#identity -->
		
		  <div id="primary-menus">
		    <div id="primary-menus-wrapper" class="clearfix">
		      <a id="primary-menus-toggle" class="hide-for-desktop"><span>Menu</span></a>
		      <div id="primary-menus-off-canvas" class="off-canvas">
		        <a id="primary-menus-close" class="hide-for-desktop"><span>Close</span></a>
		        
		        <div id="nav">
		          <?php
		          
		          // main menu bar links
				  echo $this -> element('mainMenuBar');
		          
		          // action bar links
				  echo $this -> element('actionBar');
				  
		          ?>
		        </div>
		        
		        <div id="utility">
		          <div class="row clearfix">
		          
		            <div id="utility-links">
		              <!-- utility-links -->
		              <ul class="menu">
		                <li class="mothership ulink"><a href="http://www.gatech.edu">Georgia Tech Home</a></li>
		                <li class="campus-map ulink"><a href="http://www.map.gatech.edu">Map</a></li>
		                <li class="directories ulink"><a href="http://www.gatech.edu/directory">Directory</a></li>
		                <li class="offices ulink"><a href="http://www.gatech.edu/departments">Offices</a></li>
		              </ul>
		            </div>
		            
		            <!--
		            <div id="social-media-links-wrapper">
		              <ul id="social-media-links">
		                <li class="first"><a href="https://www.facebook.com/georgiatech" title="Facebook" class="facebook">Facebook</a></li>
		                <li><a href="http://www.flickr.com/photos/georgiatech" title="Flickr" class="flickr">Flickr</a></li>
		                <li><a href="https://plus.google.com/+georgiatech/posts" title="Google Plus" class="googleplus">Google Plus</a></li>
		                <li><a href="http://www.linkedin.com/company/3558" title="LinkedIn" class="linkedin">LinkedIn</a></li>
		                <li><a href="http://pinterest.com/georgiatech/" title="Pinterest" class="pinterest">Pinterest</a></li>
		                <li><a href="http://gatech.edu/rss" title="RSS" class="rss">RSS</a></li>
		                <li><a href="https://twitter.com/georgiatech" title="Twitter" class="twitter">Twitter</a></li>
		                <li class="last"><a href="http://www.youtube.com/georgiatech" title="YouTube" class="youtube">YouTube</a></li>
		              </ul>
		            </div>
		            
		            /#social-media -->
		            
		          </div>
		        </div><!-- /#utility -->
		      </div>
		      
		      <div id="site-search">
		        <a id="site-search-container-switch">Search</a>
		        <div id="site-search-container">
		          <div id="search-gt">
		            <form action="http://search.gatech.edu/search" method="get">
		            <input  value="" name="q" id="q" class="form-text" type="text" />
		            <input name="site" value="default_collection" type="hidden" />
		            <input name="client" value="default_frontend" type="hidden" />
		            <input name="output" value="xml_no_dtd" type="hidden" />
		            <input name="proxystylesheet" value="default_frontend" type="hidden" />
		            <input name="proxyreload" value="1" type="hidden" /></form>
		          </div>
		        </div>
		      </div>
		      
		    </div><!-- /#primary-menus-wrapper -->
		    
		    <?php echo $this->element('breadcrumb')?>
		    
		  </div><!-- /#primary-menus -->
		    
		</div><!-- /#masthead -->
        
        </div>
        
        
        
        
        <!--
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
        -->
        
        <div id="content">
        <div class="clearfix">
			<div class="ui-overlay" id="help" style="display:none;">
				<div class="ui-widget-overlay"></div>
				<div class="ui-corner-all" id="overlay" style="width: 100%; height: 100%; position: absolute;">
					<?php
					if (!strcmp($this -> fetch('helppage'), ""))
						echo "This page is currently empty. Please let us know what would be most needed/helpful to put here.";
					echo $this -> fetch('helppage');
					echo $this -> Form -> button("X", array(
						'onclick' => 'closeHelp()',
						'style' => 'float:right;'
					));
				?></div>
			</div>

			<?php echo $this -> Session -> flash('auth'); ?><?php echo $this -> fetch('content'); ?>
            <!-- 
            <div id="footer_old">
                <?php /*
				echo $this -> Html -> link("Privacy Policy", array(
					'controller' => 'pages',
					'action' => 'privacy_policy'
				), array('style' => 'display:inline;float:left;text-decoration:none;color:#666;'));
				echo $this -> Html -> para('', date('Y') . ' Georgia Tech Student Government Association', array('style' => 'display:inline;'));
                */?>
			</div>
			-->
			 </div>
		</div><!-- content -->
			
			<div id="footer">
		    <div class="row clearfix">
		      <div id="footer-utility-links">

		        <ul class="menu">
		          <li class="last"><?php echo $this -> Html -> link("Privacy Policy", array(
					'controller' => 'pages',
					'action' => 'privacy_policy'
					)); ?>
				  </li>
		        </ul> 
		      </div>

		      <div id="footer-logo">
		        <a href="http://www.gatech.edu/"><img alt="Georgia Tech" src="http://comm-whdev1.gatech.edu/sites/all/themes/gt/images/logos/gt-logo-footer.png" ></a>
		        <a href="/" rel="home" title="Georgia Tech" ><img style="width:70px;" alt="Georgia Tech" src="/Jacketpages/img/sgalogogrey.png" /></a>     
		    
		        <p>&copy; <?php  print $year = date("Y"); ?> Georgia Tech Student Government Association</p>

		      </div>
		    </div>
		  </div><!-- /footer --> 
			
			
			
			
			
			</div>
        <?php echo $this -> element('sql_dump'); ?>
			</body>
			</html>
