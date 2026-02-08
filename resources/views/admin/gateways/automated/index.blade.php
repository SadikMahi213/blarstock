@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="row g-lg-4 g-3">
            @foreach ($gateways as $gateway)
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="custom--card payment-method-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                        <div class="card-header text-center">
                            <h3 class="title">{{$loop->iteration}}. {{ __($gateway->name) }}</h3>
                        </div>
                        <div class="card-body">
                            <ul class="text-center fw-semibold mb-3">
                                <li><i class="ti ti-currency text--info"></i> @lang('Total Currency') : {{ collect($gateway->supported_currencies)->count() }}</li>
                                <li><i class="ti ti-circle-dashed-check text--success"></i> @lang('Active Currency') : {{ $gateway->currencies->count() }}</li>
                            </ul>
                            <div class="text-center">
                                @php echo $gateway->statusBadge @endphp
                            </div>
                            <div class="border-top pt-3 mt-3 d-flex justify-content-center align-items-center gap-2">
                                <a href="{{ route('admin.gateway.automated.edit', $gateway->alias) }}" class="btn btn--sm btn--base"><i class="ti ti-edit"></i> @lang('Edit')</a>

                                @if ($gateway->status)
                                    <button type="button" class="btn btn--sm btn--warning decisionBtn" data-question="@lang('Are you confirming the inactivation of this gateway')?" data-action="{{ route('admin.gateway.automated.status', $gateway->id) }}"><i class="ti ti-ban"></i> @lang('Inactive')</button>
                                @else
                                    <button type="button" class="btn btn--sm btn--success decisionBtn" data-question="@lang('Are you confirming the activation of this gateway')?" data-action="{{ route('admin.gateway.automated.status', $gateway->id) }}"><i class="ti ti-circle-check"></i> @lang('Active')</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <x-decisionModal />
@endsection
