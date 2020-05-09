function copyToClipboard(element) {
    const $temp = jQuery("<input>");
    const $text = jQuery(element).text();

    jQuery("body").append($temp);
    $temp.val($text).select();
    document.execCommand("copy");
    $temp.remove();

    jQuery(element).next('.copied-shortcode').show();

    setTimeout(function () {
        jQuery(element).next('.copied-shortcode').fadeOut();
    }, 2000)
}