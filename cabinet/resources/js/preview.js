const codeGenerationForm = $('#code-generation-form');
const messageField = $('.ajax-info span');
const generatedCodeBlock = $('.generated-code');

$('select#code-type').on('change', function () {
   return location.href = '?code=' + $.trim($(this).val());
});

codeGenerationForm.on('beforeSubmit', function () {
    const yiiform = $(this);
    $.ajax({
        type: yiiform.attr('method'),
        url: yiiform.attr('action'),
        data: yiiform.serializeArray(),
    })
    .done(function(resp) {
        if (resp.status) {
            displayMessage(resp.message);
            generatedCodeBlock.find('textarea').text(resp.code);
            generatedCodeBlock.find('button').removeClass('hidden');
            $('ul.nav-tabs').removeClass('hidden');
            if (resp.hasOwnProperty('codeTypes')) renderCodeContent(resp.codeTypes);
        }
    });
    return false;
}).on('submit', function () {
    return false;
});

function renderCodeContent (codeTypes) {
    for (const type in codeTypes) {
        if (codeTypes.hasOwnProperty(type))
            $('#' + type + ' textarea').text(codeTypes[type].content);
    }
}

$('select#website').on('change', function () {
    let value = parseInt($(this).val());
    return window.location.href = '?id=' + value;
});

// ******************************************

// appending & removing blocks runtime
const blockInput = $('#blockcount');
const previewSlider = $('.custom-slider');
let minReached = false;
let maxReached = false;

blockInput.on('touchspin.on.startupspin', () => {
    //let blockCount = parseInt(blockInput.val());
    if (!maxReached) {
        $.ajax({
            type: 'GET',
            url: blockInput.data('url'),
            data: {}
        })
        .done((resp) => {
            previewSlider.find('.slider-track').append($(resp));
            window.customSlider.update();
            window.customSlider.slideToStart();
        });
    }
});

blockInput.on('touchspin.on.startdownspin', () => {
    if (!minReached) {
        previewSlider.find('.slide:last').remove();
        window.customSlider.update();
        window.customSlider.slideToStart();
    }
});

$("#direction").on("change", function() {
    const value = this.value;
    if (value === "vertical") {
        $(".horizontal-preview").removeClass("visible");
        $(".vertical-preview").addClass("visible");
    } else {
        $(".vertical-preview").removeClass("visible");
        $(".horizontal-preview").addClass("visible");
    }
});

$('#titlefontsize').on('change', function () {
    let fontSize = parseInt($(this).val());
    $('.slide-post__info span').css({"font-size": fontSize})
})

$('#titlestyle').on('change', function () {
    let fontStyle = $(this).val();
    $('.slide-post__info span').css({"font-weight": fontStyle})
});

$('#titlefont').on('change', function () {
    let font = $(this).val();
    $('.slide-post__info span').css({"font-family": font})
})

$('#blockwidth').on('change', function () {
    let value = parseInt($(this).val());
    previewSlider.find('.slide-post').css({
        "width": value,
        "min-width": value
    });
    window.customSlider.update();
})

blockInput.on('touchspin.on.max', () => {
    maxReached = true;
});

blockInput.on('touchspin.on.min', () => {
    minReached = true;
});

function displayMessage(message) {
    messageField.text(message);
    setTimeout( () => { messageField.text('') }, 5000);
}