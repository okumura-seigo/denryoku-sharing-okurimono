jQuery(function($){
  $(function(){
    // global nav
    var $body = $('body');
    var $bodyClass = 'is-global-nav__open';
    var $contents = $('.l-contents__wrap');
    var $open = false;
    var $contents_top = 0;
    var $contents_scroll = 0;
    $('.js-nav-open').on('click', function () {
      $open = true;
      $contents_top = $(window).scrollTop();
      $contents_scroll = $contents_top * (-1);
      $body.toggleClass($bodyClass);
      $contents.css('top', $contents_scroll + 'px');
    });
    $('.js-nav-close').on('click', function () {
      $body.removeClass($bodyClass);
      $contents.css('top', '');
      $(window).scrollTop($contents_top);
      $open = false;
    });
    $(window).on('resize',function(){
      if ($open == true) {
        $body.removeClass($bodyClass);
        $contents.css('top', '');
        $(window).scrollTop($contents_top);
        $open = false;
      }
    });
  });
  $(function(){
    $('.js-toggle').on('click', function() {
      $(this).toggleClass('is-active');
    });
  });
});
