/**
 * @file
 * js base functions
 *
 * Includes js behaviors for various toggles and switches
 *
 */

$(function() {
    
  // Slide out menu
  $('#primary-menus-toggle, #primary-menus-close').click(function(){
      $('#primary-menus-off-canvas').toggleClass('reveal');
      return false;
  });
  
  // Site search toggle
  $('#site-search-container-switch').click(function(){
    $('#site-search-container').toggle();
    return false;
  });
  
  // Superfooter links toggle
  $('.superfooter-resource-links .title').click(function(){
      if($(this).next().is('ul')){
          $(this).next().toggle();
          if ($(this).hasClass('open')){
            $(this).removeClass('open').addClass('closed');
          } else {
            $(this).removeClass('closed').addClass('open');
          }
          return false;
      }
      return true;
  });
  
});
