@extends($activeTheme . 'layouts.frontend')
@section('frontend')
    @include($activeTheme . 'partials.breadcrumb')
    <div class="contact">
        <div class="container py-120">
            <div class="row g-0 justify-content-center">
                <div class="col-xl-3 col-md-2">
                    <div class="contact__thumb">
                        <img src="{{ getImage($activeThemeTrue . 'images/site/contact_us/' . $siteData?->data_info?->image, '700x700') }}" alt="image">
                    </div>
                </div>
                <div class="col-xl-9 col-md-10">
                    <div class="contact__card">
                        <div class="contact__card__sidebar h-auto">
                            <div class="contact__card__sidebar__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/contact_us/' . $siteData?->data_info?->bg_image, '500x750') }}"></div>
                            <ul class="contact__card__list">
                                <li>
                                    <h3 class="contact__card__list__title"><i class="ti ti-map-pin"></i> @lang('Address'):</h3>
                                    <div class="contact__card__list__content">{{ __($siteData?->data_info?->address) }}</div>
                                </li>
                                <li>
                                    <h3 class="contact__card__list__title"><i class="ti ti-mail"></i> @lang('Email'):</h3>
                                    <div class="contact__card__list__content">
                                        <span class="d-block">{{ $siteData?->data_info?->email }}</span>
                                    </div>
                                </li>
                                <li>
                                    <h3 class="contact__card__list__title"><i class="ti ti-phone"></i> @lang('Phone'):</h3>
                                    <div class="contact__card__list__content">
                                        <span class="d-block">{{ $siteData?->data_info?->contact_number }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="contact__card__form">
                            <form method="POST" class="row g-lg-4 g-3 verify-gcaptcha">
                                
                                @csrf
                                
                                <div class="col-12">
                                    <label for="fullName" class="form--label">@lang('Your Full Name') <span class="text--danger">*</span></label>
                                    <input type="text" id="fullName" class="form--control" name="name" value="{{ old('name', $user?->fullname) }}" @if ($user) readonly @endif required>
                                </div>
                                
                                <div class="col-12">
                                    <label for="emailAddress" class="form--label">@lang('Your Email') <span class="text--danger">*</span></label>
                                    <input type="email" id="emailAddress" class="form--control" name="email" value="{{ old('email', $user?->email) }}" @if ($user) readonly @endif required>
                                </div>
                                
                                <div class="col-12">
                                    <label for="subject" class="form--label">@lang('Subject') <span class="text--danger">*</span></label>
                                    <input type="text" id="subject" class="form--control" name="subject" value="{{ old('subject') }}" required>
                                </div>
                                
                                <div class="col-12">
                                    <label for="message" class="form--label">@lang('Message') <span class="text--danger">*</span></label>
                                    <textarea id="message" rows="10" class="form--control" name="message" required>{{ old('message') }}</textarea>
                                </div>
                                
                                <x-captcha />

                                <div class="col-12">
                                    <button type="submit" class="btn btn--base w-100">{{ __($siteData?->data_info?->submit_button_text) }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection