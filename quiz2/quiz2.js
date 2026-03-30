// Quiz 2
// Put your javascript here in a document.ready function

alert('The page is about to load.');

$(document).ready(function () {
  var DEFAULT_TITLE = 'ITWS 1100 - Quiz 2';
  var ALT_TITLE = 'Nikhil Midda \u2013 Quiz 2';
  var $h1 = $('h1').first();

  document.title = DEFAULT_TITLE;
  $h1.text(DEFAULT_TITLE);

  $('#goBtn').on('click', function () {
    if (document.title === DEFAULT_TITLE) {
      document.title = ALT_TITLE;
      $h1.text(ALT_TITLE);
    } else {
      document.title = DEFAULT_TITLE;
      $h1.text(DEFAULT_TITLE);
    }
  });

  $('#lastName').hover(
    function () {
      $(this).addClass('makeItPurple');
    },
    function () {
      $(this).removeClass('makeItPurple');
    }
  );
});
