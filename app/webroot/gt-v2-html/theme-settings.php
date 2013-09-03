<?php
/**
 * Implements hook_form_FORM_ID_alter().
 *
 * @param $form
 *   The form.
 * @param $form_state
 *   The form state.
 */
function gt_form_system_theme_settings_alter(&$form, &$form_state) {
  
  /**
   * Hiding basic theme settings options via CSS (logo, main menu, etc.)
   * (We would just keep these out via the theme info file,
   * but there is currently a bug w/ the logo and favicon upload fields,
   * so it's easier to just hide them with CSS.)
  */
  
  // Hiding basic settings (main menu, secondary logo, etc.)
  $form['theme_settings']['#attributes'] = array('class' => array('element-hidden'));
    
  // Hiding favicon options
  $form['favicon']['#attributes'] = array('class' => array('element-hidden'));
  
  // Hiding default logo options
  $form['logo']['#attributes'] = array('class' => array('element-hidden'));
    
  /**
   * GT Logo Options
  */
  $form['gt_logo'] = array(
    '#type'          => 'fieldset',
    '#title'         => t('Georgia Tech Logo Options'),
  );
  $form['gt_logo']['gt_logo_default'] = array(
    '#type'         => 'checkbox',
    '#title'        => t('Use a Georgia Tech logo listed below.'),
    '#default_value' => theme_get_setting('gt_logo_default', 'gt'),
    '#description'  => t('Check this option to use one of the Georgia Tech logos listed below. Uncheck this box to upload a different Georgia Tech logo.'),
  );
  // Default logo options
  $form['gt_logo']['gt_logo_settings']['#type'] = 'container';
  $form['gt_logo']['gt_logo_settings']['#states'] = array('invisible' => array('input[name="gt_logo_default"]' => array('checked' => FALSE)));
  $form['gt_logo']['gt_logo_settings']['gt_logo_type']['#type'] = 'radios';
  $form['gt_logo']['gt_logo_settings']['gt_logo_type']['#title'] = t('Georgia Tech Logo Option');
  $form['gt_logo']['gt_logo_settings']['gt_logo_type']['#description'] = t('Select a Georgia Tech logo to use on this site.');
  $form['gt_logo']['gt_logo_settings']['gt_logo_type']['#default_value'] = theme_get_setting('gt_logo_type', 'gt');
  $form['gt_logo']['gt_logo_settings']['gt_logo_type']['#options'] = array(
                                                  0 => t('Primary Georgia Tech Logo (site default)'),
                                                  1 => t('Georgia Tech - College of Architecture'),
                                                  2 => t('Georgia Tech - College of Computing'), 
                                                  3 => t('Georgia Tech - College of Engineering'),
                                                  4 => t('Georgia Tech - College of Sciences'),
                                                  5 => t('Georgia Tech - Ivan Allen College of Liberal Arts'),
                                                  6 => t('Georgia Tech - Scheller College of Business'),
                                                );
  // Logo file upload
  $form['gt_logo']['gt_logo_upload']['#type'] = 'container';
  $form['gt_logo']['gt_logo_upload']['#states'] = array('invisible' => array('input[name="gt_logo_default"]' => array('checked' => TRUE)));
  $form['gt_logo']['gt_logo_upload']['gt_logo_upload_file'] = array(
    '#type'     => 'managed_file',
    '#title'    => t('Georgia Tech Logo Upload'),
    '#description'   => t('Use this field to upload a Georgia Tech. Logo files must be in .png format, and between 188 - 700 pixels wide by 90 pixels tall. <em>Uploaded images that are larger than these dimensions will be resized proportionally and may produce undesirable results.</em> <a href="http://comm.gatech.edu">Contact Institute Communications</a> for support with generating official Georgia Tech logos.'),
    '#required' => FALSE,
    '#upload_location' => file_default_scheme() . '://gt_theme_files',
    '#default_value' => theme_get_setting('gt_logo_upload_file'), 
    '#upload_validators' => array(
      'file_validate_extensions' => array('png'),
      'file_validate_image_resolution' => array('700x90', '188x90'),
    ),
  );
  
  /**
   * Site title options
  */
  $form['site_title'] = array(
    '#type'          => 'fieldset',
    '#title'         => t('Site Title Settings'),
  );

  $form['site_title']['site_title_line_1'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Site Title - Line One'),
    '#default_value' => theme_get_setting('site_title_line_1'),
    '#description'   => t('Enter the site title. Site titles are aligned to the right. If you want the title to appear over two lines, enter text into the Site Title - Line Two field.'),
  );

  $form['site_title']['site_title_line_2'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Site Title - Line Two'),
    '#default_value' => theme_get_setting('site_title_line_2'),
    '#description'   => t('If you want the site title to appear over two lines, enter the second line here.'),
  );
  
  /**
   * Site search options
  */
  $form['search_options'] = array(
    '#type'         => 'fieldset',
    '#title'        => t('Search Options'),
    '#description'  => t('Select an option for site search. "Search This Site" will use the default Drupal search for this site. "Search all of Georgia Tech" will use the campus Google&trade; Appliance index, and display search results on the search.gatech.edu site. "User Choice" will provide radio buttons for the user to select one of the search options, which defaults to "Search All of Georgia Tech." If you select "Search This Site" make sure that you have permissions configured correctly so that anonymous users can search your site.'),
  );

  $form['search_options']['search_option']= array(
    '#type' => 'radios',
    '#title' => t('Site Search'),
    '#default_value' => theme_get_setting('search_option', 'gt'),
    '#options' => array(
      0 => t('Search This Site'),
      1 => t('Search All of Georgia Tech'), 
      2 => t('User Choice')
    ),
  );
  
  /**
   * Map image and street address
  */
  $form['map_settings'] = array(
    '#type'         => 'fieldset',
    '#title'        => t('Site Footer Map and Street Address Settings'),
    '#description'  => t('Use these options upload a custom map image and provide a custom link for that image, and add a custom street address.'),
  );
  $form['map_settings']['map_default'] = array(
    '#type'         => 'checkbox',
    '#title'        => t('Use the default Georgia Tech campus map image in the footer.'),
    '#default_value' => theme_get_setting('map_default', 'gt'),
    '#description'  => t('Check this option to use the default Georgia Tech campus map image in the footer of your site. Uncheck this box to upload a custom image.'),
  );
  $form['map_settings']['map_image']['#type'] = 'container';
  $form['map_settings']['map_image']['#states'] = array('invisible' => array('input[name="map_default"]' => array('checked' => TRUE)));
  $form['map_settings']['map_image']['map_image_upload'] = array(
    '#type'     => 'managed_file',
    '#title'    => t('Map Image Upload'),
    '#description'   => t('Use this field to upload a custom map image. Images must be in .png, .gif, or .jpg format, and 370 pixels wide by 200 pixels tall. <em>Uploaded images that are larger than these dimensions will be scaled down and may produce undesirable results.</em>'),
    '#required' => FALSE,
    '#upload_location' => file_default_scheme() . '://gt_theme_files',
    '#default_value' => theme_get_setting('map_image_upload', 'gt'), 
    '#upload_validators' => array(
      'file_validate_extensions' => array('gif png jpg jpeg'),
      'file_validate_image_resolution' => array('370x200', '370x200'),
    ),
  );
  $form['map_settings']['map_image']['map_image_link'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Map Image Link'),
    '#default_value' => theme_get_setting('map_image_link', 'gt'),
    '#description'   => t('Provide a full URL (i.e., http://www.gatech.edu) for your custom map image.'),
  );
  $form['map_settings']['street_address'] = array(
    '#type'   => 'textarea',
    '#title'   => t('Custom Street Address '),
    '#description'  => t('Provide a custom street address which will appear with the campus map image in the footer.'),
    '#default_value' => theme_get_setting('street_address', 'gt'), 
  );
  
  /**
   * Footer login link options
  */
  $form['footer_login_link'] = array(
    '#type'          => 'fieldset',
    '#title'         => t('Footer Login Link'),
  );
  
  $form['footer_login_link']['login_link_option'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Check this checkbox if you would like to have a login link in the footer.'),
    '#default_value' => theme_get_setting('login_link_option', 'gt'),
    '#description'   => t('This will add a login link, plus a GT Login service logout link, and standard logout link for users once they\'ve logged in to the site.'),
  ); 
    
}