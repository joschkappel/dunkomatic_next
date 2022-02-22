@push('css')
<style>
    .cookiealert {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        margin: 0 !important;
        z-index: 999;
        opacity: 0;
        visibility: hidden;
        border-radius: 0;
        transform: translateY(100%);
        transition: all 500ms ease-out;
        color: #ecf0f1;
        background: #212327;
    }

    .cookiealert.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0%);
        transition-delay: 1000ms;
    }

    .cookiealert a {
        text-decoration: underline
    }

    .cookiealert .acceptcookies {
        margin-left: 10px;
        vertical-align: baseline;
    }
</style>
@endpush

@section('footer')
<div class="alert text-center cookiealert" role="alert">
    <b>@lang('Do you like cookies') ?</b> &#x1F36A; @lang('We use cookies to ensure you get the best experience on our website.') <a
        href="{{ route('cookies', ['language'=>app()->getLocale()])}}" >@lang('Learn more') </a>
    <button type="button" class="btn btn-primary btn-sm acceptcookies">
        @lang('I agree')
    </button>
</div>
@endsection


@push('js')
<script>
    (function () {
        "use strict";

        var cookieAlert = document.querySelector(".cookiealert");
        var acceptCookies = document.querySelector(".acceptcookies");

        if (!cookieAlert) {
            return;
        }

        cookieAlert.offsetHeight; // Force browser to trigger reflow (https://stackoverflow.com/a/39451131)

        // Show the alert if we cant find the "dunkomatic_next_cookie_consent" cookie
        if (!getCookie("dunkomatic_next_cookie_consent")) {
            cookieAlert.classList.add("show");
        }

        // When clicking on the agree button, create a 1 year
        // cookie to remember user's choice and close the banner
        acceptCookies.addEventListener("click", function () {
            setCookie("dunkomatic_next_cookie_consent", true, 365);
            cookieAlert.classList.remove("show");

            // dispatch the accept event
            window.dispatchEvent(new Event("cookieAlertAccept"))
        });

        // Cookie functions from w3schools
        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) === 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
    })();
</script>

@endpush
