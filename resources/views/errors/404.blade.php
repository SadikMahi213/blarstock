<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $setting->siteName($pageTitle ?? '') }}</title>
        <link rel="shortcut icon" type="image/png" href="{{ getImage(getFilePath('logoFavicon').'/favicon.png') }}">

        <link rel="stylesheet" href="{{ asset('assets/universal/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/universal/css/tabler.css') }}">
        <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/main.css') }}">
        <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/color.php?color1=' . $setting->primary_color . '&color2=' . $setting->secondary_color) }}">
    </head>

    <body class="pt-0">

        <div class="error">
            <div class="error-page bg-img" data-background-image="{{ asset('assets/universal/images/error-page-bg.png') }}">
                 <div class="container">
                      <div class="row justify-content-center">
                           <div class="col-lg-6">
                                <div class="error-page__content">
                                     <div class="error-page__thumb bg-img" data-background-image="{{ asset('assets/universal/images/error-bg.png') }}">
                                          <h1 class="error-page__thumb__number">404</h1>
                                          <img src="{{ asset('assets/universal/images/error.png') }}" alt="image">
                                     </div>
                                     <div class="error-page__txt">
                                          <h3 class="error-page__txt__title">404, @lang('Page Not Found') 😞</h3>
                                          <p class="error-page__txt__desc">@lang('Oops')! 😖 @lang('The requested URL was not found on this server').</p>
                                          <div class="d-flex justify-content-center flex-wrap gap-2">
                                               <button onclick="history.back()" class="btn btn--secondary">
                                                    <i class="ti ti-arrow-back"></i> @lang('Back to Previous')
                                               </button>
                                               <a href="{{ route('home') }}" class="btn btn--base">
                                                    <i class="ti ti-home"></i> @lang('Back to Home')
                                               </a>
                                          </div>
                                     </div>
                                </div>
                           </div>
                      </div>
                 </div>
            </div>
        </div>

      <script src="{{ asset('assets/universal/js/jquery-3.7.1.min.js') }}"></script>
      <script src="{{ asset('assets/universal/js/bootstrap.js') }}"></script>
      <script src="{{ asset($activeThemeTrue . 'js/main.js') }}"></script>
  </body>
</html>