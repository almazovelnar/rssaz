var otherNewsList = $(".other-news-list");
var callingAjax = false,
    limitReached = false,
    scrollSum = null,
    page = 1;

var url = window.location.pathname;

// Search
$('input.search').on('keypress', function (e) {
    if (e.which === 13) {
        var value = $.trim($(this).val());
        return (value.length < 3)
            ? false
            : window.location.href = '/search/' + value;
    }
});

var firstNewsTimestamp = $('.newsBlock:first').data('timestamp');

$(window).on("scroll", function() {
    scrollSum = Math.ceil($(window).scrollTop() + $(window).innerHeight());

    if (scrollSum >= ($(document).height() - 300) && !callingAjax) {
        callingAjax = true;
        if (limitReached) return false;
        page++;
        if (!url.match(/page\/\d+/)) {
            url += "/page/" + page;
        } else {
            url = url.replace(/page\/(\d+)/, "page/" + page);
        }
        $.get(url, {date: firstNewsTimestamp}, function (resp) {
            otherNewsList.append(resp.html);
            if (resp.hasOwnProperty('limitReached')) limitReached = resp.limitReached;

            callingAjax = false;
        });
    }
});