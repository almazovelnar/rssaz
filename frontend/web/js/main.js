var otherNewsList = $(".other-news-list");
var callingAjax = false,
    limitReached = false,
    scrollSum = null;

$(window).on("scroll", function() {
    scrollSum = Math.ceil($(window).scrollTop() + $(window).innerHeight());

    if (scrollSum >= ($(document).height() - 300) && !callingAjax) {
        callingAjax = true;
        var timestamp = $('.othernews-block:last').data('timestamp');

        if (limitReached) return false;

        $.ajax({
            url: otherNewsList.data('url'),
            type: "GET",
            data: {date: timestamp},
        })
        .done(function(response) {
            otherNewsList.append(response.html);

            if (response.hasOwnProperty('limitReached'))
                limitReached = response.limitReached;

             callingAjax = false;
        });
    }
});