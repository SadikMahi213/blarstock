@extends($activeTheme. 'layouts.app')
@section('content')
    <section class="account">
        <div class="account__form">
            <div class="account__form__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/email_confirm/' . $siteData?->data_info?->image, '1920x1080') }}"></div>
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
                <form method="POST" action="{{ route('user.verify.email') }}" class="verification-code-form">
                    @csrf

                    <div class="row">

                        <div class="mb-4">
                            <p>@lang('A six-digit verification code has been emailed to you') :  {{ showEmailAddress(auth()->user()->email) }}</p>
                        </div>

                        @include('partials.verificationCode')
                        
                        <div class="col-sm-12 form-group mt-3">
                            <button type="submit" class="btn btn--base w-100">{{ __($siteData?->data_info?->submit_button_text) }}</button>
                        </div>

                        <div class="co-sm-12 form-group">
                            @lang('If you don\'t receive any code you can') <a href="{{ route('user.send.verify.code', 'email') }}">@lang('Send again')</a>
                        </div>

                        <div class="col-sm-12">
                            @if($errors->has('resend'))
                                <small class="text-danger d-block">{{ $errors->first('resend') }}</small>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="account__img bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/email_confirm/' . $siteData?->data_info?->bg_image, '1920x1280') }}"></div>
    </section>
@endsection
