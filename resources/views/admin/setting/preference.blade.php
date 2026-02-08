@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="custom--card">
            <div class="card-header">
                <h3 class="title">@lang('System Preferences')</h3>
            </div>
            <div class="card-body">
                <form class="row g-4" action="{{ route('admin.basic.system.setting') }}" method="POST">
                    @csrf
                    <div class="col-12">
                        <div class="row g-lg-4 g-3 row-cols-xxl-5 row-cols-xl-4 row-cols-md-3 row-cols-sm-2 row-cols-1 preference-card-list justify-content-center">
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-login"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('User Signup')</span>
                                        <span class="preference-card__desc">@lang('Enable or disable user registration with this toggle for your website. If deactivated, the option to create new accounts will be disabled').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox" name="signup" @if($setting->signup) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-lock"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('Enforce Strong Password')</span>
                                        <span class="preference-card__desc">@lang('Enhance account security by enforcing the use of strong passwords with this toggle, ensuring robust user authentication').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox" name="strong_pass" @if($setting->strong_pass) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-clipboard-text"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('Accept Policy')</span>
                                        <span class="preference-card__desc">@lang('Control user access by enabling this toggle, which mandates users to agree to your terms before accessing the website').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox"  name="agree_policy" @if($setting->agree_policy) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-user-scan"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('Know Your Customer Check')</span>
                                        <span class="preference-card__desc">@lang('Implement this toggle to require user identity verification, enhancing trust and compliance with regulatory standards on your website').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox" name="kc" @if($setting->kc) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-mail-check"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('Email Confirmation')</span>
                                        <span class="preference-card__desc">@lang('Ensure user authenticity by enabling this toggle, requiring users to verify their email addresses during the registration process').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox" name="ec" @if($setting->ec) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-mail-bolt"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('Email Alert')</span>
                                        <span class="preference-card__desc">@lang('Activate this toggle to notify users via email about important updates, events, and announcements on your website').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox" name="ea" @if($setting->ea) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-message-check"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('Mobile Confirmation')</span>
                                        <span class="preference-card__desc">@lang('Enhance user verification by enabling this toggle, which mandates users to confirm their identity via their mobiles during registration').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox" name="sc" @if($setting->sc) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-message-bolt"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('SMS Alert')</span>
                                        <span class="preference-card__desc">@lang('Activate this toggle to notify users via SMS about important updates, events, and announcements on your website').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox"  name="sa" @if($setting->sa) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-certificate"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('Enforce SSL')</span>
                                        <span class="preference-card__desc">@lang('Ensure data security by requiring all connections to your website to be encrypted using this toggle feature').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox" name="enforce_ssl" @if($setting->enforce_ssl) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                            <i class="ti ti-language"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('Language Preference')</span>
                                        <span class="preference-card__desc">@lang('Control user experience by activating this toggle, allowing visitors to select their preferred language for seamless interaction').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox" name="language" @if($setting->language) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-droplet"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('Watermark')</span>
                                        <span class="preference-card__desc">@lang('The watermark image will now be applied to all uploaded images, making them branded with the watermark for better protection').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox" name="watermark" @if($setting->watermark) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-settings-automation"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('Auto Approval')</span>
                                        <span class="preference-card__desc">@lang('With auto-approval enabled, all uploaded assets are approved instantly without manual review').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox" name="auto_approval" @if($setting->auto_approval) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-heart-handshake"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('Donation')</span>
                                        <span class="preference-card__desc">@lang('Enable donation to activate donation features. Configure the donation settings to start receiving donations').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox" name="donation" @if($setting->donation) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-bell-share"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('Asset Approval Notification')</span>
                                        <span class="preference-card__desc">@lang('Keep your audience connected! Enable approval notifications so followers get updates when their favorite authors publish approved assets').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox" name="asset_approval_notify" @if($setting->asset_approval_notify) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="preference-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                    <div class="preference-card__thumb">
                                        <i class="ti ti-checklist"></i>
                                    </div>
                                    <div class="preference-card__content">
                                        <span class="preference-card__title">@lang('Reviewer Action Permission')</span>
                                        <span class="preference-card__desc">@lang('Activating this feature will grant selected reviewers permission to approve or reject pending assets').</span>
                                    </div>
                                    <div class="form-check form--switch">
                                        <input class="form-check-input" type="checkbox" name="reviewer_action_permission" @if($setting->reviewer_action_permission) checked @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-center">
                            <button class="btn btn--base px-4" type="submit">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection