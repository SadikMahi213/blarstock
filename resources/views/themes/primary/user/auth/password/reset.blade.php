@extends($activeTheme. 'layouts.app')
@section('content')
    <section class="account">
        <div class="account__form">
            <div class="account__form__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/password_reset/' . $siteData?->data_info?->image, '1920x1080') }}"></div>
            <div class="account__form__container">
                <div class="account__top d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <a href="{{ route('home') }}"><img src="{{ getImage(getFilePath('logoFavicon').'/logo_dark.png') }}" alt="Logo"></a>
                    </div>
                    <a href="{{ route('home') }}" class="back-to-home"><i class="ti ti-home"></i></a>
                </div>
                <div class="account__form__content">
                    <h3 class="account__form__title">{{ __($siteData?->data_info?->heading) }}</h3>
                    <p>{{ __($siteData?->data_info?->subheading) }}</p>
                </div>
                <form method="POST" action="{{ route('user.password.reset') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="code" value="{{ $code }}">

                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="your-password" class="form--label">@lang('Password')</label>
                            <div class="position-relative">
                                <input id="your-password" type="password" class="form-control form--control @if($setting->strong_pass) secure-password @endif" name="password" required>
                                <span class="password-show-hide ti ti-eye toggle-password" id="#your-password"></span>
                            </div>
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="your-password" class="form--label">@lang('Confirm Password')</label>
                            <div class="position-relative">
                                <input id="confirm-password" type="password" class="form-control form--control" name="password_confirmation" required>
                                <span class="password-show-hide ti ti-eye toggle-password" id="#confirm-password"></span>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 form-group">
                            <button type="submit" class="btn btn--base w-100">{{ __($siteData?->data_info?->submit_button_text) }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="account__img bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/password_reset/' . $siteData?->data_info?->bg_image, '1920x1280') }}"></div>
    </section>
@endsection

@if ($setting->strong_pass)
    @push('page-style-lib')
        <link rel="stylesheet" href="{{ asset('assets/universal/css/strongPassword.css') }}">
    @endpush

    @push('page-script-lib')
        <script src="{{asset('assets/universal/js/strongPassword.js')}}"></script>
    @endpush
@endif
