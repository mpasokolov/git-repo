function buildReviewsList() {
  $.get('http://localhost:3000/review-list', {}, function(reviews) {
    var $reviewList = $('#review-list');
    $reviewList.empty();
    document.getElementById('review-list').textContent = 'Последние отзывы: ';
    reviews.forEach(function(review) {
      var $reviewBlock = $('<div/>', {
        "data-id": review.id,
         class: "review-block",
         id: "review-block"
      });
      var $text = $('<blockquote/>', {
        text: review.text
      });
      var $delButton = $('<button/>', {
        text: "Delete review",
        class: "delete",
        "data-id": review.id
      });
      $reviewBlock.append($text);
      $reviewBlock.append($delButton);
      $('#review-list').append($reviewBlock);
    });
    var $addButton = $('<button/>', {
      id: "add-review",
      class: "add-review",
      text: "Добавить отзыв"
    });
    $addButton.css('margin-top', '10px');
    $reviewList.append($addButton);
  }, 'json')
  .fail(function () {
    alert('Ошибка, отзывы не загружены!');
  });
}

function buildModerateList() {
  $.get('http://localhost:3000/moderate-list', {}, function(reviews) {
    $('#review-check-list').empty();
    document.getElementById('review-check-list').textContent = 'Отзывы на модерации: ';
    reviews.forEach(function (review) {
      var $reviewBlock = $('<div/>', {
        "data-id": review.id,
        class: "review-check-block",
        id: "review-check-block"
      });
      var $text = $('<blockquote/>', {
        text: review.text
      });
      var $delButton = $('<button/>', {
        text: "Delete review",
        class: "moderate-delete",
        "data-id": review.id
      });
      var $approveButton = $('<button/>', {
        text: "Approve review",
        class: "moderate-approve",
        "data-id": review.id
      });
      $reviewBlock.append($text);
      $reviewBlock.append($delButton);
      $reviewBlock.append($approveButton);
      $('#review-check-list').append($reviewBlock);
    })
  })
    .fail(function () {
      alert('Ошибка при загрузке списка отзывов на модерацию!');
    });
}


(function($) {
  $(function() {
    buildReviewsList();
    buildModerateList();

    var $reviewList = $('#review-list');
    var $checkList = $('#review-check-list');
    $reviewList.on('click', '.delete', function (event) {
      $.ajax({
        url: 'http://localhost:3000/review-list/' + +$(this).attr('data-id'),
        type: 'DELETE',
        success: function () {
          buildReviewsList();
        },
        error: function () {
          alert('Ошибка при удалении отзыва!');
        }
      });
      event.preventDefault();
    });

    $checkList.on('click', '.moderate-delete', function (event) {
      $.ajax({
        url: 'http://localhost:3000/moderate-list/' + +$(this).attr('data-id'),
        type: 'DELETE',
        success: function () {
          buildModerateList();
        },
        error: function () {
          alert('Ошибка при удалении отзыва, находящегося на модерации');
        }
      });
      event.preventDefault();
    });

    $checkList.on('click', '.moderate-approve', function (event) {
      $.get('http://localhost:3000/moderate-list/' + +$(this).attr('data-id'), {}, function(review) {
        $.post('http://localhost:3000/review-list', {text: review.text} , function() {
          buildReviewsList();
        }, 'json')
          .fail(function () {
            alert('Ошибка при одобрении отзыва!')
          })
      });
      $.ajax({
        url: 'http://localhost:3000/moderate-list/' + +$(this).attr('data-id'),
        type: 'DELETE',
        success: function () {
          buildModerateList();
        },
        error: function () {
          alert('Ошибка при удалении отзыва, находящегося на модерации(после его одобрения)')
        }
      });
      event.preventDefault();
    });

    $reviewList.on('click', '.add-review', function (event) {
      event.preventDefault();
      $('#add-review-error').css('display', 'none');
      $('#add-review-done').css('display', 'none');
      $('#add-review-form').css('display', 'flex');
    });

    $('#add-review-send').on('click', function (event) {
      event.preventDefault();
      var $addReviewError =$('#add-review-error');
      $addReviewError.css('display', 'none');

      var text = $('#review-text')[0].value;

      if (text.length <= 10) {
        $addReviewError.css('display', 'block');
        return;
      }
      var review = {
        text: text
      };

      $.post('http://localhost:3000/moderate-list', review, function() {
        buildModerateList();
      }, 'json')
        .fail(function () {
          alert('Ошибка при добавлении нового отзыва!');
        })
        .done(function () {
          var $form = $('#add-review-form');
          $form.css('display', 'none');
          $form[0].reset();
          $('#add-review-done').css('display', 'block');
        });
    })
  });
})(jQuery);