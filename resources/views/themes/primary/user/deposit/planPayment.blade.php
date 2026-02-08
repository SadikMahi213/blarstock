@extends($activeTheme . 'layouts.auth')
@section('auth')
<div class="py-120">
    <div class="row g-4 justify-content-lg-between justify-content-center align-items-center">
        <div class="col-lg-5 col-md-7 col-sm-8 col-xsm-8">
            <div class="deposit__thumb">
                <img src="{{ getImage($activeThemeTrue . 'images/site/plan_payment/' . $siteData?->data_info?->image, '725x590') }}" alt="image">
            </div>
        </div>
        <div class="col-lg-6 col-md-10">
            <div class="custom--card">
                <div class="card-header">
                    <h3 class="title">@lang('Payment Methods')</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.deposit.insert') }}" method="POST" class="row g-3 align-items-center">
                        @csrf

                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <input type="hidden" name="type" value="payment">
                        <input type="hidden" name="currency">

                        <div class="col-12">
                            <label class="form--label">@lang('Total Amount')</label>
                            <div class="input--group">
                                <input type="number" step="any" class="form--control" name="amount" value="{{ getAmount($amount) }}" readonly required>
                                <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form--label mb-3">@lang('Select Payment Option')</label>
                            <select class="form--control form-select" name="gateway" required>
                                <option value="" selected disabled>@lang('Select One')</option>
                                @foreach($gatewayCurrency as $data)
                                    <option value="{{$data->method_code}}" @selected(old('gateway') == $data->method_code) data-gateway="{{ $data }}">{{$data->name}}</option>
                                @endforeach
                        </select>
                        </div>
                        <div class="col-12 preview-details d-none">
                            <table class="table table-borderless table-light">
                                <tbody>
                                    <tr>
                                        <td>@lang('Limit')</td>
                                        <td><span class="min fw-semibold">0</span> {{ __($setting->site_cur) }} - <span class="max fw-semibold">0</span> {{ __($setting->site_cur) }}</td>
                                    </tr>
                                    <tr>
                                        <td>@lang('Processing Charge') <span><i class="ti ti-help"></i></span></td>
                                        <td><span class="charge fw-semibold">0</span> {{ __($setting->site_cur) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>@lang('Total')</strong></td>
                                        <td><strong><span class="payable fw-semibold"></span> {{ __($setting->site_cur) }}</strong></td>
                                    </tr>
                                    <tr class="in-site-cur d-none">
                                        <td><strong>@lang('In') <span class="method_currency"></span></strong></td>
                                        <td><strong><span class="final_amo fw-semibold">0</span> {{ __($setting->site_cur) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 rate-element">

                        </div>
                        <div class="col-12 crypto-currency d-none">
                            <p class="small fw-semibold text-center">@lang('Conversion with') <span class="method_currency"></span> @lang('and final value will Show on next step')</p>
                        </div>
                        <div class="col-12">
                            <button class="btn btn--base w-100">@lang('Confirm Payment')</button>
                        </div>
                        <div class="col-12">
                            <p class="small fw-semibold text-center">@Lang('Select your preferred payment method for instant and secure plan activation.')</p>
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

            // $('select[name="gateway"]').on('change', function() {
            //     if (!$(this).val()) {
            //         $('.preview-details').addClass('d-none');
            //         return false;
            //     }

            //     let resource      = $(this).find('option:selected').data('gateway');
            //     let fixedCharge   = parseFloat(resource.fixed_charge);
            //     let percentCharge = parseFloat(resource.percent_charge);
            //     let rate          = parseFloat(resource.rate);
            //     let toFixedDigit = 0;

            //     if (resource.method.crypto == 1) {
            //         toFixedDigit = 8;
            //         $('.crypto_currency').removeClass('d-none');
            //     } else {
            //         toFixedDigit = 2;
            //         $('.crypto_currency').addClass('d-none');
            //     }

            //     $('.min').text(parseFloat(resource.min_amount).toFixed(2));
            //     $('.max').text(parseFloat(resource.max_amount).toFixed(2));

            //     let amount = parseFloat($('[name=amount]').val());

            //     if (!amount) {
            //         amount = 0;
            //     }

            //     if (amount <= 0) {
            //         $('.preview-details').addClass('d-none');
            //         return false;
            //     }

            //     $('.preview-details').removeClass('d-none');

            //     let charge = parseFloat(fixedCharge + (amount * percentCharge / 100)).toFixed(2);
            //     $('.charge').text(charge);

            //     let payable = parseFloat(parseFloat(amount) + parseFloat(charge)).toFixed(2);
            //     $('.payable').text(payable);

            //     let finalAmount = (payable * rate).toFixed(2);
            //     $('.final_amo').text(finalAmount);

            //     if (resource.currency != '{{ $setting->site_cur }}') {
            //         let rateElement = `<p class="small fw-semibold text-center">@lang('Conversion rate') <span class="fw-semibold">1 {{ __($setting->site_cur) }} = <span class="rate">${rate}</span> <span class="method_currency">${resource.currency}</span></span></p>`;
            //         $('.rate-element').html(rateElement);
            //         $('.rate-element').removeClass('d-none');
            //         $('.in-site-cur').removeClass('d-none');
            //     } else {
            //         $('.rate-element').html('');
            //         $('.rate-element').addClass('d-none');
            //         $('.in-site-cur').addClass('d-none');
            //     }

            //     $('.method_currency').text(resource.currency);
            //     $('[name=currency]').val(resource.currency);
            //     $('[name=amount]').trigger('input');
            // });

            // $('[name=amount]').on('input', function() {
            //     $('[name=gateway]').trigger('change');
            //     $('.amount').text(parseFloat($(this).val()).toFixed(2));
            // });

                const updatePaymentDetails = function() {
                    let gateway = $('select[name="gateway"]');

                    if (!gateway.val()) {
                        $('.preview-details').addClass('d-none');
                        return;
                    }

                    let resource = gateway.find('option:selected').data('gateway');

                    if (!resource) {
                        $('.preview-details').addClass('d-none');
                        $('.rate-element').addClass('d-none');
                        $('.crypto-currency').addClass('d-none');
                        
                        return;
                    }

                    let amount        = parseFloat($('[name=amount]').val()) || 0;
                    let fixedCharge   = parseFloat(resource.fixed_charge);
                    let percentCharge = parseFloat(resource.percent_charge);
                    let rate          = parseFloat(resource.rate);
                    let toFixedDigit  = resource.method.crypto == 1 ? 8 : 2;


                    $('.crypto-currency').toggleClass('d-none', resource.method.crypto != 1);
                    $('.min').text(parseFloat(resource.min_amount).toFixed(2));
                    $('.max').text(parseFloat(resource.max_amount).toFixed(2));

                    if (amount <= 0) {
                        $('.preview-details').addClass('d-none');
                        return;
                    }

                    $('.preview-details').removeClass('d-none');


                    let charge      = parseFloat(fixedCharge + (amount * percentCharge / 100)).toFixed(2);
                    let payable     = parseFloat(amount + parseFloat(charge)).toFixed(2);
                    let finalAmount = (payable * rate).toFixed(toFixedDigit);


                    $('.charge').text(charge);
                    $('.payable').text(payable);
                    $('.final_amo').text(finalAmount);


                    if (resource.currency != '{{ $setting->site_cur }}') {
                        let rateElement = `<p class="small fw-semibold text-center">@lang('Conversion rate') <span class="fw-semibold">1 {{ __($setting->site_cur) }} = <span class="rate">${rate}</span> <span class="method_currency">${resource.currency}</span></span></p>`;
                        $('.rate-element').html(rateElement).removeClass('d-none');
                        $('.in-site-cur').removeClass('d-none');
                    } else {
                        $('.rate-element').html('').addClass('d-none');
                        $('.in-site-cur').addClass('d-none');
                    }

                    $('.method_currency').text(resource.currency);
                    $('[name=currency]').val(resource.currency);
                };

                $('[name=amount]').on('input.amount', function() {
                    let val = parseFloat($(this).val()) || 0;
                    $('.amount').text(val.toFixed(2));
                    updatePaymentDetails();
                });

                $('select[name="gateway"]').on('change', updatePaymentDetails);

        })(jQuery);
    </script>
@endpush