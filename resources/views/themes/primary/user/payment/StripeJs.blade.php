@extends($activeTheme. 'layouts.frontend')
@section('frontend')
    @include($activeTheme . 'partials.breadcrumb')
    
    <div class="container py-120">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="custom--card h-auto">
                    <div class="card-header">
                        <h5 class="title">@lang('Stripe Storefront')</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{$data->url}}" method="{{$data->method}}">
                            @csrf
                            <ul class="list-group text-center">
                                <li class="list-group-item d-flex justify-content-between">
                                    @lang('You have to pay '):
                                    <strong>{{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    @lang('You will get '):
                                    <strong>{{showAmount($deposit->amount)}}  {{__($setting->site_cur)}}</strong>
                                </li>
                            </ul>
                            <script src="{{$data->src}}"
                                class="stripe-button"
                                @foreach($data->val as $key=> $value)
                                data-{{$key}}="{{$value}}"
                                @endforeach
                            >
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script-lib')
    <script src="https://js.stripe.com/v3/"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";
            $('button[type="submit"]').removeClass().addClass("btn btn--base w-100 mt-3").text("Pay Now");
        })(jQuery);
    </script>
@endpush