import "select2/dist/css/select2.min.css";
import "bootstrap-daterangepicker/daterangepicker.css";

import "select2/dist/js/select2";
import "bootstrap-daterangepicker/daterangepicker";
import moment from "bootstrap-daterangepicker/moment.min.js";
import "moment/locale/az";
import "./libs/colorpicker";

$(document).ready(function(){
    // Select 2 init
    const domainsSelect = $("select.tags");
    if (domainsSelect.length > 0) {
        domainsSelect.select2({
            minimumInputLength: 3,
            ajax: {
                url: domainsSelect.data("url"),
                dataType: "json",
                data: function (params) {
                    return {q: params.term}
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: function (domain) {
                    return domain.text;
                },
                templateSelection: function (domain) {
                    return domain.text;
                }
            }
        });
    }

    // Daterangepicker init
    const dateRangInps = $('.range');
    dateRangInps.daterangepicker({
        locale: {
            format: 'YYYY-MM-DD',
            customRangeLabel: "Xüsusi tarix",
        },
        ranges: {
            'Bugün': [moment(), moment()],
            'Dünən': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Son 7 gün': [moment().subtract(6, 'days'), moment()],
            'Son 30 gün': [moment().subtract(29, 'days'), moment()]
         }
    });
    dateRangInps.on('apply.daterangepicker', function () {
        this.form.submit();
    });

    // Colorpicker init
    $(".color-picker").ColorPicker({
        onSubmit: (hsb, hex, rgb, el) => {
            $(el)
                .find(".form-control")
                .attr("value", `#${hex}`)
                .trigger("change");
            $(el)
                .find(".picker-area")
                .css("background", `#${hex}`);
            $(el).ColorPickerHide();
        }
    });
});