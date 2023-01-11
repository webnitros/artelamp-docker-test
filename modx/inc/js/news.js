function openMoreNews() {
   $('.newspage_card_wrap').slice(0, 6).addClass('open');
   $('.js_btn_more_news').click(function (e) {
      e.preventDefault()
      $('.newspage_card_wrap:nth-child(n+7)').toggleClass('open');
      $(this).find('span').toggleClass('active')
   });
}
function readMore() {
   $('.unit').slice(0, 10).show();
   $('.js-open-otziv').click(function () {
      $('.posters .units .unit:hidden').slice(0, 4).show();
   });
}

$(document).ready(function () {
   openMoreNews()
})