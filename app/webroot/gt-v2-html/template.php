<?php
/**
 * @file
 * Contains theme override functions and preprocess functions for the theme.
 */

/**
 * Override or insert variables into the html templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */
function gt_preprocess_html(&$variables, $hook) { 
    
}


/**
 * Override or insert variables into the page templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 * @TODO: gt_tools check doesn't work properly with overlay turned on.
 */
function gt_preprocess_page(&$variables, $hook) {

  // Use of GT Tools module is mandatory. Otherwise everything will break.
  if (!module_exists('gt_tools')) {
    variable_set('theme_default', 'bartik');
    drupal_get_messages();
    drupal_set_message(
      t("Sorry! You'll need to !enable before you can use the GT Theme.", array('!enable' => l('enable the GT Tools module', 'admin/modules'))), 'error');
    drupal_goto($_GET['q']);
    return;
  }

  // Theme path variable
  $variables['theme_path'] = base_path() . drupal_get_path('theme', 'gt');
  
  // Main menu
  $standard_main_menu = menu_tree_output(menu_tree_all_data('main-menu'));
  $variables['primary_main_menu'] = render($standard_main_menu);
  $variables['primary_main_menu_manage'] = l(t('Manage Links'), 'admin/structure/menu/manage/main-menu', array('query' => drupal_get_destination(), 'attributes' => array('class' => array('gt-tools-contextual-link', 'populated'), 'title' => 'Manage Links'),));
  
  // GT Tools menus variables

  // social media links
  $variables['social_media_links'] = menu_navigation_links('gt-social-media');
  foreach ($variables['social_media_links'] as &$link) {
    $link['attributes']['class'][] = str_replace(' ', '', strtolower($link['title']));
  }

  $variables['social_media_links_manage'] = l(t('Manage Links'), 'admin/structure/menu/manage/gt-social-media', array('query' => drupal_get_destination(), 'attributes' => array('class' => array('gt-tools-contextual-link', 'populated'), 'title' => 'Manage Links'),));
  if (module_exists('gt_tools')) {
    $variables['social_media_links_add'] = l(t('Add Social Media Links Here'), 'admin/structure/menu/manage/gt-social-media', array('query' => drupal_get_destination(), 'attributes' => array('class' => array('gt-tools-contextual-link', 'empty'),),));
  } else {
    $variables['social_media_links_add'] = '<div class="gt-tools-contextual-link empty">' . t('Install GT Tools to add social media links here') . '.</div>';
  }
  
  // action items links
  $variables['action_items'] = menu_navigation_links('gt-action-items');
  $variables['action_items_manage'] = l(t('Manage Links'), 'admin/structure/menu/manage/gt-action-items', array('query' => drupal_get_destination(), 'attributes' => array('class' => array('gt-tools-contextual-link', 'populated'), 'title' => 'Manage Links'),));
  if (module_exists('gt_tools')) {
    $variables['action_items_add'] = l(t('Add Action Links Here'), 'admin/structure/menu/manage/gt-action-items', array('query' => drupal_get_destination(), 'attributes' => array('class' => array('gt-tools-contextual-link', 'empty'),),));
  } else {
    $variables['action_items_add'] = '<div class="gt-tools-contextual-link empty">' . t('Install GT Tools to add action links here') . '.</div>';
  }
  
  // superfooter resources links */ 
  for ($i = 1; $i < 4; $i++) {
    $footer_links[$i]['links'] = menu_navigation_links('gt-footer-links-' . $i);
    $footer_links[$i]['info'] = menu_load('gt-footer-links-' . $i);
    $variables['footer_links_' . $i] = theme('links', array(
      'links' => $footer_links[$i]['links'], 
      'attributes' => array(
        'class' => array('menu'),
      ),
      'heading' => array(
        'text' => t($footer_links[$i]['info']['title']),
        'level' => 'h4',
        'class' => array('title'),
      ),
    ));
    $variables['footer_links_' . $i . '_manage'] = l(t('Manage Links'), 'admin/structure/menu/manage/gt-footer-links-' . $i, array('query' => drupal_get_destination(), 'attributes' => array('class' => array('gt-tools-contextual-link', 'populated'), 'title' => 'Manage Links'),));
    if (module_exists('gt_tools')) {
      $variables['footer_links_' . $i . '_add'] = l(t('Add Resource Links Here'), 'admin/structure/menu/manage/gt-footer-links-' . $i, array('query' => drupal_get_destination(), 'attributes' => array('class' => array('gt-tools-contextual-link', 'empty'),),));
    } else {
      $variables['footer_links_' . $i . '_add'] = '<div class="gt-tools-contextual-link empty">' . t('Install GT Tools to add resource links here') . '.</div>';
    }
  }
  
  // footer utility links */ 
  $footer_ulinks_links = menu_navigation_links('gt-footer-utility-links');
  $variables['footer_ulinks'] = theme('links', array(
    'links' => $footer_ulinks_links, 
    'attributes' => array(
      'class' => array('menu', 'gt-footer-utility-links'),
    ),
  ));
  $variables['footer_ulinks_manage'] = l(t('Manage Links'), 'admin/structure/menu/manage/gt-footer-utility-links', array('query' => drupal_get_destination(), 'attributes' => array('class' => array('gt-tools-contextual-link', 'populated'), 'title' => 'Manage Links'),));
  if (module_exists('gt_tools')) {
    $variables['footer_ulinks_add'] = l(t('Add Footer Utility Links Here'), 'admin/structure/menu/manage/gt-footer-utility-links', array('query' => drupal_get_destination(), 'attributes' => array('class' => array('gt-tools-contextual-link', 'empty'),),));
  } else {
    $variables['footer_ulinks_add'] = '<div class="gt-tools-contextual-link empty">' . t('Install GT Tools to add footer utility links here') . '.</div>';
  }

  // GT Logo variable
  $logo_default_flag = theme_get_setting('gt_logo_default');
  $logo_upload_fid = theme_get_setting('gt_logo_upload_file');
  $logo_upload_url = theme_get_setting('logo_url');
  if ($logo_upload_fid != '' && $logo_default_flag == '' && $logo_upload_file = file_load($logo_upload_fid)) {
    $logo_upload_file_url = file_create_url($logo_upload_file->uri);
    $variables['gt_logo_file'] = '<img alt="' . $variables['site_name'] . '" class="uploaded-logo-file" src="' . $logo_upload_file_url . '" />';
    if ($logo_upload_url != '') {
      $variables['gt_logo_right_url'] = $logo_upload_url;
      $variables['gt_logo_right_title'] = $variables['site_name'];
    } else {
      $variables['gt_logo_right_url'] = '';
      $variables['gt_logo_right_title'] = '';
    }
  } else {
    $gt_logo_selection = theme_get_setting('gt_logo_type');
    switch ($gt_logo_selection) {
      case 0:
        $variables['gt_logo_file'] = '<img alt="Georgia Tech" src="' . $variables['theme_path'] . '/images/logos/logo-gt.png" />';
        $variables['gt_logo_right_url'] = '';
        $variables['gt_logo_right_title'] = '';
        break;
      case 1:
        $variables['gt_logo_file'] = '<img alt="Georgia Tech | College of Architecture" src="' . $variables['theme_path'] . '/images/logos/logo-gt-coa.png" />';
        $variables['gt_logo_right_url'] = 'http://www.coa.gatech.edu';
        $variables['gt_logo_right_title'] = 'College of Architecture';
        break;
      case 2:
        $variables['gt_logo_file'] = '<img alt="Georgia Tech | College of Computing" src="' . $variables['theme_path'] . '/images/logos/logo-gt-coc.png" />';
        $variables['gt_logo_right_url'] = 'http://www.cc.gatech.edu';
        $variables['gt_logo_right_title'] = 'College of Computing';
        break;
      case 3:
        $variables['gt_logo_file'] = '<img alt="Georgia Tech | College of Engineering" src="' . $variables['theme_path'] . '/images/logos/logo-gt-coe.png" />';
        $variables['gt_logo_right_url'] = 'http://www.coe.gatech.edu';
        $variables['gt_logo_right_title'] = 'College of Engineering';
        break;
      case 4:
        $variables['gt_logo_file'] = '<img alt="Georgia Tech | College of Sciences" src="' . $variables['theme_path'] . '/images/logos/logo-gt-cos.png" />';
        $variables['gt_logo_right_url'] = 'http://www.cos.gatech.edu';
        $variables['gt_logo_right_title'] = 'College of Sciences';
        break;
      case 5:
        $variables['gt_logo_file'] = '<img alt="Georgia Tech | Ivan Allen College of Liberal Arts" src="' . $variables['theme_path'] . '/images/logos/logo-gt-iac.png" />';
        $variables['gt_logo_right_url'] = 'http://www.iac.gatech.edu';
        $variables['gt_logo_right_title'] = 'Ivan Allen College of Liberal Arts';
        break;
      case 6:
        $variables['gt_logo_file'] = '<img alt="Georgia Tech | Scheller College of Business" src="' . $variables['theme_path'] . '/images/logos/logo-gt-scheller.png" />';
        $variables['gt_logo_right_url'] = 'http://www.scheller.gatech.edu';
        $variables['gt_logo_right_title'] = 'Scheller College of Business';
        break;
      default:
        $variables['gt_logo_file'] = '<img alt="Georgia Tech" src="' . $variables['theme_path'] . '/images/logos/logo-gt.png" />';
    }
  }
  
  // Site title and site title class variables
  $site_title_line_1 = theme_get_setting('site_title_line_1');
  $site_title_line_2 = theme_get_setting('site_title_line_2');
  if ($site_title_line_1 != '') {
    if ($site_title_line_2 != '') {
      $variables['site_title_class'] = 'two-line';
      $variables['site_title'] = $site_title_line_1 . '<br />' . $site_title_line_2;
    } else {
      $variables['site_title_class'] = 'one-line';
      $variables['site_title'] = $site_title_line_1;
    }
  } else {
    $variables['site_title'] = '';
  }
    
  // Site search option variable
  include('inc/template.site_search.inc');
  $search_option = theme_get_setting('search_option');
  $variables['search_option_value'] = $search_option;
  switch ($search_option) {
    case 0:
      $variables['search_option'] = $search_local;
      break;
    case 1:
      $variables['search_option'] = $search_gt;
      break;
    case 2:
      $variables['search_option'] = $search_user_choice;
      break;
    default:
      $variables['search_option'] = $search_gt;
  }
  // Switch to GT search if search module is turned off
  if (!module_exists('search')) { $variables['search_option'] = $search_gt; }
  
  // sidebar indicator classes for main content area
  $variables['content_class'] = 'no-sidebars';
  if (!empty($variables['page']['left']) || !empty($variables['page']['left_nav'])) {
    $variables['content_class'] = 'sidebar-left one-sidebar';
    $variables['sidebar_right_class'] = 'with-sidebar-left';
  } else {
    $variables['sidebar_right_class'] = 'solo-sidebar';
  }
  if (!empty($variables['page']['right'])) {
    $variables['content_class'] = ($variables['content_class'] == 'sidebar-left one-sidebar') ? 'both-sidebars' : 'sidebar-right one-sidebar';
    $variables['sidebar_left_class'] = 'with-sidebar-right';
  } else {
    $variables['sidebar_left_class'] = 'solo-sidebar';
  }
  
  // Footer map image variable
  $map_image_upload_fid = theme_get_setting('map_image_upload');
  if ($map_image_upload_fid != '') {
    $map_upload_file = file_load($map_image_upload_fid);
    $map_upload_file_url = file_create_url($map_upload_file->uri);
    $variables['footer_map_image_file'] = '<img alt="Map of ' . $variables['site_name'] . '" class="uploaded-map-image-file" src="' . $map_upload_file_url . '" />';
  } else {
    $variables['footer_map_image_file'] = '<img alt="Map of Georgia Tech" src="' . $variables['theme_path'] . '/images/gt-map-image-default.jpg" />';
  }
  $map_image_custom_link = theme_get_setting('map_image_link');
  if ($map_image_custom_link != '') {
    $variables['map_image'] = l($variables['footer_map_image_file'], $map_image_custom_link, array('html' => TRUE));
  } else {
    $variables['map_image'] = l($variables['footer_map_image_file'], 'http://www.gtalumni.org/map', array('html' => TRUE));
  }
  
  // Street address
  $street_address = theme_get_setting('street_address');
  if ($street_address != '') {
    $variables['street_address'] = check_markup($street_address);
  } else {
    $variables['street_address'] = check_markup("Georgia Institute of Technology\nNorth Avenue, Atlanta, GA 30332\nPhone: 404-894-2000");
  }
  
  // Footer login link flag variable
  $variables['footer_login_link'] = theme_get_setting('login_link_option');
  
  // Directory URL variable
  $variables['directory_url'] = module_exists('gt_directory') ? '/directory' : 'http://gatech.edu/directory';

}

/**
 * Duplicate of theme_menu_local_tasks() but adds clearfix to tabs.
 */
function gt_menu_local_tasks(&$variables) {
  $output = '';
  if (!empty($variables['primary'])) {
    $variables['primary']['#prefix'] = '<h2 class="element-invisible">' . t('Primary tabs') . '</h2>';
    $variables['primary']['#prefix'] .= '<ul class="tabs primary clearfix">';
    $variables['primary']['#suffix'] = '</ul>';
    $output .= drupal_render($variables['primary']);
  }
  if (!empty($variables['secondary'])) {
    $variables['secondary']['#prefix'] = '<h2 class="element-invisible">' . t('Secondary tabs') . '</h2>';
    $variables['secondary']['#prefix'] .= '<ul class="tabs secondary clearfix">';
    $variables['secondary']['#suffix'] = '</ul>';
    $output .= drupal_render($variables['secondary']);
  }
  return $output;
}

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function gt_preprocess_node(&$variables) {
  $node = $variables['node'];
  if ($variables['view_mode'] == 'full' && node_is_page($variables['node'])) {
    $variables['classes_array'][] = 'node-full';
  }
  
  include('inc/template.gt_tools_content_types.inc');
  
}

/**
 * Override or insert variables into the region templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 */
function gt_preprocess_region(&$variables) {
  // adding a block count class to the region
  $block_count = count(block_list($variables['region']));
  $block_count > 4 ? $block_count_class = 'block-count-4' : $block_count_class = 'block-count-' . $block_count;
  $variables['classes_array'][] = $block_count_class;
  $variables['classes_array'][] = 'clearfix';
}
   
/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
function gt_preprocess_block(&$variables, $hook) {
  
  /**
   * Block helper classes
   */
  
  // Adding region weight (placement) class
  $variables['classes_array'][] = 'block-region-weight-' . $variables['elements']['#weight'];
  
  // Adding row limit break classes
  $region_ordinal_weight = ($variables['elements']['#weight'] -1);
  if ($region_ordinal_weight != 0) {
    if ($region_ordinal_weight % 2 == 0) { $variables['classes_array'][] = 'row-limit-2-break'; }
    if ($region_ordinal_weight % 3 == 0) { $variables['classes_array'][] = 'row-limit-3-break'; }
    if ($region_ordinal_weight % 4 == 0) { $variables['classes_array'][] = 'row-limit-4-break'; }
  }
  
  // Adding zebra striping classes
  $variables['classes_array'][] = $variables['block_zebra'];
  
}
    
/**
 * Override of breadcrumb
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
*/
function gt_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  // Provide a navigational heading to give context for breadcrumb links to
  // screen-reader users. Make the heading invisible with .element-invisible.
  $output = '<li class="element-invisible">' . t('You are here: ') . '</li>';
  // first item is always a link back to the mothership, and it always appears
  $output .= '<li class="breadcrumb-item first"><a href="http://www.gatech.edu">GT Home</a></li>';
  if (!empty($breadcrumb)) {
    $crumb_total = count($breadcrumb);
    $crumb_count = 0;
    foreach ($breadcrumb AS $crumb) {
      $crumb_count++;
      $output .= '<li class="breadcrumb-item';
      if ($crumb_count == $crumb_total) {
        $output .= ' last';
      }
      $output .= '">' . $crumb . '</li>';
    }
  }
  return $output;
}

/**
 * Override of menu link formatting
 *
 * @param $variables
 *   An associative array passed to the theme template.
*/
function gt_menu_link(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';
  
  // adding a unique class for each link
  $element['#attributes']['class'][] = $element['#original_link']['menu_name'] . '-link-' . $element['#original_link']['mlid'];
  
  if ($element['#below']) {
   $sub_menu = drupal_render($element['#below']);
  }
  // overriding default html option of l function since titles will have span tags
  $element['#localized_options']['html'] = TRUE;
  // adding a span tag around link title
  $output = l('<span>' . $element['#title'] . '</span>', $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}