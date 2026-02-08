@extends($activeTheme. 'layouts.app')
@section('content')
    <section class="account">
        <div class="account__form">
            <div class="account__form__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/login/' . $siteData?->data_info?->image, '1920x1280') }}"></div>
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
                <form method="POST" class="verify-gcaptcha">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="email" class="form--label">@lang('Email Address')</label>
                            <input type="text" class="form--control" id="email" name="username" value="{{ old('username') }}" required>
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="your-password" class="form--label">@lang('Password')</label>
                            <div class="position-relative">
                                <input id="your-password" type="password" class="form-control form--control" name="password" required>
                                <span class="password-show-hide ti ti-eye toggle-password" id="#your-password"></span>
                            </div>
                        </div>

                        <x-captcha />

                        <div class="col-sm-12 form-group">
                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="form--check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">@lang('Remember me') </label>
                                </div>
                                <a href="{{ route('user.password.request.form') }}" class="forgot-password text--base">@lang('Forgot Your Password')?</a>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 form-group">
                            <button type="submit" class="btn btn--base w-100">{{ __($siteData?->data_info?->submit_button_text) }}</button>
                        </div>

                        <div class="col-sm-12">
                            <div class="have-account text-center">
                                <p class="have-account__text">@lang('Don\'t Have An Account')? <a href="{{ route('user.register') }}" class="have-account__link text--base fw-semibold">@lang('Create Account')</a></p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="account__img bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/login/' . $siteData?->data_info?->bg_image, '1920x1280') }}"></div>
    </section>
@endsection