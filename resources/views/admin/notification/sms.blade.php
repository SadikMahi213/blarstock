@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="custom--card">
            <form action="" method="POST">
                @csrf
                <div class="card-body">
                    <label class="form--label required">@lang('SMS Delivery Methods')</label>
                    <select class="form--control form-select" name="sms_method" required>
                        <option value="nexmo" @selected($setting->sms_config?->name == 'nexmo')>@lang('Nexmo')</option>
                        <option value="twilio" @selected($setting->sms_config?->name == 'twilio')>@lang('Twilio')</option>
                        <option value="custom" @selected($setting->sms_config?->name == 'custom')>@lang('Custom API')</option>
                    </select>
                </div>
                
                <div class="card-body border-top">
                    <div class="row g-4">
                        <div class="col-12 configForm" id="nexmo">
                            <div class="custom--card">
                                <div class="card-header">
                                    <h3 class="title">@lang('Nexmo Config')</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="row align-items-center gy-2">
                                                <div class="col-xxl-3 col-lg-3 col-md-3">
                                                    <label class="col-form--label required">@lang('API Key')</label>
                                                </div>
                                                <div class="col-xxl-9 col-lg-9 col-md-9">
                                                    <input type="text" class="form--control" name="nexmo_api_key" value="{{ $setting->sms_config?->nexmo->api_key }}" placeholder="@lang('Nexmo app key')">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row align-items-center gy-2">
                                                <div class="col-xxl-3 col-lg-3 col-md-3">
                                                    <label class="col-form--label required">@lang('API Secret')</label>
                                                </div>
                                                <div class="col-xxl-9 col-lg-9 col-md-9">
                                                    <input type="text" class="form--control" name="nexmo_api_secret" value="{{ $setting->sms_config?->nexmo->api_secret }}" placeholder="@lang('Nexmo api secret key')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 configForm" id="twilio">
                            <div class="custom--card">
                                <div class="card-header">
                                    <h3 class="title">@lang('Twilio Config')</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="row align-items-center gy-2">
                                                <div class="col-xxl-3 col-lg-3 col-md-3">
                                                    <label class="col-form--label required">@lang('Account SID')</label>
                                                </div>
                                                <div class="col-xxl-9 col-lg-9 col-md-9">
                                                    <input type="text" class="form--control" name="account_sid" value="{{ $setting->sms_config?->twilio->account_sid }}" placeholder="@lang('Account SID')">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row align-items-center gy-2">
                                                <div class="col-xxl-3 col-lg-3 col-md-3">
                                                    <label class="col-form--label required">@lang('Auth Token')</label>
                                                </div>
                                                <div class="col-xxl-9 col-lg-9 col-md-9">
                                                    <input type="text" class="form--control" name="auth_token" value="{{ $setting->sms_config?->twilio->auth_token }}" placeholder="@lang('Auth token')">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row align-items-center gy-2">
                                                <div class="col-xxl-3 col-lg-3 col-md-3">
                                                    <label class="col-form--label required">@lang('From Number')</label>
                                                </div>
                                                <div class="col-xxl-9 col-lg-9 col-md-9">
                                                    <input type="text" class="form--control" name="from" value="{{ $setting->sms_config?->twilio->from }}" placeholder="@lang('From Number')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mt-0 configForm" id="custom">
                        <div class="col-12">
                            <h3 class="card-subtitle">@lang('Custom API Config')</h3>
                            <table class="table table-borderless table--striped">
                                    <thead>
                                        <tr>
                                            <th>@lang('Short Code')</th>
                                            <th>@lang('Description')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>@{{message}}</td>
                                            <td>@lang('Message')</td>
                                        </tr>
                                        <tr>
                                            <td>@{{number}}</td>
                                            <td>@lang('Number')</td>
                                        </tr>
                                    </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <label class="form--label">@lang('API URL')</label>
                            <div class="input--group">
                                <select class="form--control form-select w-25" name="custom_api_method" required>
                                    <option value="get">@lang('GET')</option>
                                    <option value="post">@lang('POST')</option>
                                </select>
                                <input type="url" class="form--control w-75" name="custom_api_url" value="{{ $setting->sms_config?->custom->url }}" placeholder="@lang('API URL')">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="custom--card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="title">@lang('Headers')</h3>
                                    <button type="button" class="btn btn--sm btn--base add-sms-header-btn"><i class="ti ti-circle-plus"></i> @lang('Add New')</button>
                                </div>
                                <div class="card-body">
                                    <div class="row gy-3 sms-notification-headers">
                                        @for($i = 0; $i < count($setting->sms_config->custom->headers->name); $i++)
                                            <div class="col-12 sms-notification-headers-col">
                                                <div class="input--group">
                                                    <input type="text" class="form--control" name="custom_header_name[]" value="{{ $setting->sms_config?->custom->headers->name[$i] }}" placeholder="@lang('Headers Name')" required>
                                                    <input type="text" class="form--control" name="custom_header_value[]" value="{{ $setting->sms_config?->custom->headers->value[$i] }}" placeholder="@lang('Headers Value')" required>
                                                    <button type="button" class="btn btn--danger px-2 delete-sms-header-btn"><i class="ti ti-x"></i></button>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="custom--card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="title">@lang('Body')</h3>
                                    <button type="button" class="btn btn--sm btn--base add-sms-body-btn"><i class="ti ti-circle-plus"></i> @lang('Add New')</button>
                                </div>
                                <div class="card-body">
                                    <div class="row gy-3 sms-notification-body">
                                        @for($i = 0; $i < count($setting->sms_config->custom->body->name); $i++)
                                            <div class="col-12 sms-notification-body-col">
                                                <div class="input--group">
                                                    <input type="text" class="form--control" name="custom_body_name[]" value="{{ $setting->sms_config?->custom->body->name[$i] }}" placeholder="@lang('Body Name')" required>
                                                    <input type="text" class="form--control" name="custom_body_value[]" value="{{ $setting->sms_config?->custom->body->value[$i] }}" placeholder="@lang('Body Value')" required>
                                                    <button type="button" class="btn btn--danger px-2 delete-sms-body-btn"><i class="ti ti-x"></i></button>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-top">
                    <div class="d-flex justify-content-center">
                        <button class="btn btn--base px-4" type="submit">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="testSMSModal" tabindex="-1" aria-labelledby="testSMSModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="testSMSModalLabel">@lang('Test SMS Send')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="{{ route('admin.notification.sms.test') }}" method="POST">
                    @csrf
                        <div class="modal-body">
                            <label class="form--label required">@lang('Sent to')</label>
                            <input type="text" class="form--control form--control--sm" name="mobile" placeholder="@lang('Mobile number')" required>
                        </div>
                        <div class="modal-footer gap-2">
                            <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                            <button class="btn btn--sm btn--base" type="submit">@lang('Send')</button>
                        </div>
                    </form>
                </div>
            </div>
       </div>
    </div>
@endsection

@push('breadcrumb')
    <button type="button" class="btn btn--sm btn--base" data-bs-target="#testSMSModal" data-bs-toggle="modal">
        <span class="ti ti-message"></span> @lang('Test SMS')
    </button>
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $('select[name=custom_api_method]').val('{{ $setting->sms_config?->custom->method }}');
            var method = '{{ $setting->sms_config->name }}';

            smsMethod(method);
            $('select[name=sms_method]').on('change', function() {
                var method = $(this).val();
                smsMethod(method);
            });

            function smsMethod(method){
                $('.configForm').addClass('d-none');
                if(method != 'php') {
                    $(`#${method}`).removeClass('d-none');
                }
            }

            $('.add-sms-header-btn').on('click', function(){
                $('.sms-notification-headers').append(`<div class="col-12 sms-notification-headers-col">
                <div class="input--group">
                    <input type="text" class="form--control" name="custom_header_name[]" placeholder="@lang('Headers Name')" required>
                    <input type="text" class="form--control" name="custom_header_value[]" placeholder="@lang('Headers Value')" required>
                    <button type="button" class="btn btn--danger px-2 delete-sms-header-btn"><i class="ti ti-x"></i></button>
                </div>
                </div>`);
            });

            $(document).on('click', '.delete-sms-header-btn', function(){
                $(this).closest('.sms-notification-headers-col').remove();
            });

            $('.add-sms-body-btn').on('click', function(){
                $('.sms-notification-body').append(`<div class="col-12 sms-notification-body-col">
                <div class="input--group">
                    <input type="text" class="form--control" name="custom_body_name[]" placeholder="@lang('Body Name')" required>
                    <input type="text" class="form--control" name="custom_body_value[]" placeholder="@lang('Body Value')" required>
                    <button type="button" class="btn btn--danger px-2 delete-sms-body-btn"><i class="ti ti-x"></i></button>
                </div>
                </div>`);
            });

            $(document).on('click', '.delete-sms-body-btn', function(){
                $(this).closest('.sms-notification-body-col').remove();
            });
        })(jQuery);
    </script>
@endpush
