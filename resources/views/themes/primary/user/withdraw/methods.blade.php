@extends($activeTheme. 'layouts.auth')
@section('auth')
<div class="py-120">
    <div class="row g-4 justify-content-lg-between justify-content-center align-items-center">
         <div class="col-lg-5 col-md-7 col-sm-8 col-xsm-8">
              <div class="withdraw__thumb">
                   <img src="{{ getImage($activeThemeTrue . 'images/site/withdraw/' . $siteData?->data_info?->image, '725x730') }}" alt="image">
              </div>
         </div>
         <div class="col-xl-6 col-lg-7 col-md-10">
              <div class="custom--card">
                   <div class="card-header">
                        <h3 class="title">{{ __($pageTitle) }}</h3>
                   </div>
                   <div class="card-body">
                        <form method="POST" class="row g-4">
                            @csrf

                            <div class="col-12">
                                <label for="depositAmount" class="form--label">@lang('Amount') <span class="text--danger">*</span></label>
                                <div class="input--group">
                                    <input type="number" step="any" class="form--control" id="depositAmount" name="amount" value="{{ old('amount') }}" required>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label mb-3">@lang('Select Gateway Method')</label>
                                <select class="form-select form--control select-2" name="method_id" required>
                                    <option value="" disabled selected>@lang('Select Gateway')</option>
                                    @foreach ($methods as $data)
                                        <option value="{{ $data->id }}" data-resource="{{ $data }}"> {{ __($data->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 preview-details d-none">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-light no-shadow">
                                        <tbody>
                                                <tr>
                                                    <td>@lang('Limit')</td>
                                                    <td><span class="min fw-bold">0</span> {{ __($setting->site_cur) }} - <span class="max fw-bold">0</span> {{ __($setting->site_cur) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('Charge')</td>
                                                    <td><span class="charge fw-bold">0</span> {{ __($setting->site_cur) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('Receivable')</td>
                                                    <td><span class="receivable fw-bold">0</span> {{ __($setting->site_cur) }}</td>
                                                </tr>
                                                <tr class="in-site-cur d-none">
                                                    <td><strong>@lang('In') <span class="base_currency"></span></strong></td>
                                                    <td><strong><span class="finalAmount fw-semibold">0</span> {{ __($setting->site_cur) }}</strong></td>
                                                </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 rate-element">

                            </div>
                            <div class="col-12">
                                <button class="btn btn--base w-100">@lang('Request Withdraw')</button>
                            </div>
                            <div class="col-12">
                                <p class="small fw-semibold text-center">@lang('Securely Access Your Funds with Our Trusted Withdrawal Process')</p>
                            </div>
                        </form>
                   </div>
              </div>
         </div>
    </div>
</div>

@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            let toFixedDigit  = 2;

            $('[name=method_id]').on('change', function() {
                if (!$(this).val()) {
                    $('.preview-details').addClass('d-none');
                    return false;
                }

                let resource      = $(this).find('option:selected').data('resource');
                let fixedCharge   = parseFloat(resource.fixed_charge);
                let percentCharge = parseFloat(resource.percent_charge);
                let rate          = parseFloat(resource.rate);

                $('.min').text(parseFloat(resource.min_amount).toFixed(toFixedDigit));
                $('.max').text(parseFloat(resource.max_amount).toFixed(toFixedDigit));

                let amount = parseFloat($('[name=amount]').val());

                if (!amount) {
                    amount = 0;
                }

                if (amount <= 0) {
                    $('.preview-details').addClass('d-none');
                    return false;
                }

                $('.preview-details').removeClass('d-none');

                let charge = parseFloat(fixedCharge + (amount * percentCharge / 100)).toFixed(toFixedDigit);

                $('.charge').text(charge);

                if (resource.currency != '{{ $setting->site_cur }}') {
                    let rateElement = `<p class="small fw-semibold text-center">@lang('Conversion rate') <span class="fw-semibold">1 {{ __($setting->site_cur) }} = <span class="rate">${rate}</span> <span class="base_currency">${resource.currency}</span></span></p>`;

                    $('.rate-element').html(rateElement);
                    $('.rate-element').removeClass('d-none');
                    $('.in-site-cur').removeClass('d-none');
                } else {
                    $('.rate-element').html();
                    $('.rate-element').addClass('d-none');
                    $('.in-site-cur').addClass('d-none');
                }

                let receivable = (amount - charge).toFixed(toFixedDigit);

                $('.receivable').text(receivable);

                let finalAmount = (receivable * rate).toFixed(toFixedDigit);

                $('.finalAmount').text(finalAmount);
                $('.base-currency').text(resource.currency);
            });

            $('[name=amount]').on('input', function() {
                let data = $('[name=method_id]').trigger('change');
                $('.amount').text(parseFloat($(this).val()).toFixed(toFixedDigit));
            });
        })(jQuery);
    </script>
@endpush
