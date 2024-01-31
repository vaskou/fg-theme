(function ($) {
    $('.fg-guitar-menu-tabs li').on('mouseenter', function (e) {
        if ($(this).hasClass('uk-active') == false) {
            let index = $(this).index();
            UIkit.tab('.fg-guitar-menu-tabs').show(index);
        }
    });

    $('.fg-category-menu-link').click(function (e) {
        let link = $(this).attr('href');
        location.href = link;
    });


    let currency_cookie = Cookies.get('fg-selected-currency');
    if (currency_cookie) {
        currency_toggle($('.' + currency_cookie));
    }

    $('.fg-currency-button').click(function () {
        if ($(this).hasClass('fg-original-currency-symbol')) {
            currency_toggle($('.fg-original-currency-symbol'));
            Cookies.set('fg-selected-currency', 'fg-original-currency-symbol');
        }
        if ($(this).hasClass('fg-converted-currency-symbol')) {
            currency_toggle($('.fg-converted-currency-symbol'));
            Cookies.set('fg-selected-currency', 'fg-converted-currency-symbol');
        }
    });

    function currency_toggle(el) {
        $('.fg-currency-button').removeClass('fg-selected');
        el.addClass('fg-selected');

        if (el.hasClass('fg-original-currency-symbol')) {
            $('.fg-original-price').removeClass('uk-hidden');
            $('.fg-converted-price').addClass('uk-hidden');
        }
        if (el.hasClass('fg-converted-currency-symbol')) {
            $('.fg-original-price').addClass('uk-hidden');
            $('.fg-converted-price').removeClass('uk-hidden');
        }
    }

    $('.navigation.pagination ul.page-numbers').addClass('uk-pagination uk-flex-center');
    $('.navigation.pagination .page-numbers.current').parent().addClass('uk-active');

    let $fg_auto_gallery = $('.fg-auto-gallery > div');

    $fg_auto_gallery.children('figure').each(function () {
        let caption = $(this).children('figcaption').text();
        $(this).children('a').attr('data-caption', caption);
    });

    $fg_auto_gallery.addClass('uk-grid uk-child-width-1-3@m');

    UIkit.grid($fg_auto_gallery, {masonry: true});
    UIkit.lightbox($fg_auto_gallery, {toggle: '.fg-auto-gallery > div > figure > a'});

    // $(body).on('wpcf7mailsent', '.wpcf7', function () {
    //     let $this = $(this);
    //
    //     setTimeout(function () {
    //         let elementID = $this.closest('.fg-available-guitar__modal').attr("id");
    //         UIkit.modal(`#${elementID}`).hide();
    //     }, 5000);
    // });

})(jQuery)

// Accessibility
var onloadCallback = function () {
    //Contact form 7 reCaptcha
    var textarea = document.getElementById("g-recaptcha-response-100000");
    if (textarea) {
        textarea.setAttribute("aria-hidden", "true");
        textarea.setAttribute("aria-label", "do not use");
        textarea.setAttribute("aria-readonly", "true");
    }
};