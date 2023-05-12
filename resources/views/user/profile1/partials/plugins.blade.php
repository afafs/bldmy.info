<div id="WAButton"></div>
@if (is_array($packagePermissions) && in_array('Google Analytics', $packagePermissions))
    @if ($userBs->analytics_status == 1)
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="//www.googletagmanager.com/gtag/js?id={{ $userBs->measurement_id }}"></script>
        <script>
            "use strict";
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', '{{ $userBs->measurement_id }}');
        </script>
    @endif
@endif
@if (is_array($packagePermissions) && in_array('Facebook Pixel', $packagePermissions))
    @if ($userBs->pixel_status == 1)
        <!-- Meta Pixel Code -->
        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $userBs->pixel_id }}');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ $userBs->pixel_id }}&ev=PageView&noscript=1" /></noscript>
        <!-- End Meta Pixel Code -->
    @endif
@endif
@if (is_array($packagePermissions) && in_array('Tawk.to', $packagePermissions))
    @if ($userBs->tawkto_status == 1)
        @php
            $directLink = str_replace('tawk.to', 'embed.tawk.to', $userBs->tawkto_direct_chat_link);
            $directLink = str_replace('chat/', '', $directLink);
        @endphp
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            "use strict";
            let directLink = '{{ $directLink }}';
            var Tawk_API = Tawk_API || {},
                Tawk_LoadStart = new Date();
            (function() {
                var s1 = document.createElement("script"),
                    s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = directLink;
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();
        </script>
        <!--End of Tawk.to Script-->
    @endif
@endif
@if (is_array($packagePermissions) && in_array('WhatsApp', $packagePermissions))
    @if ($userBs->whatsapp_status == 1)
        <script type="text/javascript">
            var whatsapp_popup = {{ $userBs->whatsapp_popup_status }};
            var whatsappImg = "{{ asset('assets/front/img/whatsapp.svg') }}";
            $(function() {
                $('#WAButton').floatingWhatsApp({
                    phone: "{{ $userBs->whatsapp_number }}", //WhatsApp Business phone number
                    headerTitle: "{{ $userBs->whatsapp_header_title }}", //Popup Title
                    popupMessage: `{!! nl2br($userBs->whatsapp_popup_message) !!}`, //Popup Message
                    showPopup: whatsapp_popup == 1 ? true : false, //Enables popup display
                    buttonImage: '<img src="' + whatsappImg + '" />', //Button Image
                    position: "right"
                });
            });
        </script>
    @endif
@endif
