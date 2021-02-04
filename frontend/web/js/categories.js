var loadContainer = $(".load-container");

var callingAjax = false,
    limitReached = false,
    scrollSum = null;

$(window).on("scroll", function() {
    scrollSum = Math.ceil($(window).scrollTop() + $(window).innerHeight());

    if (scrollSum >= ($(document).height() - 300) && !callingAjax) {
        callingAjax = true;
        var lastBlock = $('.othernews-block:last');

        if (limitReached) return false;

        $.ajax({
            url: loadContainer.data('url'),
            type: "GET",
            data: {date: lastBlock.data('timestamp'), category: lastBlock.data('category')},
        })
        .done(function(response) {
            loadContainer.append(response.html);

            if (response.hasOwnProperty('limitReached'))
                limitReached = response.limitReached;

            callingAjax = false;
        });
    }
});