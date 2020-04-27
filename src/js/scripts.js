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
})(jQuery)