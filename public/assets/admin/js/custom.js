"use strict";

WebFont.load({
  google: { "families": ["Lato:300,400,700,900"] },
  custom: { "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: [mainurl + '/assets/admin/css/fonts.min.css'] },
  active: function () {
    sessionStorage.fonts = true;
  }
});

/*****************************************************
  ==========Bootstrap Notify start==========
  ******************************************************/

function bootnotify(message, title, type) {
  var content = {};

  content.message = message;
  content.title = title;
  content.icon = 'fa fa-bell';

  $.notify(content, {
    type: type,
    placement: {
      from: 'top',
      align: 'right'
    },
    showProgressbar: true,
    time: 1000,
    allow_dismiss: true,
    delay: 4000
  });
}


/*****************************************************
==========Bootstrap Notify end==========
******************************************************/

$(function ($) {

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  /* ***************************************************************
  ==========disabling default behave of form submits start==========
  *****************************************************************/
  $("#ajaxEditForm").attr('onsubmit', 'return false');
  $("#ajaxForm").attr('onsubmit', 'return false');
  /* *************************************************************
  ==========disabling default behave of form submits end==========
  ***************************************************************/

  // Sidebar Search

  $(".sidebar-search").on('input', function () {
    let term = $(this).val().toLowerCase();

    if (term.length > 0) {
      $(".sidebar ul li.nav-item").each(function (i) {
        let menuName = $(this).find("p").text().toLowerCase();
        let $mainMenu = $(this);

        // if any main menu is matched
        if (menuName.indexOf(term) > -1) {
          $mainMenu.removeClass('d-none');
          $mainMenu.addClass('d-block');
        } else {
          let matched = 0;
          let count = 0;
          // search sub-items of the current main menu (which is not matched)
          $mainMenu.find('span.sub-item').each(function (i) {
            // if any sub-item is matched  of the current main menu, set the flag
            if ($(this).text().toLowerCase().indexOf(term) > -1) {
              count++;
              matched = 1;
            }
          });


          // if any sub-item is matched  of the current main menu (which is not matched)
          if (matched == 1) {
            $mainMenu.removeClass('d-none');
            $mainMenu.addClass('d-block');
          } else {
            $mainMenu.removeClass('d-block');
            $mainMenu.addClass('d-none');
          }
        }
      });
    } else {
      $(".sidebar ul li.nav-item").addClass('d-block');
    }
  });




  /* ***************************************************
  ==========bootstrap datepicker start==========
  ******************************************************/
  $('.datepicker').datepicker({
    autoclose: true
  });
  /* ***************************************************
  ==========bootstrap datepicker end==========
  ******************************************************/



  /* ***************************************************
  ==========fontawesome icon picker start==========
  ******************************************************/
  $('.icp-dd').iconpicker();
  /* ***************************************************
  ==========fontawesome icon picker upload end==========
  ******************************************************/


  /* ***************************************************
  ==========Summernote initialization start==========
  ******************************************************/
  $(".summernote").each(function (i) {
    let theight;
    let $summernote = $(this);
    if ($(this).data('height')) {
      theight = $(this).data('height');
    } else {
      theight = 200;
    }
    $('.summernote').eq(i).summernote({
      height: theight,
      dialogsInBody: true,
      dialogsFade: false,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['height', ['height']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']],
      ],
      popover: {
        image: [
          ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
          ['float', ['floatLeft', 'floatRight', 'floatNone']],
          ['remove', ['removeMedia']]
        ],
        link: [
          ['link', ['linkDialogShow', 'unlink']]
        ],
        table: [
          ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
          ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
        ],
        air: [
          ['color', ['color']],
          ['font', ['bold', 'underline', 'clear']],
          ['para', ['ul', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture']]
        ]
      },
      callbacks: {
        onImageUpload: function (files) {
          $(".request-loader").addClass('show');

          let fd = new FormData();
          fd.append('image', files[0]);

          $.ajax({
            url: imgupload,
            method: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function (data) {
              $summernote.summernote('insertImage', data);
              $(".request-loader").removeClass('show');
            }
          });

        }
      }
    });
  });



  $(document).on('click', ".note-video-btn", function () {

    let i = $(this).index();

    if ($(".summernote").eq(i).parents(".modal").length > 0) {

      setTimeout(() => {
        $("body").addClass('modal-open');
      }, 500);
    }
  });


  /* ***************************************************
  ==========Summernote initialization end==========
  ******************************************************/




  $('.icp-dd').iconpicker();


  /* ***************************************************
  ==========Summernote initialization end==========
  ******************************************************/



  /* ***************************************************
  ==========Bootstrap Notify start==========
  ******************************************************/
  function bootnotify(message, title, type) {
    var content = {};

    content.message = message;
    content.title = title;
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: type,
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      allow_dismiss: true,
      delay: 4000,
    });
  }
  /* ***************************************************
  ==========Bootstrap Notify end==========
  ******************************************************/



  /* ***************************************************
  ==========Appointment start ==========
  ******************************************************/

  $(".editbtnAd").on('click', function () {
    let datas = $(this).data();
    var table_id = datas.id
    $("#" + datas.modal).find('.modal-body #table_id').val(table_id)
    if (datas.edit == 'editCategory') {
      var name = datas.name
      var price = datas.price
      $("#" + datas.modal).find('.modal-body #name').val(name)
      $("#" + datas.modal).find('.modal-body #price').val(price)
      var image = datas.image
      var host = window.location.origin;
    }
    if (image) {
      $("#" + datas.modal).find('.modal-body #imageSrc').attr('src', host + '/assets/user/img/category/' + image)
    } else {
      $("#" + datas.modal).find('.modal-body #imageSrc').attr('src', host + '/assets/admin/img/noimage.jpg')
    }
    $("#" + datas.modal).find('.modal-body #tableId').val(table_id)
  })

  /* ***************************************************
  ==========Appointment end ==========
  ******************************************************/



  /* ***************************************************
  ==========Form Submit with AJAX Request Start==========
  ******************************************************/
  $('.submitBtn').on('click', function (e) {
    let $theForm = $(e.target).data('form');
    $(e.target).attr('disabled', true);
   $(".request-loader").addClass("show");
   
    let ajaxForm = document.getElementById($theForm);
    let fd = new FormData(ajaxForm);
    let url = $("#" + $theForm).attr('action');
    let method = $("#" + $theForm).attr('method');
    if ($("#" + $theForm + " .summernote").length > 0) {
      $("#" + $theForm + " .summernote").each(function (i) {
        let content = $(this).summernote('code');
        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      });
    }

    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $(e.target).attr('disabled', false);
        $(".request-loader").removeClass("show");

        $(".em").each(function () {
          $(this).html('');
        })

        if (data == "success") {
          location.reload();
        }

        // if error occurs
        else if (typeof data.error != 'undefined') {
          for (let x in data) {
            if (x == 'error') {
              continue;
            }
            document.getElementById('err' + x).innerHTML = data[x][0];
          }
        }
      },
      error: function (error) {
        $(".em").each(function () {
          $(this).html('');
        })
        for (let x in error.responseJSON.errors) {
          document.getElementById('err' + x).innerHTML = error.responseJSON.errors[x][0];
        }
        $(".request-loader").removeClass("show");
        $(e.target).attr('disabled', false);
      }
    });
  });

  $("#permissionBtn").on('click', function () {
    $("#permissionsForm").trigger("submit");
  });

  $("#langBtn").on('click', function () {
    $("#langForm").trigger("submit");
  });


  // appointment settings

  $(".full-payment").on('click', function (e) {
    let val = $(e.target).val();
    $("#pp").val(val);
    if ($("#pp").val() == 1) {
      $("#PercentageBox").hide();
    } else {
      $("#PercentageBox").show();
    }
  });

  jQuery(document).ready(function ($) {
    let vl = $("#pp").val();
    if (vl == 1) {
      $("#PercentageBox").hide();
    } else {
      $("#PercentageBox").show();
    }
  });

  // total fee box
  $(".appointment_category").on('click', function (e) {
    let catval = $(e.target).val();
    $("#cat").val(catval);
    if ($("#cat").val() == 1) {
      $("#totalFeeBox").hide();
    } else {
      $("#totalFeeBox").show();
    }
  });


  jQuery(document).ready(function ($) {
    let catvl = $("#cat").val();
    if (catvl == 1) {
      $("#totalFeeBox").hide();
    } else {
      $("#totalFeeBox").show();
    }
  });


  // $(function () {
  //   $('.calendar-container').pignoseCalendar('init', {
  //     disabledDates: jQuery.parseJSON($holidays),
  //     minDate: new Date(),
  //     disabledWeekdays: jQuery.parseJSON($weekends),
  //     theme: 'dark',
  //     // disabledWeekdays: [2, 4], // SUN (0), SAT (6)
  //     select: onClickHandler
  //   });
  // });

  // function onClickHandler(date, obj) {
  //   if (date[0] !== null) {
  //     var $date = date[0]._i;
  //     $("input[name='date']").val($date);
  //     $("input[name='slot']").val('');
  //     $('.request-loader').show();
  //     $('.timeslot-box').hide();
  //     $.ajax({
  //       url: timeSlotUrl,
  //       type: 'get',
  //       data: {
  //         date: $date
  //       },
  //       success: function (data) {
  //         $("#bookedSlot").addClass('d-none');
  //         let slots = '';
  //         $('.timeslot-box').show();
  //         if (data.length !== 0) {
  //           for (let i = 0; i < data.length; i++) {
  //             slots += `<span   class="single-timeslot mr-2 mb-2  p-2 rounded" dir="ltr" data-id="${data[i].id}" data-slot="${data[i].start} - ${data[i].end}"  >${data[i].start} - ${data[i].end}</span>`;
  //           }
  //           $(".timeslot-box").html(slots);
  //         } else {

  //           slots += `<span class="text-warning rounded">No Slots Available</span>`;
  //           $(".timeslot-box").html(slots);
  //         }
  //         $('.request-loader').hide();
  //       }, error: function (data) {
  //         console.log(data)
  //       }
  //     });
  //   }
  // }

  // $(document).on('click', '.single-timeslot', function (e) {
  //   let slotId = $(this).attr('data-id')
  //   let date = $("input[name='date']").val();
  //   $.ajax({
  //     url: checkThisSlot,
  //     type: 'get',
  //     data: {
  //       slotId: slotId,
  //       date: date,
  //     },
  //     success: function (data) {
  //       if (data == 'booked') {
  //         $("#bookedSlot").removeClass('d-none');
  //       } else {
  //         console.log(data);
  //         $("#bookedSlot").addClass('d-none');
  //       }
  //     }, error: function (err) {
  //       console.log(err)
  //     }
  //   });
  //   $('.single-timeslot').removeClass('active');
  //   $(this).addClass('active');
  //   $("input[name='slot']").val($(this).attr('data-slot'));
  //   $("input[name='slotId']").val($(this).attr('data-id'));
  // })


  // appointment settings

  /* ***************************************************
  ==========Form Submit with AJAX Request End==========
  ******************************************************/

  /* ***************************************************
  ==========datatables start==========
  ******************************************************/
  $('#basic-datatables').DataTable({
    responsive: true,
    ordering: false
  });
  /* ***************************************************
  ==========datatables end==========
  ******************************************************/


  /* ***************************************************
  ==========Form Prepopulate After Clicking Edit Button Start==========
  ******************************************************/
  $(".editbtn").on('click', function () {

    let datas = $(this).data();
    delete datas['toggle'];

    for (let x in datas) {
      if ($("#in" + x).hasClass('summernote')) {
        $("#in" + x).summernote('code', datas[x]);
      } else if ($("#in" + x).hasClass('image')) {
        $("#in" + x).attr('src', datas[x]);
      } else if ($("#in" + x).data('role') == 'tagsinput') {
        if (datas[x].length > 0) {
          let arr = datas[x].split(" ");
          for (let i = 0; i < arr.length; i++) {
            $("#in" + x).tagsinput('add', arr[i]);
          }
        } else {
          $("#in" + x).tagsinput('removeAll');
        }
      }
      else if ($("input[name='" + x + "']").attr('type') == 'radio') {
        $("input[name='" + x + "']").each(function (i) {
          if ($(this).val() == datas[x]) {
            $(this).prop('checked', true);
          }
        });
      }
      else {
        $("#in" + x).val(datas[x]);
      }
    }

  });


  /* ***************************************************
  ==========Form Prepopulate After Clicking Edit Button End==========
  ******************************************************/




  /* ***************************************************
  ==========Form Update with AJAX Request Start==========
  ******************************************************/
  $("#updateBtn").on('click', function (e) {

    $(".request-loader").addClass("show");

    let ajaxEditForm = document.getElementById('ajaxEditForm');
    let fd = new FormData(ajaxEditForm);
    let url = $("#ajaxEditForm").attr('action');
    let method = $("#ajaxEditForm").attr('method');

    if ($("#ajaxEditForm .summernote").length > 0) {
      $("#ajaxEditForm .summernote").each(function (i) {
        let content = $(this).summernote('code');
        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      })
    }

    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {

        $(".request-loader").removeClass("show");

        $(".em").each(function () {
          $(this).html('');
        })

        if (data == "success") {
          location.reload();
        }

        // if error occurs
        else if (typeof data.error != 'undefined') {
          for (let x in data) {
            if (x == 'error') {
              continue;
            }
            document.getElementById('eerr' + x).innerHTML = data[x][0];
          }
        }
      },
       error: function (error) {

        $(".em").each(function () {
          $(this).html('');
        })
        for (let x in error.responseJSON.errors) {
          document.getElementById('eerr' + x).innerHTML = error.responseJSON.errors[x][0];
          
        }
        $(".request-loader").removeClass("show");
        $(e.target).attr('disabled', false);
      }
    });
  });

  $(".update-btn").each(function () {
    $(this).on('click', function (e) {
      let $this = $(this);

      $(".request-loader").addClass("show");

      let formId = $(this).data('form_id');
      let ajaxEditForm = document.getElementById(formId);
      let fd = new FormData(ajaxEditForm);
      let url = $("#" + formId).attr('action');
      let method = $("#" + formId).attr('method');

      if ($("#" + formId + " .summernote").length > 0) {
        $("#" + formId + " .summernote").each(function (i) {
          let content = $(this).summernote('code');
          fd.delete($(this).attr('name'));
          fd.append($(this).attr('name'), content);
        })
      }

      $.ajax({
        url: url,
        method: method,
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
          let parentCount = $this.parents('.modal').length;
          let parentId;
          // if the form is in modal
          if (parentCount > 0) {
            parentId = $this.parents('.modal').attr('id');
          }
          // if the form is not in modal
          else {
            parentId = formId;
          }
          $(".request-loader").removeClass("show");

          $("#" + parentId).children(".em").each(function () {
            $(this).html('');
          })

          if (data == "success") {
            location.reload();
          }

          // if error occurs
          else if (typeof data.error != 'undefined') {
            for (let x in data) {
              if (x == 'error') {
                continue;
              }
              $("#" + parentId + " .eerr" + x).html(data[x][0]);
            }
          }
        }
      });
    });
  });
  /* ***************************************************
  ==========Form Update with AJAX Request End==========
  ******************************************************/



  /* ***************************************************
  ==========Delete Using AJAX Request Start==========
  ******************************************************/
  $('.deletebtn').on('click', function (e) {
    e.preventDefault();

    $(".request-loader").addClass("show");

    swal({
      title: $delete_title,
      text: $delete_subtitle,
      type: 'warning',
      buttons: {
        confirm: {
          text: $delete_btntext,
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          text: $cancel_btntext,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(this).parent(".deleteform").trigger('submit');
      } else {
        swal.close();
        $(".request-loader").removeClass("show");
      }
    });
  });
  /* ***************************************************
  ==========Delete Using AJAX Request End==========
  ******************************************************/


  /* ***************************************************
  ==========Close Ticket Using AJAX Request Start==========
  ******************************************************/
  $('.close-ticket').on('click', function (e) {
    e.preventDefault();

    $(".request-loader").addClass("show");

    swal({
      title: 'Are you sure?',
      text: "You want to close this ticket!",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes, close it!',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        swal.close();
        $(".request-loader").removeClass("show");
      } else {
        swal.close();
        $(".request-loader").removeClass("show");
      }
    });
  });
  /* ***************************************************
  ==========Delete Using AJAX Request End==========
  ******************************************************/


  /* ***************************************************
  ==========Delete Using AJAX Request Start==========
  ******************************************************/
  $(document).on('change', '.bulk-check', function () {
    let val = $(this).data('val');
    let checked = $(this).prop('checked');

    // if selected checkbox is 'all' then check all the checkboxes
    if (val == 'all') {
      if (checked) {
        $(".bulk-check").each(function () {
          $(this).prop('checked', true);
        });
      } else {
        $(".bulk-check").each(function () {
          $(this).prop('checked', false);
        });
      }
    }


    // if any checkbox is checked then flag = 1, otherwise flag = 0
    let flag = 0;
    $(".bulk-check").each(function () {
      let status = $(this).prop('checked');

      if (status) {
        flag = 1;
      }
    });

    // if any checkbox is checked then show the delete button
    if (flag == 1) {
      $(".bulk-delete").addClass('d-inline-block');
      $(".bulk-delete").removeClass('d-none');
    }
    // if no checkbox is checked then hide the delete button
    else {
      $(".bulk-delete").removeClass('d-inline-block');
      $(".bulk-delete").addClass('d-none');
    }
  });

  $('.bulk-delete').on('click', function () {

    swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes, delete it!',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(".request-loader").addClass('show');
        let href = $(this).data('href');
        let ids = [];

        // take ids of checked one's
        $(".bulk-check:checked").each(function () {
          if ($(this).data('val') != 'all') {
            ids.push($(this).data('val'));
          }
        });

        let fd = new FormData();
        for (let i = 0; i < ids.length; i++) {
          fd.append('ids[]', ids[i]);
        }

        $.ajax({
          url: href,
          method: 'POST',
          data: fd,
          contentType: false,
          processData: false,
          success: function (data) {

            $(".request-loader").removeClass('show');
            if (data == "success") {
              location.reload();
            }
          }
        });
      } else {
        swal.close();
      }
    });

  });
  /* ***************************************************
  ==========Delete Using AJAX Request End==========
  ******************************************************/


  //  image (id) preview js/
  $(document).on('change', '#image', function (event) {
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
      $('.showImage img').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  })
  //  image (class) preview js/
  $(document).on('change', '.image', function (event) {
    let $this = $(this);
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
      $this.prev('.showImage').children('img').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  });

  // datepicker & timepicker
  $("input.datepicker").datepicker();
  $('input.timepicker').timepicker();

  // select2
  $('.select2').select2();
});


$(document).on('change', '.langBtn', function () {
  let $this = $(this);
  var $code = $(this).val();

  $.ajax({
    url: $("#setLocale").val(),
    method: 'get',
    data: {
      code: $code
    },
    success: function (data) {
      // console.log(curr_url+$this.val())
      window.location = curr_url + $this.val();
    }
  });
})
