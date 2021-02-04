// Javascript Libs
import "moment/locale/az";
import "bootstrap-daterangepicker/daterangepicker";
import "select2/dist/js/select2";
import "./libs/colorpicker";

$(document).ready(() => {
    // Select 2 init
    $("select.tags").select2({
        tags: true
    });

    // Daterangepicker init
    $('input[name="dates"]').daterangepicker();

    // Generate code page
    const copyToClipboardBtn = $(".copy-to-clipboard");

    copyToClipboardBtn.click(function() {
        const generatedCode = $(this).closest(".generated-code").find("textarea")

        generatedCode.select();
        document.execCommand("copy");
    })
});