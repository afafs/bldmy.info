"use strict";
(function ($) {
  var app = new Vue({
    el: '#app',
    data: {
      type: 1,
      counter: 0,
      placeholdershow: true,
      fileExtensions: false,
    },
    methods: {
      typeChange() {
        if (this.type == 3 || this.type == 5) {
          if (this.type == 5) {
            this.fileExtensions = true;
          }
          this.placeholdershow = false;
        } else {
          this.fileExtensions = false;
          this.placeholdershow = true;
        }
        if (this.type == 2 || this.type == 3) {
          $("#searchingField").removeClass('d-none');
          this.counter = 1;
        } else {
          $("#searchingField").addClass('d-none');
          this.counter = 0;
        }
      },
      addOption() {
        $("#optionarea").addClass('d-block');
        this.counter++;
      },
      removeOption(n) {
        $("#counterrow" + n).remove();
        if ($(".counterrow").length == 0) {
          this.counter = 0;
        }
      }
    }
  })
})(jQuery);

$(window).on('load', function () {
  const sortable = "#sortable";
  $(sortable).sortable({
    stop: function (event, ui) {
      $(".request-loader").addClass('show');
      let fd = new FormData();
      $(".ui-state-default.ui-sortable-handle").each(function (index) {
        fd.append('ids[]', $(this).data('id'));
        let order = parseInt(index) + 1
        fd.append('orders[]', order);
      });
      $.ajax({
        url: orderUpdateUrl,
        method: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
          $(".request-loader").removeClass('show');
        }
      })
    }
  });
  $(sortable).disableSelection();
});