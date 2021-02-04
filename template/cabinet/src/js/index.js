// Styles
import "../css/font-awesome.css";
import "../css/colorpicker.css";
import "../scss/style.scss";
// Javascript Libs
import "bootstrap/js/dist/dropdown";
import "bootstrap/js/dist/collapse";
import "bootstrap/js/dist/tab";

import "./generated";

$(document).ready(() => {
    $('.toggle-rss').on('click', function () {
        $('#' + $(this).data('toggle')).removeClass('hidden');
        $(this).remove();
    });

    // Show / hide menu
    const mainHeader = $(".main-header");
    const menuToggler = $(".menu-toggler");
    const closeMenu = $(".close-menu");
    const sideMenu = $(".side-menu-wrapper");

    menuToggler.click(() => {
        mainHeader.toggleClass("active");
        sideMenu.addClass("active");
    });

    closeMenu.click(() => {
        sideMenu.removeClass("active");
    });

    // Show / hide search
    const filtersSearch = $(".mobile-filters .search-field");
    const openSearch = $(".open-search");
    const closeSearch = $(".close-search");

    openSearch.click(() => {
        filtersSearch.addClass("active");
    });

    closeSearch.click(() => {
        filtersSearch.removeClass("active");
    });

    // Copy to clipboard
    const copyToClipboardBtn = $(".copy-to-clipboard");

    copyToClipboardBtn.click(() => {
        copyToClipboard();
    });

    function copyToClipboard() {
        const generatedCode = $(".generated-code textarea");

        generatedCode.select();
        document.execCommand("copy");
    }

    // Edit profile
    const uploadImageField = $('.upload-avatar');
    const imagePreview = $('.image-preview');

    function uploadPhotoPreview(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
    
            reader.onload = (e) => {
                imagePreview
                    .css("background-image", `url(${e.target.result})`)
                    .addClass("image-loaded");
            };
    
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('.trigger-image-upload').click(function () {
        uploadImageField.trigger('click');
    });

    uploadImageField.change(function() {
        uploadPhotoPreview(this);
    });

    // Toggle go to top icon visibility
    let offset = 300,
        // before scroll value
        beforeScroll = $(window).scrollTop(),
        toTop = $('.to-top');

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

});