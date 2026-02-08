@extends($activeTheme. 'layouts.auth')
@section('auth')
    <div class="py-120">
        <div class="row g-4 justify-content-lg-between justify-content-center align-items-center">
            <div class="col-lg-5 col-md-7 col-sm-8 col-xsm-8">
                <div class="change-password__thumb">
                    <img src="{{ getImage($activeThemeTrue . 'images/site/change_password/' . $siteData?->data_info?->image, '725x785') }}" alt="image">
                </div>
            </div>
            <div class="col-lg-6 col-md-10">
                <div class="custom--card">
                    <div class="card-header">
                        <h3 class="title">@lang('Secure Your Account')</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="currentPassword" class="form--label">@lang('Current Password') <span class="text--danger">*</span></label>
                                <input type="password" id="currentPassword" class="form--control" name="current_password" required autocomplete="current-password">
                            </div>
                            <div class="form-group">
                                <label for="newPassword" class="form--label">@lang('New Password') <span class="text--danger">*</span></label>
                                <input type="password" id="newPassword" class="form--control @if ($setting->strong_pass) secure-password @endif" name="password" required autocomplete="current-password">
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword" class="form--label">@lang('Confirm Password') <span class="text--danger">*</span></label>
                                <input type="password" id="confirmPassword" class="form--control" name="password_confirmation" required autocomplete="current-password">
                            </div>
                            <button class="btn btn--base w-100">{{ __($siteData?->data_info?->submit_button_text) }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@if ($setting->strong_pass)
    @push('page-style-lib')
        <link rel="stylesheet" href="{{ asset('assets/universal/css/strongPassword.css') }}">
    @endpush

    @push('page-script-lib')
        <script src="{{asset('assets/universal/js/strongPassword.js')}}"></script>
    @endpush
@endif