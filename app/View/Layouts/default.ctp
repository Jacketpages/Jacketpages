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
		echo $this -> Html -> css('gtv2/reset');
		echo $this -> Html -> css('gtv2/default');
		echo $this -> Html -> css('gtv2/fonts');
		echo $this -> Html -> css('gtv2/typography');
		echo $this -> Html -> css('gtv2/layout');
		echo $this -> Html -> css('gtv2/blocks');
		echo $this -> Html -> css('gtv2/content');
		echo $this -> Html -> css('gtv2/editor');
		echo $this -> Html -> css('gtv2/static');
		
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
        <!--         <div id="container">  -->
        
        
        
        
		<div id="masthead">
		  
		  <div id="identity" class="clearfix">
		    <div id="identity-wrapper">
		      <h1 id="gt-logo">
		        <a href="/" rel="home" title="Georgia Tech" ><img alt="Georgia Tech" src="http://comm-whdev1.gatech.edu/sites/all/themes/gt/images/logos/logo-gt.png" /></a>    
		      </h1>
		      <a href="/" rel="home" title="Georgia Tech" style="float: right">
		      		<?php echo $this->Html->image('jacketpages.png', array('alt' => 'Georgia Tech')); ?>
		      	</a> 
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
				<?php echo $this -> Html -> link('Search', array(
					'controller' => 'organizations',
					'action' => ''),
					array('id'=>'site-search-container-switch')); ?>
		      </div>
		      
		    </div><!-- /#primary-menus-wrapper -->
		    
		    <?php echo $this->element('breadcrumb')?>
		    
		  </div><!-- /#primary-menus -->
		    
		</div><!-- /#masthead -->
        
        </div>
        
        <div id="content">
        <div class="clearfix">
			<div class="ui-overlay" id="help" style="display:none;">
				<div class="ui-widget-overlay"></div>
				<div class="ui-corner-all" id="overlay" style="width: 100%; height: 600px; position: absolute;overflow-y:scroll; margin-top:10px;">
					<?php
					echo $this -> Form -> button("X", array(
						'onclick' => 'closeHelp()',
						'style' => 'float:right;'
					));
					echo $this -> element('helppage');
				?></div>
			</div>

			<?php echo $this -> Session -> flash('auth'); ?>
			<?php echo $this -> fetch('content'); ?>
			 </div>
		</div><!-- content -->
			
			<div id="footer">
		    <div class="row clearfix">
		      <div id="footer-utility-links">
				<?php echo $this->element('footer_menu') ?>
		      </div>

		      <div id="footer-logo">
		        <a href="/" rel="home" title="Georgia Tech" style="margin-right:4px">
		        	<?php echo $this->Html->image('sgalogogrey.png', array('alt' => 'Georgia Tech')); ?>
		        </a>     
		    
		        <p>&copy; <?php  print $year = date("Y"); ?> Georgia Tech Student Government Association</p>

		      </div>
		    </div>
		  </div><!-- /footer --> 
			
			
			
			
			
        <?php echo $this -> element('sql_dump'); ?>
			</body>
			</html>
