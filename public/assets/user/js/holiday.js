$(function () {
  $('.calendar-container').pignoseCalendar('init', {
    disabledDates: jQuery.parseJSON($holidays),
    minDate: new Date(),
    disabledWeekdays: jQuery.parseJSON($weekends),
    theme: 'dark',
    // disabledWeekdays: [2, 4], // SUN (0), SAT (6)
    select: onClickHandler
  });
});


 function onClickHandler(date, obj) {
     if (date[0] !== null) {
         var $date = date[0]._i;
         $("input[name='date']").val($date);
         $("input[name='slot']").val("");
         $(".request-loader").show();
         $(".timeslot-box").hide();
         $.ajax({
             url: timeSlotUrl,
             type: "get",
             data: {
                 date: $date,
             },
             success: function (data) {
                 $("#bookedSlot").addClass("d-none");
                 let slots = "";
                 $(".timeslot-box").show();
                 if (data.length !== 0) {
                     for (let i = 0; i < data.length; i++) {
                         slots += `<span   class="single-timeslot mr-2 mb-2  p-2 rounded" dir="ltr" data-id="${data[i].id}" data-slot="${data[i].start} - ${data[i].end}"  >${data[i].start} - ${data[i].end}</span>`;
                     }
                     $(".timeslot-box").html(slots);
                 } else {
                     slots += `<span class="text-warning rounded">No Slots Available</span>`;
                     $(".timeslot-box").html(slots);
                 }
                 $(".request-loader").hide();
             },
             error: function (data) {
                 console.log(data);
             },
         });
     }
 }

 $(document).on("click", ".single-timeslot", function (e) {
     let slotId = $(this).attr("data-id");
     let date = $("input[name='date']").val();
     $.ajax({
         url: checkThisSlot,
         type: "get",
         data: {
             slotId: slotId,
             date: date,
         },
         success: function (data) {
             if (data == "booked") {
                 $("#bookedSlot").removeClass("d-none");
             } else {
                 console.log(data);
                 $("#bookedSlot").addClass("d-none");
             }
         },
         error: function (err) {
             console.log(err);
         },
     });
     $(".single-timeslot").removeClass("active");
     $(this).addClass("active");
     $("input[name='slot']").val($(this).attr("data-slot"));
     $("input[name='slotId']").val($(this).attr("data-id"));
 });
