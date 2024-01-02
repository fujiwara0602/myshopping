$(function() {

  //下から表示させる要素を指定
  var $pagetop = $('#pagetop');
  //一定量スクロールするまで非表示
  $pagetop.hide();

  $(window).on( 'scroll', function () {
    //スクロール位置を取得
    if ( $(this).scrollTop() < 100 ) {
      //要素をスライド非表示
      $pagetop.slideUp('slow');
    } else {
      //要素をスライド表示
      $pagetop.slideDown('slow');
    }
  });
});