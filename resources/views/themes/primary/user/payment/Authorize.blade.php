@extends($activeTheme . 'layouts.frontend')
    @section('frontend')
        @include($activeTheme . 'partials.breadcrumb')

        <div class="container py-120">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card custom--card">
                        <div class="card-header">
                            <h5>@lang('Authorize Net')</h5>
                        </div>
                        <div class="card-body">
                            <div class="card-wrapper mb-3"></div>
                            <form role="form" id="payment-form" method="{{ $data->method }}" action="{{ $data->url }}">
                                @csrf
                                <input type="hidden" value="{{$data->track}}" name="track">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('Name on Card')</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form--control" name="name" value="{{ old('name') }}" required autocomplete="off" autofocus>
                                            <span class="input-group-text"><i class="ti ti-user-star"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('Card Number')</label>
                                        <div class="input-group">
                                            <input type="tel" class="form-control form--control" name="cardNumber" autocomplete="off" value="{{ old('cardNumber') }}" required autofocus>
                                            <span class="input-group-text"><i class="ti ti-credit-card-pay"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('Expiration Date')</label>
                                        <input type="tel" class="form-control form--control" name="cardExpiry" value="{{ old('cardExpiry') }}" autocomplete="off" required>
                                    </div>
                                    <div class="col-md-6 ">
                                        <label class="form-label">@lang('CVC Code')</label>
                                        <input type="tel" class="form-control form--control" name="cardCVC" value="{{ old('cardCVC') }}" autocomplete="off" required>
                                    </div>
                                </div>
                                <br>
                                <button class="btn btn--base w-100" type="submit"> @lang('Submit')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('page-script-lib')
        <script src="{{asset('assets/universal/js/card.js')}}"></script>
    @endpush

    @push('page-script')
        <script>
            (function ($) {
                "use strict";

                var card = new Card({
                    form: '#payment-form',
                    container: '.card-wrapper',
                    formSelectors: {
                        numberInput: 'input[name="cardNumber"]',
                        expiryInput: 'input[name="cardExpiry"]',
                        cvcInput: 'input[name="cardCVC"]',
                        nameInput: 'input[name="name"]'
                    }
                });
            })(jQuery);
        </script>
    @endpush
