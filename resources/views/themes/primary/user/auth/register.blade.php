@extends($activeTheme. 'layouts.app')
@section('content')
    @php
        $policyPages = getSiteData('policy_pages.element', false, null, true);
    @endphp

    <section class="account">
        <div class="account__form">
            <div class="account__form__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/register/' . $siteData?->data_info?->image, '1920x1080') }}"></div>
            <div class="account__form__container">
                <div class="account__top d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <a href="{{ route('home') }}"><img src="{{ getImage(getFilePath('logoFavicon') . '/logo_dark.png') }}" alt="Logo"></a>
                    </div>
                    <a href="{{ route('home') }}" class="back-to-home"><i class="ti ti-home"></i></a>
                </div>
                <div class="account__form__content">
                    <h3 class="account__form__title">{{ __($siteData?->data_info?->heading) }}</h3>
                    <p>{{ __($siteData?->data_info?->subheading) }}</p>
                </div>
                <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha">
                    @csrf
                    <div class="row g-3">
                        @if (session()->get('reference') != null)
                            <div class="col-xl-12 col-md-12 col-sm-6">
                                <label for="referenceBy" class="form--label">@lang('Reference by') <span class="text--danger">*</span></label>
                                <input type="text" class="form--control" id="referenceBy" name="referBy" value="{{session()->get('reference')}}" readonly>
                            </div>    
                        @endif
                        
                        <div class="col-xl-6 col-md-12 col-sm-6">
                            <label for="firstName" class="form--label">@lang('First Name') <span class="text--danger">*</span></label>
                            <input type="text" class="form--control" id="firstName" name="firstname" value="{{ old('firstname') }}" required>
                        </div>
                        
                        <div class="col-xl-6 col-md-12 col-sm-6">
                            <label for="lastName" class="form--label">@lang('Last Name') <span class="text--danger">*</span></label>
                            <input type="text" class="form--control" id="lastName" name="lastname" value="{{ old('lastname') }}" required>
                        </div>
                        
                        <div class="col-xl-6 col-md-12 col-sm-6">
                            <label for="username" class="form--label">@lang('Username') <span class="text--danger">*</span></label>
                            <input type="text" class="form--control checkUser" id="username" name="username" value="{{ old('username') }}" required>
                            <small class="text-danger usernameExist"></small>
                        </div>
                        
                        <div class="col-xl-6 col-md-12 col-sm-6">
                            <label for="email" class="form--label">@lang('Email Address') <span class="text--danger">*</span></label>
                            <input type="email" class="form--control checkUser" id="email" name="email" value="{{ old('email') }}" required>
                            <small class="text-danger emailExist"></small>
                        </div>
                        
                        <div class="col-xl-6 col-md-12 col-sm-6">
                            <label for="country" class="form--label">@lang('Country') <span class="text--danger">*</span></label>
                            <select id="country" name="country" class="form--control form-select" required>
                                @foreach($countries as $key => $country)
                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-xl-6 col-md-12 col-sm-6">
                            <label for="phoneNumber" class="form--label">@lang('Phone') <span class="text--danger">*</span></label>
                            <div class="input--group">
                                <span class="input-group-text input-group-text-light mobile-code"></span>
                                
                                <input type="hidden" name="mobile_code">
                                <input type="hidden" name="country_code">
                                <input type="number" id="phoneNumber" class="form--control checkUser" name="mobile" required>
                            </div>
                            <small class="text-danger mobileExist"></small>
                        </div>
                        
                        <div class="col-xl-6 col-md-12 col-sm-6">
                            <label for="your-password" class="form--label">@lang('Password') <span class="text--danger">*</span></label>
                            <div class="position-relative">
                                <input id="your-password" type="password" class="form-control form--control @if ($setting->strong_pass) secure-password @endif" name="password" required>
                                <span class="password-show-hide ti ti-eye toggle-password" id="#your-password"></span>
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-md-12 col-sm-6">
                            <label for="confirm-password" class="form--label">@lang('Confirm Password') <span class="text--danger">*</span></label>
                            <div class="position-relative">
                                <input id="confirm-password" type="password" class="form-control form--control" name="password_confirmation" required>
                                <span class="password-show-hide ti ti-eye toggle-password" id="#confirm-password"></span>
                            </div>
                        </div>
                        
                        <x-captcha />

                        @if ($setting->agree_policy)
                            <div class="col-sm-12">
                                <div class="form--check">
                                    <input class="form-check-input" type="checkbox" id="remember" @checked(old('agree')) name="agree" required>
                                    <label class="form-check-label w-auto" for="remember">@lang('I agree with') 
                                        @foreach ($policyPages as $policy)
                                            <a href="{{ route('policy.pages',[slug($policy->data_info->title),$policy->id]) }}" target="_blank">{{ __($policy->data_info->title) }}</a> @if(!$loop->last), @endif
                                        @endforeach
                                    </label>
                                </div>
                            </div>
                        @endif
                        
                        <div class="col-sm-12">
                            <button type="submit" id="recaptcha" class="btn btn--base w-100">{{ __($siteData?->data_info?->submit_button_text) }}</button>
                        </div>
                        
                        <div class="col-sm-12">
                            <div class="have-account text-center">
                                <p class="have-account__text">@lang('Already Have An Account')? <a href="{{ route('user.login.form') }}" class="have-account__link text--base fw-semibold">@lang('Sign In')</a> @lang('Here')</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="account__img bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/register/' . $siteData?->data_info?->bg_image, '1920x1280') }}"></div>
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

@push('page-script')
    <script>
        "use strict";

        (function ($) {
            @if($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected','');
            @endif

            $('[name=country]').on('change', function() {
                $('[name=mobile_code]').val($('[name=country] :selected').data('mobile_code'));
                $('[name=country_code]').val($('[name=country] :selected').data('code'));
                $('.mobile-code').text('+'+$('[name=country] :selected').data('mobile_code'));
            });

            $('[name=mobile_code]').val($('[name=country] :selected').data('mobile_code'));
            $('[name=country_code]').val($('[name=country] :selected').data('code'));
            $('.mobile-code').text('+'+$('[name=country] :selected').data('mobile_code'));

            $('.checkUser').on('focusout',function(e) {
                var url = '{{ route('user.check.user') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {mobile:mobile,_token:token}
                }

                if ($(this).attr('name') == 'email') {
                    var data = {email:value,_token:token}
                }

                if ($(this).attr('name') == 'username') {
                    var data = {username:value,_token:token}
                }

                $.post(url, data, function(response) {
                  if (response.data != false && (response.type == 'email' || response.type == 'username' || response.type == 'mobile')) {
                    $(`.${response.type}Exist`).text(`${response.type} already exist`);
                  }else{
                    $(`.${response.type}Exist`).text('');
                  }
                });
            });
        })(jQuery);
  </script>
@endpush
