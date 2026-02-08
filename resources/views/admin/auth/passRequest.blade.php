@extends('admin.layouts.app')
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
                        <img src="{{ asset('assets/admin/images/forgot.gif') }}" alt="Hi">
                    </div>
                    <h3 class="account__form__title">@lang('Forgot Password')?</h3>
                    <p>@lang('Let\'s make sure that it\'s you')</p>
                </div>
                <form class="verify-gcaptcha" action="" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label class="form--label">@lang('Email')</label>
                            <input type="email" class="form--control" name="email" placeholder="@lang('Enter your email')" required autofocus>
                        </div>

                        <x-captcha />

                        <div class="col-sm-12 form-group">
                            <button type="submit" class="btn btn--base w-100">@lang('Send')</button>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('admin.login') }}"><i class="ti ti-chevrons-left"></i> @lang('Back to login')</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
