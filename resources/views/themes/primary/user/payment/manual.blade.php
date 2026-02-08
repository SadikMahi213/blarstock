@extends($activeTheme. 'layouts.frontend')
@section('frontend')
    @include($activeTheme . 'partials.breadcrumb')
    
    <div class="container py-120">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="custom--card h-auto">
                    <div class="card-header">
                        <h5 class="title">{{__($pageTitle)}}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ auth()->check() ? route('user.deposit.manual.update') : route('donation.manual.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <p class="text-center mt-2">@lang('You have requested') <b class="text-success">{{ showAmount($deposit['amount'])  }} {{__($setting->site_cur)}}</b> , @lang('Please pay')
                                        <b class="text-success">{{showAmount($deposit['final_amo']) .' '.$deposit['method_currency'] }} </b> @lang('for successful payment')
                                    </p>
                                    <h4 class="text-center mb-4">@lang('Please follow the instruction below')</h4>

                                    <p class="my-4 text-center">@php echo  $deposit->gateway->guideline @endphp</p>

                                </div>

                                <x-phinix-form identifier="id" identifierValue="{{ $gateway->form_id }}" />

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn--base w-100">@lang('Pay Now')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

