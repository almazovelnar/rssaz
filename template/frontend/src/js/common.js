// Styles
import "../css/fontello.css";
import "../scss/style.scss";
// JS
import "bootstrap/js/dist/dropdown";

$(document).ready(function() {
    // Toggle menu
    const header = $("header");
    const menuToggler = $(".menu-toggler");

    menuToggler.click(() => {
        header.toggleClass("active");
    });

    // Toggle search
    const filtersSearch = $(".mobile-filters .search-field");
    const openSearch = $(".open-search");
    const closeSearch = $(".close-search");

    openSearch.click(() => {
        filtersSearch.addClass("active");
    });

    closeSearch.click(() => {
        filtersSearch.removeClass("active");
    });

    // Toggle go to top icon visibility
    let offset = 300;
    let toTop = $('.to-top');
    let beforeScroll = $(window).scrollTop();

    toTop.on('click', function (event) {
        event.preventDefault();
        $('body, html').animate({scrollTop: 0}, 600);
    });

    $(window).scroll(function () {
        let scrollTop = $(this).scrollTop();

        if (scrollTop > offset) {
            if (scrollTop < beforeScroll && !toTop.hasClass('active')) {
                toTop.addClass('active');
            }
        } else if (toTop.hasClass('active')) {
            toTop.removeClass('active');
        }
        
        beforeScroll = scrollTop;
    });

    // Lazy loading images
    const lazy = $(".lazy img");

    if (lazy.length > 0) {
        lazy.lazy();
    }

});
