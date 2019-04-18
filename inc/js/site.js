jQuery( document ).ready( function( $ ) {

  $(document).foundation();
  var w = $(window);
  if( w.width() > 640 ){
    var offset1, offset2;
    offset1 = w.scrollTop();
    var header = $('header.header');
    var logo = $('.home-logo');
    var kochanowski = $('.kochanowski');
    w.scroll(0);

    w.scroll( function() {
      var offset2 = $(this).scrollTop();
        header.css({
          'top' : (offset2*0.7)+'px',
          'opacity' : 1/(offset2*0.01)
        });
        logo.css({
          'transform' : 'translateX('+(offset2*0.2)+'px)'
        });
        kochanowski.css({
          'transform' : 'translateX(-'+(offset2*0.2)+'px)'
        });

    });
  }
});
