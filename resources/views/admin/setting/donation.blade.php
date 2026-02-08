@extends('admin.layouts.master')
@section('master')
    <div class="col-12">
        <form action="{{ route('admin.basic.donation.setting.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-xl-12">
                    <div class="custom--card">
                        <div class="card-header">
                            <h3 class="title">@lang('Donation Configuration')</h3>
                        </div>
                        <div class="card-body">
                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-lg-4">
                                            <label class="col-form--label required">@lang('Donation Item')</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form--control" name="item" value="{{ $setting->donation_setting?->item ?? '' }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-lg-4">
                                            <label class="col-form--label required">@lang('Subtitle')</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form--control" name="subtitle" value="{{ $setting->donation_setting?->subtitle ?? '' }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-lg-4">
                                            <label class="col-form--label required">@lang('Icon')</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input--group">
                                                <input type="text" class="form--control iconPicker icon" name="icon" autocomplete="off" value="{{ $setting->donation_setting?->icon ?? '' }}" required>
                                                <span class="input-group-text input-group-addon" data-icon="ti ti-home" role="iconpicker"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-lg-4">
                                            <label class="col-form--label required">@lang('Amount')</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input--group">
                                                <input type="number" step="any" min="0" class="form--control" name="amount" value="{{ $setting->donation_setting?->amount ?? old('amount') }}" required>
                                                <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>        
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="custom--card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="title">@lang('Donation Unit Count')</h3>
                            <button type="button" class="btn btn--sm btn--base amountGenerateBtn"><i class="ti ti-circle-plus"></i> @lang('Add')</button>
                        </div>
                        <div class="card-body">
                            <div class="row gy-3 amount-headers">
                                @if (isset($setting->donation_setting->unit))
                                    @foreach ($setting->donation_setting->unit as $key => $value)
                                        <div class="col-4 amount-col">
                                            <div class="row">
                                                <div class="col-10">
                                                    <div class="input--group">
                                                        <input type="number" step="any" min="0" class="form--control" name="unit[{{ $key }}}]" value="{{ $value }}" required>
                                                        <span class="input-group-text">@lang('Unit')</span>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <button type="button" class="btn btn--danger px-2 delete-amount-btn input-group-text"><i class="ti ti-x"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <button class="btn btn--base px-4" type="submit">@lang('Submit')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('breadcrumb')
@endpush

@push('page-style')
    <style>
        .iconpicker-popover.fade {
            opacity: 1;
        }
    </style>
@endpush

@push('page-style-lib')
    <link href="{{ asset('assets/admin/css/page/iconpicker.css') }}" rel="stylesheet">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/admin/js/page/iconpicker.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            'use strict';

            $('.iconPicker').iconpicker().on('iconpickerSelected', function (e) {
                $(this).closest('.input--group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
            });

            $('.amountGenerateBtn').on('click', function() {
                let index = $('.amount-col').length;

                $('.amount-headers').append(`
                    <div class="col-4 amount-col">
                        <div class="row">
                            <div class="col-10">
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="unit[${index}]" value="0" required>
                                    <span class="input-group-text">@lang('Unit')</span>
                                </div>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn--danger px-2 delete-amount-btn input-group-text"><i class="ti ti-x"></i></button>
                            </div>
                        </div>
                    </div>
                `);
            });

            $(document).on('click', '.delete-amount-btn', function() {
                $(this).closest('.amount-col').remove();
            });

        })(jQuery);
    </script>
@endpush