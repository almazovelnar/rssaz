var uploadAvatarField = $('.upload-avatar');
var imagePreview = $('.image-preview');
var deleteButton = $('.trigger-photo-delete');
var deleteButtonContainer = deleteButton.closest('.btn-group');

function uploadPhotoPreview(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = (e) => {
            imagePreview
                .css("background-image", `url(${e.target.result})`)
                .addClass("image-loaded");
        };

        reader.readAsDataURL(input.files[0]);
        deleteButtonContainer.removeClass('hidden');
    }
}

function handlePhotoDelete() {
    uploadAvatarField.val("");

    imagePreview
        .css('background-image', 'none')
        .removeClass('image-loaded');

    deleteButtonContainer.addClass('hidden')
}

$('.trigger-photo-upload').click(function () {
    uploadAvatarField.trigger('click');
});

deleteButton.click(function () {
    handlePhotoDelete(this)
});

uploadAvatarField.change(function() {
    uploadPhotoPreview(this);
});

$('.remove_button').on('click', function(e) {
    e.preventDefault();
    var reason = prompt('Xəbərin silinmə səbəbini daxil edin');
    if  (reason && reason.length > 3) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('href'),
            data: {reason: reason},
            success: function(data)
            {
                if (data.status == true) location.reload();
            },
            error: function(error)
            {
                console.log(error)
            }
        });
    }
});

$('[data-toggle="tooltip"]').tooltip()


$(document).ready(function(){
    $('.nav-icon4').click(function(){
        $(this).toggleClass('open');
    });

    $('.fold-show').on('click', function () {
        var navIsFolded = localStorage.getItem("navFolded");

        if (navIsFolded == null || navIsFolded === "") {
            localStorage.setItem("navFolded", "true");
        } else {
            localStorage.removeItem("navFolded");
        }
    });

});

