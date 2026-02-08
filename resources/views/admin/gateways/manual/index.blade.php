@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="row g-lg-4 g-3">
            @forelse ($methods as $method)
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="custom--card payment-method-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                        <div class="card-header text-center">
                            <h3 class="title">{{$loop->iteration}}. {{ __($method->name) }}</h3>
                        </div>
                        <div class="card-body">
                            <ul class="text-center fw-semibold mb-3">
                                <li>
                                    <i class="ti ti-currency text--info"></i> @lang('Currency') : {{ __($method->singleCurrency->currency) }}
                                </li>
                                <li>
                                    <i class="ti ti-lock-access text--primary"></i> @lang('Limit') : {{ $method->singleCurrency->min_amount + 0 }} - {{ $method->singleCurrency->max_amount + 0 }} {{__($setting->site_cur) }}
                                </li>
                                <li>
                                    <i class="ti ti-percentage text--warning"></i> @lang('Charge') : {{ showAmount($method->singleCurrency->fixed_charge)}} {{__($setting->site_cur) }} {{ (0 < $method->singleCurrency->percent_charge) ? ' + '. showAmount($method->singleCurrency->percent_charge) .' %' : '' }}
                                </li>
                            </ul>
                            <div class="text-center">
                                @php echo $method->statusBadge @endphp
                            </div>
                            <div class="border-top pt-3 mt-3 d-flex justify-content-center align-items-center gap-2">
                                <a href="{{ route('admin.gateway.manual.edit', $method->id) }}" class="btn btn--sm btn--base"><i class="ti ti-edit"></i> @lang('Edit')</a>

                                @if ($method->status)
                                    <button type="button" class="btn btn--sm btn--warning decisionBtn" data-question="@lang('Are you confirming the inactivation of this method')?" data-action="{{ route('admin.gateway.manual.status', $method->id) }}"><i class="ti ti-ban"></i> @lang('Inactive')</button>
                                @else
                                    <button type="button" class="btn btn--sm btn--success decisionBtn" data-question="@lang('Are you confirming the activation of this method')?" data-action="{{ route('admin.gateway.manual.status', $method->id) }}"><i class="ti ti-circle-check"></i> @lang('Active')</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    @include('partials.noData')
                </div>
            @endforelse
        </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.gateway.manual.new') }}" class="btn btn--sm btn--base"><i class="ti ti-circle-plus"></i> @lang('Add New')</a>
@endpush
