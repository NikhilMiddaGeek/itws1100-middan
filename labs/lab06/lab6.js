
$(document).ready(function () {
   'use strict';

   $('#labButton').click(function () {
      alert('You\'ve clicked the lab button');
   });

   
   $('h1').on('click', function () {
      $('.myName').text('Nikhil Midda').addClass('myNameActive');
   });

   
   const $textParagraphs = $('#showHideBlock p');
   $('#hideText').on('click', function (event) {
      event.preventDefault();
      $textParagraphs.stop(true, true).animate(
         { opacity: 0, marginLeft: '32px' },
         2000,
         function () {
            $(this).hide();
         }
      );
   });
   $('#showText').on('click', function (event) {
      event.preventDefault();
      $textParagraphs
         .stop(true, true)
         .show()
         .css({ opacity: 0, marginLeft: '-32px' })
         .animate({ opacity: 1, marginLeft: '0px' }, 3300);
   });

   $('#labList').on('click', 'li', function () {
      $(this).toggleClass('red');
   });

   let listItemCount = $('#labList li').length;
   $('#AddListItem').on('click', function () {
      listItemCount += 1;
      $('#labList').append($('<li>').text(`List item ${listItemCount}`));
   });


   $('#toggleText').on('click', function (event) {
      event.preventDefault();
      $textParagraphs.slideToggle(350);
   });

   
});
