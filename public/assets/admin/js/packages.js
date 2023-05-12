(function ($) {
    "use strict";
    $('input[name="is_trial"]').on('change',function () {
        if ($(this).val() == 1) {
            $('#trial_day').show();
        } else {
            $('#trial_day').hide();
        }
        $('#trial_days').val(null);
    });
})(jQuery); 


"use strict";
$(document).ready(function () {
    $(".selectgroup-input").on('click', function () {
        var val = $(this).val()
        if (val == 'vCard') {
            if ($(this).is(":checked")) {
                $(".v-card-box").show();
            } else {
                $(".v-card-box").hide();
            }
        }
    })
});
