$(document).ready(function () {
    var header = $("header"); // ヘッダーのセレクタに適切なものを使用する
    var headerOffset = header.offset().top;

    function headerFixed() {
        var scrollTop = $(window).scrollTop();

        if (scrollTop > headerOffset) {
            header.css({
                "position": "fixed",
                "top": "0",
                "width": "100%" // スクロール時も幅を100%に保つ
            });
        } else {
            header.css({
                "position": "relative",
                "top": "auto",
                "width": "100%" // スクロール時も幅を100%に保つ
            });
        }
    }

    // ページロード時に実行
    headerFixed();

    // スクロールイベント時に実行
    $(window).scroll(headerFixed);
});
