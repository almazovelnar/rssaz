function isAppBrowser () {
    var ua = navigator.userAgent || navigator.vendor || window.opera;
    return (ua.indexOf("FBAN") > -1) || (ua.indexOf("FBAV") > -1) || (ua.indexOf("Instagram") > -1);
}

if (isAppBrowser()) {
    $('a').removeAttr('target')
} else {
    $('.read-post').on('click', function() {
        setTimeout(function() {
            window.location.href = $('.redirect-page').data('next');
        }, 200)
    });
}