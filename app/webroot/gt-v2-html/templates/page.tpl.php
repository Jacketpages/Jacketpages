<?php
/**
 * @file
 * GT theme's implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
  * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 * - $gt_logo_file: The Georgia Tech logo image file as configured in theme settings.
 * - $site_title: The site title as configured in the theme settings.
 * - $site_title_class: The CSS class associated with the site title.
 * - $map_image: The map image for the super footer of the site as configured in theme 
 *   settings.
 * - $street_address: The street address below the map in the super footer as configured 
 *   in theme settings
 *
 * Menus and Search:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $secondary_menu_heading: The title of the menu used by the secondary links.
 * - $breadcrumb: The breadcrumb trail for the current page.
 * - $social_media_links: Social media links menu from GT Tools module.
 * - $action_links: Action links menu from GT Tools module.
 * - $footer_links_1: Superfooter links menu 1 from GT Tools module.
 * - $footer_links_2: Superfooter links menu 2 from GT Tools module.
 * - $footer_links_3: Superfooter links menu 3 from GT Tools module.
 * - $footer_ulinks: Footer utility links menu from GT Tools module.
 * - $search_option: Site search option markup as configured in the theme settings.
 *
 * Regions:
 * - $page['left_nav'] = Left Side Menu (displays above all other content in Left Sidebar)
 * - $page['left'] = Left Sidebar
 * - $page['content_lead'] = Area ABOVE Main Content (IGNORES right region)
 * - $page['help'] = Site help content
 * - $page['main_top'] = Area ABOVE Main Content (RESPECTS right region)
 * - $page['content'] = Main Content
 * - $page['main_bottom'] = Area BELOW Main Content (RESPECTS right region)
 * - $page['right'] = Right Sidebar 
 * - $page['content_close'] = Area BELOW Main Content (IGNORES right region)
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see gt_preprocess_page()
 * @see template_process()
 */
?>

<div id="page">
  
  <header id="masthead">
      
    <section id="identity" class="clearfix">
      <div id="identity-wrapper">
        <h1 id="gt-logo">
          <a href="<?php print $front_page; ?>" rel="home" title="<?php print $site_name; ?>" ><?php print $gt_logo_file; ?></a>
        </h1>
        <?php if ($site_title != '') : ?>
          <h2 class="<?php print $site_title_class; ?>" id="site-title"><?php print $site_title; ?></h2>
        <?php endif; ?>
      </div>
    </section><!-- /#identity -->
  
    <section id="primary-menus">
      <div id="primary-menus-wrapper" class="clearfix">
        <a id="primary-menus-toggle" class="hide-for-desktop"><span>Menu</span></a>
        <div id="primary-menus-off-canvas" class="off-canvas">
          <a id="primary-menus-close" class="hide-for-desktop"><span>Close</span></a>
          <nav>
            <div id="main-menu-wrapper">
              <?php if ($main_menu) : ?>          
                <?php print $primary_main_menu; ?>
                <?php if ($is_admin) : print $primary_main_menu_manage; endif; ?>
              <?php endif; ?>
            </div>
            <div id="action-items-wrapper">
              <?php if ($action_items): ?>
                <?php print theme('links', array('links' => $action_items, 'attributes' => array('id' => 'action-items'))); ?>
                <?php if ($is_admin) : print $action_items_manage; endif; ?>
              <?php else : ?>
                <?php if ($is_admin) : print $action_items_add; endif; ?>
              <?php endif; ?>
            </div>
          </nav>
          <div id="utility">
            <div class="row clearfix">
              <nav id="utility-links">
                <!-- utility-links -->
                <ul class="menu">
                  <li class="mothership ulink"><a href="http://www.gatech.edu">Georgia Tech Home</a></li>
                  <li class="campus-map ulink"><a href="http://www.map.gatech.edu">Map</a></li>
                  <li class="directories ulink"><a href="http://www.gatech.edu/directory">Directory</a></li>
                  <li class="offices ulink"><a href="http://www.gatech.edu/departments">Offices</a></li>
                </ul>
              </nav>
              <div id="social-media-links-wrapper">
                <?php if ($social_media_links): ?>
                  <?php print theme('links', array('links' => $social_media_links, 'attributes' => array('id' => 'social-media-links'))); ?>
                  <?php if ($is_admin) : print $social_media_links_manage; endif; ?>
                <?php else : ?>
                  <?php if ($is_admin) : print $social_media_links_add; endif; ?>
                <?php endif; ?>
              </div>
            </div>
          </div><!-- /#utility -->
        </div>
        <div id="site-search">
          <?php if ($search_option_value == 0 || $search_option_value == 2) : ?> 
            <a href="<?php print $base_path; ?>search" id="site-search-container-switch">Search</a>
          <?php else : ?>
            <a href="http://search.gatech.edu" id="site-search-container-switch">Search</a>
          <?php endif; ?>
          <div id="site-search-container">
            <?php print $search_option; ?>
          </div>
        </div>
      </div><!-- /#primary-menus-wrapper -->
      <div id="breadcrumb" class="hide-for-mobile">
        <div class="row clearfix">
          <ul><?php print $breadcrumb; ?></ul>
        </div>
      </div><!-- /#breadcrumb --> 
    </section><!-- /#primary-menus -->
      
    <!--
    Placeholder for TLW/College home page features
    <section id="main-feature">
      
    </section>
    -->
    
  </header><!-- /#masthead -->
  
  <section id="main">
    <div class="row clearfix">
      
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h2 class="title" id="page-title"><?php print $title; ?></h2>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
      
      <?php
        // Check for content lead/close and left_nav
        $content_lead = render($page['content_lead']);
        $content_close = render($page['content_close']);
      ?>
      
      <?php if ($content_lead) : ?>
        <div id="content-lead">
           <?php print $content_lead; ?>
        </div>
      <?php endif; ?>
      
      <div class="<?php print $content_class; ?>" id="content">
        
        <?php
          // Check for content page help and tabs
          $page_help = render($page['help']);
          $page_tabs = render($tabs);
        ?>
        
        <?php if ($messages || $page_help || $page_tabs || $action_links) : ?> 
          <div id="support">
            <?php print $messages; ?>
            <?php print render($page['help']); ?>
            <?php print render($tabs); ?>
            <?php if ($action_links) : ?>
              <ul class="action-links">
                <?php print render($action_links); ?>
              </ul>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <?php print render($page['main_top']); ?>
        <?php print render($page['content']); ?>
        <?php print render($page['main_bottom']); ?>
        <?php print $feed_icons; ?>
      </div><!-- /#content -->
      
      <?php
        // Render the sidebars to see if there's anything in them.
        $left_nav = render($page['left_nav']);
        $sidebar_left  = render($page['left']);
        $sidebar_right = render($page['right']);
      ?>
      
      <?php if ($left_nav || $sidebar_left): ?>
        <aside id="sidebar-left" class="<?php print $sidebar_left_class; ?>">
          <?php if ($left_nav) : ?>
            <nav id="left-nav">
               <?php print $left_nav; ?>
            </nav>
          <?php endif; ?>
          <?php print $sidebar_left; ?>
        </aside>
      <?php endif; ?>
      
      <?php if ($sidebar_right): ?>
        <aside id="sidebar-right" class="<?php print $sidebar_right_class; ?>">
          <?php print $sidebar_right; ?>
        </aside>
      <?php endif; ?>
      
      <?php if ($content_close) : ?>
        <div id="content-close">
           <?php print $content_close; ?>
        </div>
      <?php endif; ?>
      
    </div>
  </section><!-- /#main -->

  <section id="superfooter">
    <div class="row clearfix">
      
      <div class="superfooter-resource-links" id="gt-default-resource-links">
        <h4 class="title">Georgia Tech Resources</h4>
        <ul class="menu" id="gt-default-resources">
          <li><a href="http://www.gatech.edu/departments">Offices &amp; Departments</a></li>
          <li><a href="http://www.news.gatech.edu">Newsroom</a></li>
          <li><a href="http://www.calendar.gatech.edu">Campus Calendar</a></li>
          <li><a href="http://www.specialevents.gatech.edu">Special Events</a></li>
          <li><a href="http://www.greenbuzz.gatech.edu">GreenBuzz</a></li>
          <li><a href="http://www.comm.gatech.edu">Institute Communications</a></li>
          <li><span class="nolink">Visitor Resources</span></li>
          <li><a href="http://www.admission.gatech.edu/visit">Campus Visits</a></li>
          <li><a href="http://www.gatechhotel.com">Georgia Tech Hotel &amp; Conference Center</a></li>
          <li><a href="http://www.glc.gatech.edu">Georgia Tech Global Learning Center</a></li>
          <li><a href="http://www.pts.gatech.edu/visitors/Pages/default.aspx">Visitor Parking Information</a></li>
          <li><a href="http://www.admission.gatech.edu/visit/directions-and-parking">Directions to Campus</a></li>
          <li><a href="http://www.lawn.gatech.edu/help/GTvisitor.html">GTvisitor Wireless Network Information</a></li>
          <li><a href="http://www.gatech.bncollege.com">Barnes &amp; Noble at Georgia Tech</a></li>
          <li><a href="http://www.ferstcenter.gatech.edu">Robert Ferst Center for the Arts</a></li>
          <li><a href="http://www.ipst.gatech.edu/amp">Robert C. Williams Paper Museum</a></li>
        </ul>
      </div>
      
      <div class="superfooter-resource-links" id="gt-footer-links-1">
        <?php if ($footer_links_1): ?>
          <?php print $footer_links_1; ?>
          <?php if ($is_admin) : print $footer_links_1_manage; endif; ?>
        <?php else : ?>
          <?php if ($is_admin) : print $footer_links_1_add; endif; ?>
        <?php endif; ?>
      </div>
      
      <div class="superfooter-resource-links"  id="gt-footer-links-2">
        <?php if ($footer_links_2): ?>
          <?php print $footer_links_2; ?>
          <?php if ($is_admin) : print $footer_links_2_manage; endif; ?>
        <?php else : ?>
          <?php if ($is_admin) : print $footer_links_2_add; endif; ?>
        <?php endif; ?>
      </div>
      
      <div class="superfooter-resource-links" id="gt-footer-links-3">
        <?php if ($footer_links_3): ?>
          <?php print $footer_links_3; ?>
          <?php if ($is_admin) : print $footer_links_3_manage; endif; ?>
        <?php else : ?>
          <?php if ($is_admin) : print $footer_links_3_add; endif; ?>
        <?php endif; ?>
      </div>
      
      <div id="street-address-info">
        <?php print $map_image ?>
        <div class="street-address"><?php print $street_address; ?></div>
      </div>
      
    </div>
  </section><!-- /superfooter -->

  <footer id="footer">
    <div class="row clearfix">
      <div id="footer-utility-links">
        <?php if ($footer_ulinks): ?>
          <?php print $footer_ulinks; ?>
          <?php if ($is_admin) : print $footer_ulinks_manage; endif; ?>
        <?php else : ?>
          <?php if ($is_admin) : print $footer_ulinks_add; endif; ?>
        <?php endif; ?>
        <ul class="menu<?php if ($footer_ulinks): print ' custom-links-included'; endif; ?>">
          <li><a href="http://www.gatech.edu/emergency/">Emergency Information</a></li>
          <li><a href="http://www.gatech.edu/legal/">Legal &amp; Privacy Information</a></li>
          <li><a href="http://www.gatech.edu/accountability/">Accountability</a></li>
          <?php if ($footer_login_link) : ?>
            <li><a href="http://www.careers.gatech.edu">Employment</a></li>
            <?php if ($user->uid >= 1) : ?>
              <?php if (module_exists('cas')) : ?>
                <li class="gt-site-logout"><a href="<?php print base_path(); ?>caslogout" title="Log out of the site AND end your GT login session">GT Logout</a></li>
                <li class="site-logout last"><a href="<?php print base_path(); ?>user/logout" title="Log out of the site ONLY (GT login session will remain active)">Site Logout</a></li>
              <?php else : ?>
                <li class="site-logout last"><a href="<?php print base_path(); ?>user/logout">Logout</a></li>
              <?php endif; ?>
            <?php else : ?>
              <li class="site-login last"><a href="<?php print base_path(); ?>user">Login</a></li>
            <?php endif; ?>
          <?php else : ?>
            <li class="last"><a href="http://www.careers.gatech.edu">Employment</a></li>
          <?php endif; ?>
        </ul> 
      </div>
      <div id="footer-logo">
        <a href="http://www.gatech.edu/"><img alt="Georgia Tech" src="<?php print $theme_path; ?>/images/logos/gt-logo-footer.png" ></a>
        <p>&copy; <?php  print $year = date("Y"); ?> Georgia Institute of Technology</p>
      </div>
    </div>
  </footer><!-- /footer --> 
  
</div><!-- /#page --> 

<?php print render($page['bottom']); ?> 