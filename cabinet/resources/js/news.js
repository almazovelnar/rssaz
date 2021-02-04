$('a.prioritize-news').on('click', function (e) {
    e.preventDefault();
    if (!confirm('Are you sure to prioritize this news ?'))
        return false;

    let link = $(this);
    $.get(link.attr('href'), function (resp) {
        if (resp.status) {
            link.fadeOut(300, () => {
                alert(resp.message);
            });
        }
    });
})