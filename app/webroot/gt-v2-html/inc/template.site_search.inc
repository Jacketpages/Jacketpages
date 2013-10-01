<?php
/**
 * @file
 * Contains markup variables for site search options.
 */

$search_gt = '<div id="search-gt">';
$search_gt .= '<form action="http://search.gatech.edu/search" method="get">';
$search_gt .= '<input  value="" name="q" id="q" class="form-text" type="text" />';
$search_gt .= '<input name="site" value="default_collection" type="hidden" />';
$search_gt .= '<input name="client" value="default_frontend" type="hidden" />';
$search_gt .= '<input name="output" value="xml_no_dtd" type="hidden" />';
$search_gt .= '<input name="proxystylesheet" value="default_frontend" type="hidden" />';
$search_gt .= '<input name="proxyreload" value="1" type="hidden" />';
$search_gt .= '</form>';
$search_gt .= '</div>';

if (module_exists('search')) {
  $search_form = drupal_get_form('search_form');
  $search_page = drupal_render($search_form);
  $search_local = '<div id="search-local">' . $search_page . '</div>';
} else {
  $search_local = '';
}
	
$search_user_choice = '<div class="clearfix" id="search-user-choice">';
$search_user_choice .= '<a href="' . base_path() . 'search" id="search-local-selection" class="checked">This Site</a>';
$search_user_choice .= '<a href="http://search.gatech.edu" id="search-gt-selection">All of Georgia Tech</a>';
$search_user_choice .= $search_gt;
$search_user_choice .= $search_local;
$search_user_choice .= '</div>';

