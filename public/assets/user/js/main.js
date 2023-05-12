
//===== Prealoder
$(window).on('load', function (event) {
    $('.preloader').delay(500).fadeOut(500);
    //===== Popup
    if ($('.popup-wrapper').length > 0) {
        let $firstPopup = $('.popup-wrapper').eq(0);
        appearPopup($firstPopup);
    }
});

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    "use strict";


    // format date & time for announcement popup
    $('.offer-timer').each(function () {
        let $this = $(this);
        let date = new Date($this.data('end_date'));
        let year = parseInt(new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date));
        let month = parseInt(new Intl.DateTimeFormat('en', { month: 'numeric' }).format(date));
        let day = parseInt(new Intl.DateTimeFormat('en', { day: '2-digit' }).format(date));
        let time = $this.data('end_time');
        time = time.split(':');
        let hour = parseInt(time[0]);
        let minute = parseInt(time[1]);
        $this.syotimer({
            year: year,
            month: month,
            day: day,
            hour: hour,
            minute: minute
        });
    });



    //===== Sticky
    $(window).on('scroll', function (event) {
        var scroll = $(window).scrollTop();
        if (scroll < 110) {
            $(".header-menu").removeClass("sticky");

        } else {
            $(".header-menu").addClass("sticky");
        }
    });

    
    //===== categories dropdown
    jQuery(document).ready(function (e) {
        function t(t) {
            e(t).bind("click", function (t) {
                t.preventDefault();
                e(this).parent().fadeOut()
            })
        }
        e(".seylon-toggler").bind("click", function () {
            var t = e(this).parents(".seylon-dropdown").children(".seylon-dropdown-menu").is(":hidden");
            e(".seylon-dropdown .seylon-dropdown-menu").hide();
            e(".seylon-dropdown .seylon-toggler").removeClass("active");
            if (t) {
                e(this).parents(".seylon-dropdown").children(".seylon-dropdown-menu").toggle().parents(".seylon-dropdown").children(".seylon-toggler").addClass("active")
            }
        });
        e(document).bind("click", function (t) {
            var n = e(t.target);
            if (!n.parents().hasClass("seylon-dropdown")) e(".seylon-dropdown .seylon-dropdown-menu").hide();
        });
        e(document).bind("click", function (t) {
            var n = e(t.target);
            if (!n.parents().hasClass("seylon-dropdown")) e(".seylon-dropdown .seylon-toggler").removeClass("active");
        })
    });
    //====== Nice Select
    // $('select').niceSelect();

    //====== Magnific Popup

    $('.video-popup').magnificPopup({
        type: 'iframe'
        // other options
    });


    //===== Magnific Popup

    $('.image-popup').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });


    //===== slider Range



    // $("#amount1").val("$" + $("#slider-range").slider("values", 0) + ".00");

    // $("#amount2").val("$" + $("#slider-range").slider("values", 1) + ".00");



    //===== slick Products View

    $(".details-image").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        rtl: rtl == 1 ? true : false,
        asNavFor: '.details-image-thumb'
    });

    $(".details-image-thumb").slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.details-image',
        dots: false,
        infinite: true,
        rtl: rtl == 1 ? true : false,
        arrows: true,
        prevArrow: '<span class="prev"><i class="fal fa-chevron-left"></i></span>',
        nextArrow: '<span class="next"><i class="fal fa-chevron-right"></i></span>',
        centerMode: false,
        focusOnSelect: true
    });



    //===== Go to Top

    // Scroll Event
    $(window).on('scroll', function () {
        var scrolled = $(window).scrollTop();
        if (scrolled > 300) $('.go-top').addClass('active');
        if (scrolled < 300) $('.go-top').removeClass('active');
    });

    // Click Event
    $('.go-top').on('click', function () {
        $("html, body").animate({
            scrollTop: "0"
        }, 1200);
    });



    //=====  label File

    $('.input-file').each(function () {
        var $input = $(this),
            $label = $input.next('.js-labelFile'),
            labelVal = $label.html();

        $input.on('change', function (element) {
            var fileName = '';
            if (element.target.value) fileName = element.target.value.split('\\').pop();
            fileName ? $label.addClass('has-file').find('.js-fileName').html(fileName) : $label.removeClass('has-file').html(labelVal);
        });
    });


    // developer


    // ad management
    $(".getStateForad").on('change', function () {
        let url = $("#stateGetterForAds").attr('value');
        let id = $(this).val();
        let code = $(this).data('code');

        var formData = new FormData();
        formData.append('url', url);
        formData.append('country_id', id);
        formData.append('code', code);
        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                jQuery("#" + code + '_state').empty();
                jQuery("#" + code + '_city').empty();
                jQuery.each(response.states, function (key, value) {
                    jQuery("#" + code + '_state').append('<option value="' + value.id + '">' + value.name + '</option>')
                });
            },
            error: function (data) {
                console.log('Error......');
            }
        });
    });
    $(".getCityForad").on('change', function () {
        let url = $("#cityGetterForAds").attr('value');
        let id = $(this).val();
        let code = $(this).data('code');

        var formData = new FormData();
        formData.append('url', url);
        formData.append('state_id', id);
        formData.append('code', code);
        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                jQuery("#" + code + '_city').empty();
                jQuery.each(response.cities, function (key, value) {
                    jQuery("#" + code + '_city').append('<option value="' + value.id + '">' + value.name + '</option>')
                });
            },
            error: function (data) {
                console.log('Error......');
            }
        });
    });

    //  image (id) preview js/
    $(document).on('change', '#image', function (event) {
        var file = event.target.files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.showImage img').attr('src', e.target.result);
        };

        reader.readAsDataURL(file);
    })

    // lazy load init
    var lazyLoadInstance = new LazyLoad();

    $('#allads, #Pendingads, #featuredads, #publishedads, #rejectedads').DataTable({});

});

function appearPopup($this) {
    let closedPopups = [];
    if (sessionStorage.getItem('closedPopups')) {
        closedPopups = JSON.parse(sessionStorage.getItem('closedPopups'));
    }
    // if the popup is not in closedPopups Array
    if (closedPopups.indexOf($this.data('popup_id')) == -1) {
        $('#' + $this.attr('id')).show();
        let popupDelay = $this.data('popup_delay');
        setTimeout(function () {
            jQuery.magnificPopup.open({
                items: { src: '#' + $this.attr('id') },
                type: 'inline',
                callbacks: {
                    afterClose: function () {
                        // after the popup is closed, store it in the sessionStorage & show next popup
                        closedPopups.push($this.data('popup_id'));
                        sessionStorage.setItem('closedPopups', JSON.stringify(closedPopups));
                        if ($this.next('.popup-wrapper').length > 0) {
                            appearPopup($this.next('.popup-wrapper'));
                        }
                    }
                }
            }, 0);
        }, popupDelay);
    } else {
        if ($this.next('.popup-wrapper').length > 0) {
            appearPopup($this.next('.popup-wrapper'));
        }
    }
}

