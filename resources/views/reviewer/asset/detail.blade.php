@extends('reviewer.layouts.master')
@section('master')
    <div class="col-12">
        <form action="{{ route('reviewer.asset.update') }}" method="POST" class="assetStatusChangingForm">
            @csrf

            <input type="hidden" name="asset_id" value="{{ $asset->id }}" required>

            <div class="row g-4">
                <div class="col-xl-6">
                    <div class="custom--card">
                        <div class="card-header">
                            <h3 class="title">
                                @if ($asset->image_name)
                                    @lang('Image')
                                @else
                                    @lang("Video")
                                @endif
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="align-items-center">
                                        <div class="col-xxl-12 col-sm-12">
                                            <div class="reviewer-asset-preview">
                                                <div class="upload__img">
                                                    @if ($asset->video)
                                                        <video src="{{ videoFileUrl($asset->video) }}" controls autoplay muted loop></video>
                                                    @elseif ($asset->image_name)
                                                        <img src="{{ imageUrl(getFilePath('stockImage'), $asset->image_name) }}" alt="image">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="custom--card">
                        <div class="card-header">
                            <h3 class="title">@lang('Asset Contents')</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Author')</label>
                                            <input type="text" class="form--control" value="{{ __($asset->user->author_name) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Total View')</label>
                                            <input type="text" class="form--control" value="{{ __($asset->total_view) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Total Likes')</label>
                                            <input type="text" class="form--control" value="{{ __($asset->total_like) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Total Download')</label>
                                            <input type="text" class="form--control" value="{{ __($asset->total_download) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="custom--card">
                        <div class="card-header">
                            <h3 class="title">@lang('Information')</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Title')</label>
                                            <input type="text" class="form--control" value="{{ __($asset->title) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('File Type')</label>
                                            <input type="text" class="form--control" value="{{ __($asset->fileType->name) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Category')</label>
                                            <input type="text" class="form--control" value="{{ __($asset->category->name) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Extensions')</label>
                                            <select class="form--control form-select select-2" multiple data-tags="false" disabled>
                                                @foreach ($asset->extensions ?? [] as $extension)
                                                    <option selected>{{ __($extension) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Tags')</label>
                                            <select class="form--control form-select select-2" multiple data-tags="false" disabled>
                                                <option value="" disabled>@lang('Select One')</option>
                                                @foreach ($asset->tags ?? [] as $tag)
                                                    <option selected>{{ __($tag) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Description')</label>
                                            <textarea class="form--control" readonly>{{ $asset->description ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($asset->status == ManageStatus::IMAGE_PENDING)
                    <div class="col-xl-12">
                        <div class="custom--card">
                            <div class="card-header">
                                <h3 class="title">@lang('Action')</h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="row align-items-center gy-2">
                                            <div class="col-xxl-3 col-sm-4"><label class="col-form--label required">@lang('Status')</label></div>
                                            <div class="col-xxl-9 col-sm-8">
                                                <select class="form--control form-select" name="status" required>
                                                    <option value="" disabled>@lang('Select One')</option>
                                                    <option value="0" selected>@lang('Pending')</option>
                                                    <option value="1">@lang('Approved')</option>
                                                    <option value="2">@lang('Rejected')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($asset->imageFiles->isNotEmpty())
                    <div class="col-xl-12">
                        <div class="custom--card">
                            <div class="card-header">
                                <h3 class="title">@lang('Files')</h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach ($asset->imageFiles as $file)
                                        <div class="col-12 fileElement">
                                            <div class="row gy-3">
                                                <div class="col-3">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-xxl-12 col-sm-12">
                                                            <label class="form--label required">@lang('Resolution')</label>
                                                            <div class="input--group">
                                                                <input type="text" class="form--control" value="{{ $file->resolution }}" readonly>
                                                                <a href="{{ route('reviewer.asset.download', $file->id) }}" class="input-group-text">
                                                                    <span><i class="ti ti-download"></i></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-xxl-12 col-sm-12">
                                                            <label class="form--label required">@lang('Status')</label>
                                                            <input class="form--control" value="{{ $file->status == ManageStatus::ACTIVE ? trans('Enable') : trans('Disable') }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-xxl-12 col-sm-12">
                                                            <label class="form--label required">@lang('Premium')/@lang('Free')</label>
                                                            <input class="form--control" value="{{ $file->is_free == ManageStatus::FREE ? trans('Free') : trans('Premium') }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($file->is_free == ManageStatus::PREMIUM)
                                                    <div class="col-3">
                                                        <div class="row align-items-center gy-2">
                                                            <div class="col-xxl-12 col-sm-12">
                                                                <label class="form--label required">@lang('Price')</label>
                                                                <div class="input--group">
                                                                    <input class="form--control" value="{{ getAmount($file->price) }}" readonly>
                                                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-xl-12 reason @if ($asset->status != ManageStatus::IMAGE_REJECTED) d-none @endif">
                    <div class="custom--card">
                        <div class="card-header">
                            <h3 class="title">@lang('Rejection Reason')</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-3 col-sm-4"><label class="col-form--label required">@lang('Predefined Reason')</label></div>
                                        <div class="col-xxl-9 col-sm-8">
                                            <select class="form--control form-select select-2" id="predefinedReason">
                                                <option value="" disabled selected>@lang('Select One')</option>
                                                @foreach ($predefinedReasons as $reason)
                                                    <option value="{{ $reason->description }}">{{ __($reason->title) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-3 col-sm-4"><label class="col-form--label required">@lang('Reason')</label></div>
                                        <div class="col-xxl-9 col-sm-8">
                                            <textarea class="form--control" name="reason" @if ($asset->status == 3) required @endif>{{ __($asset->reason) }}</textarea>
                                        </div>
                                    </div>
                                </div>
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
    <a href="{{ route('admin.asset.index') }}" class="btn btn--sm btn--base"><i class="ti ti-circle-arrow-left"></i> @lang('Back')</a>
@endpush

@push('page-script')
    <script>
        (function($) {
            'use strict';

            // ___________________________________Extension for file type start____________________________________

            let selectedExtension = [
                @foreach($asset->extensions ?? [] as $i => $ext)
                    "{{ $ext }}"@if(!$loop->last),@endif
                @endforeach
            ]

            let fileTypeSelect    = $('select[name="file_type_id"]');
            let extensionSelect   = $('#addExtensionsSelect');
            let selectedFileType  = fileTypeSelect.find('option:selected');
            let defaultExtensions = selectedFileType.data('extensions') ?? [];

            populateExtensions(defaultExtensions, selectedExtension);

            fileTypeSelect.on('change', function() {
                let newExtensions = $(this).find('option:selected').data('extensions') ?? [];

                populateExtensions(newExtensions, []);
            });

            function populateExtensions(allExtensions, selectedExtensions) {
                let  html = `<option value="" disabled>@lang('Select Extensions')</option>`;

                if (allExtensions.length && selectedExtensions.length) {
                    allExtensions.forEach(extension => {
                        let isSelect = selectedExtensions.includes(extension) ? 'selected' : '';
                        html += `<option value="${extension}" ${isSelect}>${extension}</option>`;
                    });
                } else if (allExtensions.length && !selectedExtensions.length) {
                    allExtensions.forEach(extension => {
                        html += `<option value="${extension}">${extension}</option>`;
                    });
                } else if (!allExtensions.length && selectedExtensions.length) {
                    selectedExtensions.forEach(extension => {
                        html += `<option value="${extension}" selected>${extension}</option>`;
                    });
                }

                extensionSelect.html(html).trigger('change');
            }

            // ___________________________________Extension for file type end____________________________________

            // ___________________________________File price toggle start________________________________________
            $('.isFreeSelect').on('change', function() {
                let val = $(this).val();

                if (val == 1 ) {
                    $(this).closest('.fileElement').find('.price').addClass('d-none');
                } else if (val == 0) {
                    $(this).closest('.fileElement').find('.price').removeClass('d-none');
                }
            });
            // ___________________________________File price toggle end__________________________________________

            // __________________________________Predefined reject reason start__________________________________

            let rejectedStatus = '{{ ManageStatus::IMAGE_REJECTED }}';
            let reasonDiv      = $('.reason');

            $('select[name="status"]').on('change', function() {
                if ($(this).val() == rejectedStatus) {
                    reasonDiv.removeClass('d-none');
                } else {
                    reasonDiv.addClass('d-none');
                    $('[name=reason]').val('');
                }
            });

            $('#predefinedReason').on('change', function() {
                $('[name=reason]').val($(this).val());
            });
            // __________________________________Predefined reject reason end____________________________________

        })(jQuery);
    </script>
@endpush
