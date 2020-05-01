(function($){
    $('.fg-guitar-menu-tabs li').on('mouseenter', function (e) {
        console.log(e);
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
        if ($(this).hasClass('fg-usd')) {
            currency_toggle($('.fg-usd'));
            Cookies.set('fg-selected-currency', 'fg-usd');
        }
        if ($(this).hasClass('fg-eur')) {
            currency_toggle($('.fg-eur'));
            Cookies.set('fg-selected-currency', 'fg-eur');
        }
    });

    function currency_toggle(el) {
        $('.fg-currency-button').removeClass('fg-selected');
        el.addClass('fg-selected');

        if (el.hasClass('fg-usd')) {
            $('.fg-original-price').removeClass('uk-hidden');
            $('.fg-converted-price').addClass('uk-hidden');
        }
        if (el.hasClass('fg-eur')) {
            $('.fg-original-price').addClass('uk-hidden');
            $('.fg-converted-price').removeClass('uk-hidden');
        }
    }

})(jQuery)