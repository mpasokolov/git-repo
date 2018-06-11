$(function () {
  $('.button').click(function () {

    //Позиция элемента
    var position = $('.button').index(this);

    //Удаляем все классы active
    $('.active').removeClass('active');

    //Добавляем класс active к нужным элементам
    $(this).addClass('active');
    $('.text').eq(position).addClass('active');
  })
});
