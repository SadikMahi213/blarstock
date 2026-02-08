@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="custom--card">
            <div class="card-header">
                <h3 class="title">@lang('Site Preferences')</h3>
            </div>
            <div class="card-body">
                <form class="row g-lg-4 g-3" action="" method="POST">
                    @csrf
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Site Name')</label>
                        <input type="text" class="form--control" name="site_name" value="{{ $setting->site_name }}" placeholder="@lang('Tona Admin Template')" required>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Platform Currency')</label>
                        <input type="text" class="form--control" name="site_cur" value="{{ $setting->site_cur }}" placeholder="@lang('USD')" required>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Currency Symbol')</label>
                        <input type="text" class="form--control" name="cur_sym" value="{{ $setting->cur_sym }}" placeholder="$" required>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Time Region')</label>
                        <select class="form--control form-select select-2" name="time_region" required>
                            @foreach($timeRegions as $timeRegion)
                                <option value="'{{ $timeRegion}}'">{{ __($timeRegion) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Item Showing Per Page')</label>
                        <select class="form--control form-select" name="per_page_item" required>
                            <option value="20">20 @lang('item per page')</option>
                            <option value="50">50 @lang('item per page')</option>
                            <option value="100">100 @lang('item per page')</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Date Formats')</label>
                        <select class="form--control form-select" name="date_format" required>
                            <option value="m-d-Y">MDY (Month-Day-Year)</option>
                            <option value="d-m-Y">DMY (Day-Month-Year)</option>
                            <option value="Y-m-d">YMD (Year-Month-Day)</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Fractional Digit Show')</label>
                        <input type="text" class="form--control" name="fraction_digit" value="{{ $setting->fraction_digit }}" placeholder="2" required>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Primary Color')</label>
                        <div class="input--group colorpicker">
                            <input type="color" class="form--control" value="#{{ $setting->first_color }}">
                            <input type="text" class="form--control" name="first_color" value="#{{ $setting->first_color }}" placeholder="@lang('Hex Code e.g. #00ffff')" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Secondary Color')</label>
                        <div class="input--group colorpicker">
                            <input type="color" class="form--control" value="#{{ $setting->second_color }}">
                            <input type="text" class="form--control" name="second_color" value="#{{ $setting->second_color }}" placeholder="@lang('Hex Code e.g. #ffff00')" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Daily Upload Limit')</label>
                        <input type="text" class="form--control" name="daily_upload_limit" value="{{ $setting->daily_upload_limit }}" placeholder="4" required>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Maximum Price Limit')</label>
                        <div class="input--group">
                            <input type="number" step="any" min="0" class="form--control" name="max_price_limit" value="{{ getAmount($setting->max_price_limit) }}" required>
                            <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Tag Limit Per Asset')</label>
                        <input type="number" min="0" class="form--control" name="tag_limit_per_asset" value="{{ $setting->tag_limit_per_asset }}" placeholder="10" required>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Author\'s Commission')</label>
                        <div class="input--group">
                            <input type="number" step="any" min="0" class="form--control" name="authors_commission" value="{{ getAmount($setting->authors_commission) }}" placeholder="45" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label class="form--label required">@lang('Max Referral Level Show')</label>
                        <div class="input--group">
                            <input type="number" min="0" class="form--control" name="max_referral_level" value="{{ getAmount($setting->max_referral_level) }}" placeholder="5" required>
                            <span class="input-group-text">@lang('Level')</span>
                        </div>
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
    
    <div class="col-12">
        <div class="custom--card">
            <div class="card-header">
                <h3 class="title">@lang('Logo and Favicon Preferences')</h3>
            </div>
            <div class="card-body">
                <form class="row g-lg-4 g-3"  action="{{ route('admin.basic.logo.favicon.setting') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-12">
                        <div class="alert alert--base">
                            @lang('If the visual identifiers remain unchanged, it\'s advisable to perform a cache clearance within your browser. Typically, clearing the cache resolves this issue. However, if the previous logo or favicon persists, it could be attributed to caching mechanisms at the server or network level. Additional cache clearance may be necessary in such cases').
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label for="logoLight" class="form--label">@lang('Logo Light')</label>
                        <div class="upload__img">
                            <label for="logoLight" class="upload__img__btn"><i class="ti ti-camera"></i></label>
                            <input type="file" id="logoLight" class="image-upload" name="logo_light" accept=".png">
                            <label for="logoLight" class="upload__img-preview image-preview">
                                <img src="{{ getImage(getFilePath('logoFavicon').'/logo_light.png') }}" alt="logo">
                            </label>
                            <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none"><i class="ti ti-circle-x"></i></button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label for="logoDark" class="form--label">@lang('Logo Dark')</label>
                        <div class="upload__img">
                            <label for="logoDark" class="upload__img__btn"><i class="ti ti-camera"></i></label>
                            <input type="file" id="logoDark" class="image-upload" name="logo_dark" accept=".png">
                            <label for="logoDark" class="upload__img-preview image-preview">
                                <img src="{{ getImage(getFilePath('logoFavicon').'/logo_dark.png') }}" alt="logo">
                            </label>
                            <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none"><i class="ti ti-circle-x"></i></button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <label for="favicon" class="form--label">@lang('Favicon')</label>
                        <div class="upload__img">
                            <label for="favicon" class="upload__img__btn"><i class="ti ti-camera"></i></label>
                            <input type="file" id="favicon" class="image-upload" name="favicon" accept=".png">
                            <label for="favicon" class="upload__img-preview image-preview">
                                <img src="{{ getImage(getFilePath('logoFavicon').'/favicon.png', getFileSize('favicon')) }}" alt="logo">
                            </label>
                            <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none"><i class="ti ti-circle-x"></i></button>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-center">
                            <button class="btn btn--base px-4">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('page-script')
  <script>
    (function ($) {
        "use strict";

        $('.colorpicker').find('input').on('keyup', function(){
            var colorCode = $(this).val();
            $(this).siblings('input').val(colorCode);
        });

        $('.colorpicker').find('input[type=color]').on('input', function(){
            var colorCode = $(this).val();
            $(this).siblings('input').val(colorCode);
        });

        $('[name=per_page_item]').val('{{ bs('per_page_item') }}');
        $('[name=date_format]').val('{{ bs('date_format')  }}');
        $('[name=time_region]').val("'{{ config('app.timezone') }}'").select2();
    })(jQuery);
  </script>
@endpush
