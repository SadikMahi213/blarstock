<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title> {{ $setting->siteName(__($pageTitle)) }}</title>

        @include('partials.seo')

        <link rel="stylesheet" href="{{ asset('assets/universal/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/universal/css/tabler.css') }}">
        <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/jquery.bxslider.min.css') }}">

        @stack('page-style-lib')

        <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/main.css') }}">

        @stack('page-style')
        
        <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/color.php?color1=' . $setting->first_color . '&color2=' . $setting->second_color) }}">
    </head>

    <body>
        <!--==================== Preloader Start ====================-->
        <div class="preloader">
            <div class="loader">
                <img src="{{ asset($activeThemeTrue . 'images/preloader.gif') }}" alt="loading...">
            </div>
        </div>
        <!--==================== Preloader End ====================-->

        <!--==================== Overlay Start ====================-->
        <div class="body-overlay"></div>
        <!--==================== Overlay End ====================-->

        <!-- ==================== Scroll to Top End Here ==================== -->
        <a class="scroll-top"><i class="ti ti-arrow-move-up"></i></a>
        <!-- ==================== Scroll to Top End Here ==================== -->

        @yield('content')

        @php
            $cookie = App\Models\SiteData::where('data_key','cookie.data')->first();
        @endphp

        @if(($cookie->data_info->status == ManageStatus::ACTIVE) && !\Cookie::get('gdpr_cookie'))
            <!-- cookies dark version start -->
            <div class="cookies-card text-center hide">
                <div class="cookies-card__icon">
                    <img src="{{ getImage('assets/universal/images/cookie.png') }}" alt="cookies">
                </div>
    
                <p class="mt-4 cookies-card__content">{{ $cookie->data_info->short_details }}</p>
    
                <div class="cookies-card__btn mt-4">
                    <button type="button" class="btn btn--base px-5 policy">@lang('Allow')</button>
                    <a href="{{ route('cookie.policy') }}" target="_blank" type="button" class="text--base px-5 pt-3">@lang('Learn more')</a>
                </div>
            </div>
            <!-- cookies dark version end -->
        @endif

        <script src="{{ asset('assets/universal/js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/universal/js/bootstrap.js') }}"></script>

        @stack('page-script-lib')

        @include('partials.plugins')
        @include('partials.toasts')

        <script src="{{ asset($activeThemeTrue . 'js/main.js') }}"></script>

        @stack('page-script')

        <script>
            (function ($) {
                "use strict";

                $('.policy').on('click',function() {
                    $.get('{{route('cookie.accept')}}', function(response) {
                        $('.cookies-card').addClass('d-none');
                    });
                });

                setTimeout(function() {
                    $('.cookies-card').removeClass('hide');
                },2000);
                
                Array.from(document.querySelectorAll('table')).forEach(table => {
                    let heading = table.querySelectorAll('thead tr th');
                    Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                        Array.from(row.querySelectorAll('td')).forEach((column, i) => {
                            if (heading[i]) { 
                                column.setAttribute('data-label', heading[i].innerText);
                            }
                        });
                    });
                });

            })(jQuery);
        </script>
    </body>
</html>
