@extends($activeTheme. 'layouts.auth')
@section('auth')

    <div class="py-120">
        <div class="custom--card">
             <div class="card-header">
                  <h3 class="title">@lang('Two-factor Authentication')</h3>
             </div>
             <div class="card-body">
                  <div class="row g-4">
                       <div class="col-lg-6">
                            <div class="two-fa-setting">
                                 <p>@lang('Two factor authentication provides extra protection for your account by requiring a special code').</p>

                                 @if (!auth()->user()->ts)
                                    <p><strong>@lang('Note'):</strong> @lang('You are only activating two factor authentication for the main password')</p>                                     
                                 @else
                                    <p><strong>@lang('Note'):</strong> @lang('You are disabling two-factor authentication and it will have no effect when you login.')</p>
                                 @endif
                                 
                                 <p>@lang('Have a smart phone')? @lang('Use Google Authenticator')</p>
                                 
                                 <div class="download-app">
                                      <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en">
                                           <img src="{{ asset($activeThemeTrue . 'images/google-play.png') }}" alt="Google Play">
                                      </a>
                                      <a href="https://apps.apple.com/us/app/google-authenticator/id388497605">
                                           <img src="{{ asset($activeThemeTrue . 'images/app-store.png') }}" alt="Google Play">
                                      </a>
                                 </div>
                                 <p class="fw-semibold text--secondary"><em><small>@lang('Android'), @lang('Google Play and the Google Play logo are trademarks of Google Inc Apple and the Apple logo are trademarks of Apple registered in the US and zaher coumes App Store is a service mark of Apple Inc.')</small></em></p>
                                 
                                 @if (!auth()->user()->ts)
                                    <p>@lang('To enable two factor authentication'), @lang('scan the QR code on the right using Google Authenticator'). @lang('When you have successfully scanned the QR code'), @lang('enter the token from Google Authenticator into the') "@lang('verify token')" @lang('field'). @lang('We make sure you can generate tokens correctly before enabling two factor auth').</p>                                     
                                 @endif
                            </div>
                       </div>
                       <div class="col-lg-6">

                            @if (!auth()->user()->ts)
                                <div class="alert alert--base">
                                    <span class="alert__content w-100 ps-0"><small><strong>@lang('Use the QR code or setup key on your Google Authenticator app to add your account').</strong></small></span>
                                </div>
                                <div class="qr-code-img">
                                    <img src="{{ $qrCodeUrl }}" alt="QR code">
                                </div>
                                <div class="account-setup-key">
                                    <div class="form-group mb-4">
                                        <label class="form--label">@lang('Setup Key')</label>
                                        <div class="input--group referral-link">
                                            <input type="text" class="form--control" id="accountSetupKey" value="{{ $secret }}" readonly>
                                            <button class="btn btn--base account-setup-key__copy"><i class="ti ti-copy"></i></button>
                                        </div>
                                    </div>
                                    <form class="verification-code-form" action="{{ route('user.twofactor.enable') }}" method="POST">
                                        @csrf

                                        <label class="form--label" for="authenticatorOtp">@lang('Google Authenticator OTP') <span class="text--danger">*</span></label>
                                        <input type="hidden" id="authenticatorOtp" class="form--control mb-3" name="key" value="{{ $secret }}" required>

                                        @include('partials.verificationCode')

                                        <button type="submit" class="btn btn--base w-100 mt-3">@lang('Submit')</button>
                                    </form>
                                </div>
                            @else
                                <div class="alert alert--base">
                                    <span class="alert__content w-100 ps-0"><small><strong>@lang('To disable two factor authentication'), @lang('enter the token from Google Authenticator into the') "@lang('Google Authenticator OTP')" @lang('field').'</strong></small></span>
                                </div>

                                <div class="account-setup-key mt-4">
                                    <form class="verification-code-form" action="{{ route('user.twofactor.disable') }}" method="POST">
                                        @csrf
                                        <label class="form--label required" for="authenticatorOtp">@lang('Google Authenticator OTP')</label>
                                        <input type="hidden" name="key" value="{{ $secret }}">

                                        @include('partials.verificationCode')

                                        <button class="btn btn--base w-100 mt-3" type="submit">@lang('Submit')</button>
                                    </form>
                               </div>
                            @endif
                       </div>
                  </div>
             </div>
        </div>
   </div>
@endsection

@push('page-style')
    <style>
        .copied::after {
            background-color: #{{ $setting->first_color }};
        }
    </style>
@endpush

@push('page-script')
    <script>
        (function($){
            "use strict";
            
            $('#copyBoard').click(function(){
                var copyText = document.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });
        })(jQuery);
    </script>
@endpush