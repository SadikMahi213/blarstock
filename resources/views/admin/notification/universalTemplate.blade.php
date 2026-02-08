@extends('admin.layouts.master')

@section('master')
    <div class="col-12 order-3">
        <div class="custom--card">
            <div class="card-header">
                <h3 class="title">@lang('Basic Configuration')</h3>
            </div>
            <div class="card-body">
                <form class="row g-lg-4 g-3"  action="" method="POST">
                    @csrf

                    <div class="col-lg-6 col-sm-6">
                        <label class="form--label required">@lang('Email Sender')</label>
                        <input type="email" class="form--control" name="email_from" value="{{ $setting->email_from }}" required>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <label class="form--label required">@lang('SMS Sender')</label>
                        <input type="text" class="form--control" name="sms_from" value="{{ $setting->sms_from }}" required>
                    </div>
                    <div class="col-12">
                        <div class="row g-4">
                            <div class="col-xl-6 col-lg-12 col-md-6">
                                <label class="form--label required">@lang('Email Body')</label>
                                <textarea class="form--control email-body-html" name="email_template" required>
                                    {{ $setting->email_template }}
                                </textarea>
                            </div>
                            <div class="col-xl-6 col-lg-12 col-md-6">
                                <label class="form--label">@lang('Email Template')</label>
                                <div class="custom--card email-body-output"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form--label required">@lang('SMS Body')</label>
                        <textarea class="form--control" name="sms_body" required>{{ $setting->sms_body }}</textarea>
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
    <div class="col-12 order-2">
        <table class="table table-borderless table--striped">
            <thead>
                <tr>
                    <th>@lang('Short Code')</th>
                    <th>@lang('Description')</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>@{{fullname}}</td>
                    <td>@lang('Full Name of User')</td>
                </tr>
                <tr>
                    <td>@{{username}}</td>
                    <td>@lang('Username of User')</td>
                </tr>
                <tr>
                    <td>@{{message}}</td>
                    <td>@lang('Message')</td>
                </tr>
                @foreach($setting->universal_shortcodes as $shortCode => $codeDetails)
                    <tr>
                        <td>@{{@php echo $shortCode @endphp}}</td>
                        <td>{{ __($codeDetails) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('page-script')
  <script>
    (function ($) {
        "use strict";
            if($('.email-body-html').length) {
                var htmlInput = $('textarea.email-body-html').val();
                var emailOutputDiv = $('div.email-body-output').get(0);
                if (!emailOutputDiv.shadowRoot) {
                emailOutputDiv.attachShadow({ mode: 'open' });
                }
                emailOutputDiv.shadowRoot.innerHTML = htmlInput;
                $('textarea.email-body-html').on('keyup', function(){
                var htmlInput = $(this).val();
                emailOutputDiv.shadowRoot.innerHTML = htmlInput;
                });
            }
        })(jQuery);
  </script>
@endpush
