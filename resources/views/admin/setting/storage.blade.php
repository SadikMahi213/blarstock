@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <form action="{{ route('admin.storage.update') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-12">
                    <div class="alert alert--base">
                        @lang('Please exercise caution when altering storage settings or switching FTP hosts. Changing these settings may impact the visibility of your images and files on the site. Before making any changes, ensure that all directories containing uploaded images and files are properly transferred to the new FTP or local storage location. For instance, when switching from Local to FTP storage, make sure to copy the directories (e.g., "images" and "files") from the previous location to the new FTP storage (such as assets/images/stock/image and assets/images/stock/file). Failure to do so may result in missing images and files on the site').
                    </div>
                </div>

                <div class="col-12">
                    <div class="custom--card">
                        <div class="card-header">
                            <h3 class="title">@lang('Select Storage')</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-lg-12">
                                    <div class="row align-items-center">
                                        <div class="col-lg-4">
                                            <label class="col-form--label required">@lang('Storage')</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form--control form-select" name="storage_type" required>
                                                <option value="1" @selected($setting->storage_type == ManageStatus::LOCAL_STORAGE)>@lang('Local Storage')</option>
                                                <option value="2" @selected($setting->storage_type == ManageStatus::FTP_STORAGE)>@lang('FTP Storage')</option>
                                                <option value="3" @selected($setting->storage_type == ManageStatus::WASABI_STORAGE)>@lang('Wasabi Storage')</option>
                                                <option value="4" @selected($setting->storage_type == ManageStatus::DIGITAL_OCEAN_STORAGE)>@lang('Digital Ocean')</option>
                                                <option value="5" @selected($setting->storage_type == ManageStatus::VULTR_STORAGE)>@lang('Vultr')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 storage-content">
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

@push('page-script')
    <script>
        (function($) {
            'use strict';

            $('[name=storage_type]').on('change', function() {
                let storageType         = Number($(this).val());
                let localStorage        = '{{ ManageStatus::LOCAL_STORAGE }}';
                let ftpStorage          = '{{ ManageStatus::FTP_STORAGE }}';
                let wasabiStorage       = '{{ ManageStatus::WASABI_STORAGE }}';
                let digitalOceanStorage = '{{ ManageStatus::DIGITAL_OCEAN_STORAGE }}';
                let vultrStorage        = '{{ ManageStatus::VULTR_STORAGE }}';

                if (storageType == localStorage) {
                    $('.storage-content').children().remove();
                } else if (storageType == ftpStorage) {
                    let ftp = `<div class="custom--card">
                                    <div class="card-header">
                                        <h3 class="title">@lang('Complete the FTP Storage Configuration')</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <div class="row align-items-center gy-2">
                                                    <div class="col-lg-4">
                                                        <label class="col-form--label required">@lang('FTP Hosting Root Access Path')</label>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form--control" name="ftp[host_domain]" value="{{ $setting->ftp?->host_domain ?? '' }}" placeholder="@lang('Domain/Folder Name')" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row align-items-center gy-2">
                                                    <div class="col-lg-4">
                                                        <label class="col-form--label required">@lang('Host')</label>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form--control" name="ftp[host]" value="{{ $setting->ftp?->host ?? '' }}" placeholder="@lang('Host')" required>
                                                    </div>
                                                </div>
                                            </div><div class="col-lg-6">
                                                <div class="row align-items-center gy-2">
                                                    <div class="col-lg-4">
                                                        <label class="col-form--label required">@lang('Username')</label>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form--control" name="ftp[username]" value="{{ $setting->ftp?->username ?? '' }}" placeholder="@lang('Username')" required>
                                                    </div>
                                                </div>
                                            </div><div class="col-lg-6">
                                                <div class="row align-items-center gy-2">
                                                    <div class="col-lg-4">
                                                        <label class="col-form--label required">@lang('Password')</label>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form--control" name="ftp[password]" value="{{ $setting->ftp?->password ?? '' }}" placeholder="@lang('Password')" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row align-items-center gy-2">
                                                    <div class="col-lg-4">
                                                        <label class="col-form--label required">@lang('Port')</label>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form--control" name="ftp[port]" value="{{ $setting->ftp?->port ?? '' }}" placeholder="@lang('Port')" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row align-items-center gy-2">
                                                    <div class="col-lg-4">
                                                        <label class="col-form--label required">@lang('Upload Root Folder')</label>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form--control" name="ftp[root_path]" value="{{ $setting->ftp?->root_path ?? '' }}" placeholder="@lang('/html_public/something')" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;

                    $('.storage-content').html(ftp);
                } else if (storageType == wasabiStorage) {
                    $('.storage-content').children().remove();
                    let wasabi = `<div class="custom--card">
                                        <div class="card-header">
                                            <h3 class="title">@lang('Complete the Wasabi Storage Configuration')</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-4">
                                                <div class="col-lg-6">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-lg-4">
                                                            <label class="col-form--label required">@lang('Driver')</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form--control" name="wasabi[driver]" value="{{ $setting->wasabi?->driver ?? '' }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-lg-4">
                                                            <label class="col-form--label required">@lang('Key')</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form--control" name="wasabi[key]" value="{{ $setting->wasabi?->key ?? '' }}" required>
                                                        </div>
                                                    </div>
                                                </div><div class="col-lg-6">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-lg-4">
                                                            <label class="col-form--label required">@lang('Secret')</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form--control" name="wasabi[secret]" value="{{ $setting->wasabi?->secret ??'' }}" required>
                                                        </div>
                                                    </div>
                                                </div><div class="col-lg-6">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-lg-4">
                                                            <label class="col-form--label required">@lang('Region')</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form--control" name="wasabi[region]" value="{{ $setting->wasabi?->region ?? '' }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-lg-4">
                                                            <label class="col-form--label required">@lang('Bucket')</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form--control" name="wasabi[bucket]" value="{{ $setting->wasabi?->bucket ?? '' }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-lg-4">
                                                            <label class="col-form--label required">@lang('Endpoint')</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form--control" name="wasabi[endpoint]" value="{{ $setting->wasabi?->endpoint ?? '' }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;

                    $('.storage-content').html(wasabi);
                } else if (storageType == digitalOceanStorage) {
                    $('.storage-content').children().remove();
                    let digitalOcean = `<div class="custom--card">
                                            <div class="card-header">
                                                <h3 class="title">@lang('Complete the Digital Ocean Storage Configuration')</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-4">
                                                    <div class="col-lg-6">
                                                        <div class="row align-items-center gy-2">
                                                            <div class="col-lg-4">
                                                                <label class="col-form--label required">@lang('Driver')</label>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <input type="text" class="form--control" name="digital_ocean[driver]" value="{{ $setting->digital_ocean?->driver ?? '' }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="row align-items-center gy-2">
                                                            <div class="col-lg-4">
                                                                <label class="col-form--label required">@lang('Key')</label>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <input type="text" class="form--control" name="digital_ocean[key]" value="{{ $setting->digital_ocean?->key ?? '' }}" required>
                                                            </div>
                                                        </div>
                                                    </div><div class="col-lg-6">
                                                        <div class="row align-items-center gy-2">
                                                            <div class="col-lg-4">
                                                                <label class="col-form--label required">@lang('Secret')</label>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <input type="text" class="form--control" name="digital_ocean[secret]" value="{{ $setting->digital_ocean?->secret ?? '' }}" required>
                                                            </div>
                                                        </div>
                                                    </div><div class="col-lg-6">
                                                        <div class="row align-items-center gy-2">
                                                            <div class="col-lg-4">
                                                                <label class="col-form--label required">@lang('Region')</label>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <input type="text" class="form--control" name="digital_ocean[region]" value="{{ $setting->digital_ocean?->region ?? '' }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="row align-items-center gy-2">
                                                            <div class="col-lg-4">
                                                                <label class="col-form--label required">@lang('Bucket')</label>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <input type="text" class="form--control" name="digital_ocean[bucket]" value="{{ $setting->digital_ocean?->bucket ?? '' }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="row align-items-center gy-2">
                                                            <div class="col-lg-4">
                                                                <label class="col-form--label required">@lang('Endpoint')</label>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <input type="text" class="form--control" name="digital_ocean[endpoint]" value="{{ $setting->digital_ocean?->endpoint ?? ''}}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;

                    $('.storage-content').html(digitalOcean);
                } else if (storageType == vultrStorage) {
                    $('.storage-content').children().remove();
                    let vultr = `<div class="custom--card">
                                        <div class="card-header">
                                            <h3 class="title">@lang('Complete the Vultr Storage Configuration')</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-4">
                                                <div class="col-lg-6">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-lg-4">
                                                            <label class="col-form--label required">@lang('Driver')</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form--control" name="vultr[driver]" value="{{ $setting->vultr?->driver ?? '' }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-lg-4">
                                                            <label class="col-form--label required">@lang('Key')</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form--control" name="vultr[key]" value="{{ $setting->vultr?->key ?? '' }}" required>
                                                        </div>
                                                    </div>
                                                </div><div class="col-lg-6">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-lg-4">
                                                            <label class="col-form--label required">@lang('Secret')</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form--control" name="vultr[secret]" value="{{ $setting->vultr?->secret ?? '' }}" required>
                                                        </div>
                                                    </div>
                                                </div><div class="col-lg-6">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-lg-4">
                                                            <label class="col-form--label required">@lang('Region')</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form--control" name="vultr[region]" value="{{ $setting->vultr?->region ?? '' }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-lg-4">
                                                            <label class="col-form--label required">@lang('Bucket')</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form--control" name="vultr[bucket]" value="{{ $setting->vultr?->bucket ?? '' }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-lg-4">
                                                            <label class="col-form--label required">@lang('Endpoint')</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form--control" name="vultr[endpoint]" value="{{ $setting->vultr?->endpoint ?? '' }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;

                    $('.storage-content').html(vultr);
                }
            }).change();

        })(jQuery);
    </script>
@endpush