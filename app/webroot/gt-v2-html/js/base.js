/**
 * @file
 * js base functions
 *
 * Includes js behaviors for various toggles and switches
 *
 */

(function($) {
Drupal.behaviors.myBehavior = {
  attach: function (context, settings) {

    //code starts
    
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
    
    // Site search local/gt toggle
    $('#search-local-selection').click(function(){
      $(this).addClass('checked');
      $('#search-local').show();
      $('#search-gt').hide();
      if ($('#search-gt-selection').hasClass('checked')){
        $('#search-gt-selection').removeClass('checked');
      }
      return false;
    });
    $('#search-gt-selection').click(function(){ 
      $(this).addClass('checked');
      $('#search-local').hide();
      $('#search-gt').show();
      if ($('#search-local-selection').hasClass('checked')){
        $('#search-local-selection').removeClass('checked');
      }
      return false;
    });
    	
    // Main menu functionality
    $("#main-menu-wrapper ul li a").live('touchstart click', function(event){
      // if toggle button for menu is visible then we're in a mobile viewport, so add accordion behaviors
      if($('#primary-menus-toggle').is(':visible')){
        if($(this).hasClass('been-clicked') || !$(this).parent('li').children('ul').length > 0){
          return true;
        } else {
          event.preventDefault();
          $(this).addClass('been-clicked');
          $(this).parent('li').children('ul').slideToggle();
          return false;
        }
      // else in desktop viewports
      } else {
        // if mega menus are in play
        if($(this).hasClass('menu-minipanel')) {
          event.preventDefault();
          // Do the magic
          $("#main-menu-wrapper > ul > li > a").trigger("mouseout");
          $(this).trigger("mouseover");
          $(this).addClass('been-clicked');
          return false;
        } else {
          // in standard drop-down only make second click necessary for top level items
          if(!$(this).hasClass('been-clicked') && $(this).parent('li').parent('ul').index() == 0) {
            if ($(this).parent('li').children('ul').length > 0) {
              $(this).addClass('been-clicked');
              return false;
            } else {
              return true;
            }
          } else {
            return true;
          }
        }
      }			
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
    
    //code ends

  }
};
})(jQuery);