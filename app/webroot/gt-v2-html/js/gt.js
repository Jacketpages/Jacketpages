/**
 * @file
 * js base functions
 *
 * Includes js behaviors for various toggles and switches
 *
 */

(function($) {
Drupal.behaviors.gt = {
  attach: function (context, settings) {
    
    // remove colorbox functionality for screens less than 600px wide
    $(function() {
      if ($(window).width() < '600') {
       $('a.colorbox').removeClass('cboxElement');
      }
    });
    
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
    $("#main-menu-wrapper ul li a").live('touchstart click', function(event) {
      // if toggle button for menu is visible then we're in a mobile viewport, so add accordion behaviors
      if($('#primary-menus-toggle').is(':visible')){
        if($(this).hasClass('been-clicked') || !$(this).parent('li').children('ul').length > 0) {
          // fix for IOS requiring triple-click to navigate to expanded menu item
		      window.location.href = $(this).attr('href');
          return true;
        } else {
          event.preventDefault();
          $(this).addClass('been-clicked');
          $(this).parent('li').children('ul').addClass('show');
          return false;
        }
      // else in desktop viewports
      } else {
        // if mega menus are in play
        if($(this).hasClass('menu-minipanel')) {
          // get index number of parent li
          var c = $(this).parent('li').index();
          // close all open mega menus
          $("#main-menu-wrapper ul li a").trigger("mouseout");
          // remove been clicked class from all other items
          $("#main-menu-wrapper ul li").each(function() {
            if ($(this).children('a').hasClass('been-clicked') && $(this).index() != c) {
              $(this).children('a').removeClass('been-clicked');
            }
          });
          // go to URL if been-clicked class exists
          if ($(this).hasClass('been-clicked')) {
            // fix for IOS requiring triple-click to navigate to expanded menu item
		        $(this).trigger("mouseout");
		        $(this).removeClass('been-clicked');
		        return false;
		        //window.location.href = $(this).attr('href');
            //return true;
          } else {
            // else trigger mouseover, add been-clicked class
            $(this).trigger("mouseover");
            $(this).addClass('been-clicked');
            return false;
          }
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