@extends('reviewer.layouts.app')
@section('content')
    <section class="account bg-img" data-background-image="{{ asset('assets/admin/images/account-bg.png') }}">
        <div class="account__form">
            <div class="account__form__container">
                <div class="account__top d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <a href="{{ route('home') }}" target="_blank"><img src="{{ getImage(getFilePath('logoFavicon').'/logo_dark.png') }}" alt="Logo"></a>
                    </div>
                </div>
                <div class="account__form__content">
                    <div class="account__form__thumb">
                        <img src="{{ asset('assets/admin/images/key.gif') }}" alt="Hi">
                    </div>
                    <h3 class="account__form__title">@lang('Code Verification')</h3>
                    <p>@lang('Please check your email to get the 6 digits verification code')</p>
                </div>
                <form class="verification-code-form" action="" method="POST">
                    @csrf

                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="row">
                        <div class="col-sm-12 form-group">
                            @include('partials.verificationCode')
                        </div>
                        <div class="col-sm-12 form-group">
                            <button type="submit" class="btn btn--base w-100">@lang('Verify')</button>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('reviewer.password.request.form') }}">@lang('Send again')?</a>
                                <a href="{{ route('reviewer.login') }}"><i class="ti ti-chevrons-left"></i> @lang('Back to login')</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection