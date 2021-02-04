//Validate rss
$('.test-rss-uri').on('click', function() {
    const formGroup = $(this).closest('.form-group');
    const input = formGroup.find('input');
    const hint = formGroup.find('.input-hint');
    hint.html('');
    $.ajax({
        type: 'POST',
        url: $(this).data('url'),
        data: {rss: input.data('domain') + input.val()},
    }).done((res) => {
        const message = res.message;
        let icon;
        if (res.success) {
            icon = '<i class="material-icons">done</i>';
            hint.removeClass('text-warning').addClass('text-success');
        } else {
            icon = '<i class="material-icons">report_problem</i>';
            hint.removeClass('text-warning').addClass('text-warning');
        }
        hint.removeClass('hidden').html(icon + message);
    });
});

//RSS Update
let rssRefreshing = false;
$('#rss-update').on('click', function (e) {
    if (rssRefreshing) return false;
    e.preventDefault();
    rssRefreshing = true;
    const link = $(this);
    $.ajax({
        type: 'POST',
        url: link.attr('href'),
        beforeSend: function () {
            link.addClass('is-loading');
        }
    }).done(function(res) {
        link.removeClass('is-loading');
        rssRefreshing = false;
        if (res.success) {
            window.location.reload();
        } else {
            $('.update-error').html(res.error);
        }
    });
});