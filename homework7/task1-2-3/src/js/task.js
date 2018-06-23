(function($) {
  $(function () {
    $('#slick').slick({
      variableWidth: true,
      adaptiveHeight: true,
      autoplay: true,
      autoplaySpeed: 2000,
      dots: true,
      arrows: false,
      touchThreshold: 10,
      swipeToSlide: true,
      centerMode: true,
      slidesToShow: 4
    });
  });
})(jQuery);
