// Search
$('input.search').on('keypress', function (e) {
    if (e.which === 13) {
        var value = $.trim($(this).val());
        return (value.length < 3)
            ? false
            : window.location.href = '/search/' + value;
    }
});

// Filters
$('.period-filter').on('change', function (e) {
    e.preventDefault();
    var filterForm = $(this).closest('form');
    var period = $(".mobile-filters:visible").length ? $(".mobile-filters .period-filter").val() : $("input[name='period']:checked").val();
    $.ajax({
        url: filterForm.data('url'),
        type: "GET",
        data: {period: period},
    })
    .done(function() {
        location.reload();
    });
});
