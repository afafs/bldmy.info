<!DOCTYPE html>
<html lang="en" @yield('rtl')>
    <head>
        <!--====== Required meta tags ======-->
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        {{-- og meta tags --}}
        <meta property="og:image" itemprop="image" content="@yield('og-image')">
        <meta property="og:image:type" content="image/jpg">
        <meta property="og:image:width" content="1024">
        <meta property="og:image:height" content="1024">
        <meta property="og:type" content="website" />
        <meta property="og:title" content="@yield('og-title')" />
        <meta property="og:description" content="@yield('og-description')" />

        <!--====== Title ======-->
        <title>{{$vcard->name}}</title>
        <link rel="shortcut icon" href="{{asset('assets/front/img/user/vcard/' . $vcard->profile_image)}}" type="image/x-icon">
        <!--====== Bootstrap css ======-->
        <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">
        <!--====== FontAwesoem css ======-->
        <link rel="stylesheet" href="{{asset('assets/front/css/all.min.css')}}">
        <!--====== Magnific Popup css ======-->
        <link rel="stylesheet" href="{{asset('assets/front/css/magnific-popup.css')}}">
        <!--====== Slick css ======-->
        <link rel="stylesheet" href="{{asset('assets/front/css/slick.css')}}">

        <!--====== Style css ======-->
        @if ($vcard->template <= 4)
        <link rel="stylesheet" href="{{asset('assets/front/css/profile/vcard/template1234/vcard.css')}}">
        @else
        <link rel="stylesheet" href="{{asset('assets/front/css/profile/vcard/template5-10/style.css')}}">
        @endif
        <!--====== RTL css ======-->
        @yield('styles')
        <!--====== RTL css ======-->
        @yield('rtl-css')
        <!--====== Base color ======-->
        @yield('base-color')
    </head>
    <body class="@yield('body')">

        @yield('content')

        <!-- Modal -->
        <div class="modal fade" id="socialMediaModal" tabindex="-1" role="dialog" aria-labelledby="socialMediaModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{$keywords["Share_On"] ?? "Share On"}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="actions">
                            <div class="action-btn">
                                <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{url()->current()}}&src=sdkpreparse"><i class="fab fa-facebook-f"></i></a>
                                <br>
                                <span>{{$keywords["Facebook"] ?? "Facebook"}}</span>
                            </div>
                            <div class="action-btn">
                                <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{urlencode(url()->current()) }}" class="linkedin"><i class="fab fa-linkedin-in"></i></a>
                                <br>
                                <span>{{$keywords["Linkedin"] ?? "Linkedin"}}</span>
                            </div>
                            <div class="action-btn">
                                <a class="twitter" href="https://twitter.com/intent/tweet?text={{url()->current()}}"><i class="fab fa-twitter"></i></a>
                                <br>
                                <span>{{$keywords["Twitter"] ?? "Twitter"}}</span>
                            </div>
                            <div class="action-btn">
                                <a class="whatsapp" href="whatsapp://send?text={{url()->current()}}"><i class="fab fa-whatsapp"></i></a>
                                <br>
                                <span>{{$keywords["Whatsapp"] ?? "Whatsapp"}}</span>
                            </div>
                            <div class="action-btn">
                                <a href="sms:?body={{url()->current()}}" class="sms"><i class="fas fa-sms"></i></a>
                                <br>
                                <span>{{$keywords["SMS"] ?? "SMS"}}</span>
                            </div>
                            <div class="action-btn">
                                <a class="mail" href="mailto:?subject=Digital Card&body=Check out this digital card {{url()->current()}}."><i class="fas fa-at"></i></a>
                                <br>
                                <span>{{$keywords["Mail"] ?? "Mail"}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="snackbar"></div>

        <!--====== Jquery js ======-->
        <script src="{{asset('assets/front/js/vendor/jquery-3.4.1.min.js')}}"></script>
        <!--====== Popper js ======-->
        <script src="{{asset('assets/front/js/popper.min.js')}}"></script>
        <!--====== Bootstrap js ======-->
        <script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
        <!--====== magnific popup js ======-->
        <script src="{{asset('assets/front/js/jquery.magnific-popup.min.js')}}"></script>
        <!--====== slick js ======-->
        <script src="{{asset('assets/front/js/slick.min.js')}}"></script>
        <!--====== lazyload js ======-->
        <script src="{{asset('assets/front/js/lazyload.min.js')}}"></script>
        <script>
            var dir = {{$vcard->direction}};
        </script>
        <!--====== vcard js ======-->
        @if ($vcard->template <= 4)
        <script src="{{asset('assets/front/js/profile/vcard/template1234/vcard.js')}}"></script>
        @else
        <script src="{{asset('assets/front/js/profile/vcard/template5-10/vcard.js')}}"></script>
        @endif
        @if (session()->has('success'))
        <script>
            showSnackbar("Mail sent successfully!");
        </script>
        @endif

        <script>
            $(document).ready(function() {
                $('#projectDetails').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget)
                    var details = button.data('details')
                    var modal = $(this)
                    modal.find('.modal-body').text(details)
                })
                $(".no-ext").on('click', function() {
                    $("#serviceDetails").modal('show');
                    let details = $(this).data('details');
                    $("#serviceDetails .modal-body").html(details);
                });
            });
        </script>
    </body>
</html>
