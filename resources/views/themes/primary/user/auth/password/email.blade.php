@extends($activeTheme. 'layouts.app')
@section('content')
    <section class="account">
        <div class="account__form">
            <div class="account__form__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/forgot_password/' . $siteData?->data_info?->image, '1920x1080') }}"></div>
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
                            <label for="email" class="form--label">@lang('Email or Username')</label>
                            <input type="text" class="form--control" id="email" name="value" value="{{ old('value') }}" required>
                        </div>

                        <x-captcha />
                        
                        <div class="col-sm-12 form-group">
                            <button type="submit" class="btn btn--base w-100">{{ __($siteData?->data_info?->submit_button_text) }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="account__img bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/forgot_password/' . $siteData?->data_info?->bg_image, '1920x1280') }}"></div>
    </section>
@endsection
