@extends('admin.layouts.master')
@section('master')
    <div class="col-12">
        <form action="{{ route('admin.asset.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="asset_id" value="{{ $asset->id }}" required>
            <input type="hidden" name="type" value="{{ $asset->type }}" required>
            
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
                                            <input type="text" class="form--control" value="{{ $asset->user->author_name }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Total View')</label>
                                            <input type="text" class="form--control" value="{{ $asset->total_view }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Total Likes')</label>
                                            <input type="text" class="form--control" value="{{ $asset->total_like }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Total Download')</label>
                                            <input type="text" class="form--control" value="{{ $asset->total_download }}" readonly>
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
                                            <input type="text" class="form--control" name="title" value="{{ $asset->title }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('File Type')</label>
                                            <select class="form--control form-select select-2" name="file_type_id">
                                                @foreach ($fileTypes as $fileType)
                                                    <option value="{{ $fileType->id }}"
                                                        @if ($fileType->id == $asset->file_type_id) selected @endif
                                                        data-extensions="{{ json_encode($fileType->supported_file_extension ?? []) }}"
                                                        data-type="{{ $fileType->type }}">
                                                            {{ __($fileType->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Category')</label>
                                            <select class="form--control form-select select-2" name="category_id">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @if ($category->id == $asset->category_id) selected @endif>{{ __($category->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Colors')</label>
                                            <select class="form--control form-select select-2" multiple data-tags="false" name="colors[]">
                                                <option value="" disabled>@lang('Select One')</option>
                                                @foreach($colors as $color)
                                                    <option value="{{ $color->code }}" @if (in_array($color->code, $asset->colors ?? [])) selected @endif>{{ __($color->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Tags')</label>
                                            <select class="form--control form-select select-2" multiple data-tags="false" name="tags[]" required>
                                                <option value="" disabled>@lang('Select One')</option>
                                                @foreach ($asset->tags ?? [] as $tag)
                                                    <option value="{{ $tag }}" selected>{{ __($tag) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12">
                                            <label class="form--label required">@lang('Extensions')</label>
                                            <select class="form--control form-select select-2" multiple data-tags="false" name="extensions[]" id="addExtensionsSelect" required></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-12 col-sm-12 editor-wrapper">
                                            <label class="form--label required">@lang('Description')</label>
                                            <textarea class="form--control trumEdit" name="description">{{ $asset->description ?? '' }}</textarea>
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
                                                <option value="0" @if ($asset->status == ManageStatus::IMAGE_PENDING) selected @endif>@lang('Pending')</option>
                                                <option value="1" @if ($asset->status == ManageStatus::IMAGE_APPROVED) selected @endif>@lang('Approved')</option>
                                                <option value="2" @if ($asset->status == ManageStatus::IMAGE_REJECTED) selected @endif>@lang('Rejected')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                                                                <input type="text" class="form--control" name="resolution[]" value="{{ $file->resolution }}" required>
                                                                <input type="hidden" name="file_id[]" value="{{ $file->id }}">
                                                                <a href="{{ route('admin.asset.download', $file->id) }}" class="input-group-text">
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
                                                            <select class="form--control form-select" name="status_file[{{ $loop->index }}]">
                                                                <option value="" disabled>@lang('Select One')</option>
                                                                <option value="1" @if ($file->status == ManageStatus::ACTIVE) selected @endif>@lang('Enable')</option>
                                                                <option value="0" @if ($file->status == ManageStatus::INACTIVE) selected @endif>@lang('Disable')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-xxl-12 col-sm-12">
                                                            <label class="form--label required">@lang('Premium')/@lang('Free')</label>
                                                            <select class="form--control form-select isFreeSelect" name="is_free[{{ $loop->index }}]">
                                                                <option value="" disabled>@lang('Select One')</option>
                                                                <option value="1" @if ($file->is_free == ManageStatus::FREE) selected @endif>@lang('Free')</option>
                                                                <option value="0" @if ($file->is_free == ManageStatus::PREMIUM) selected @endif>@lang('Premium')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-3 price @if ($file->is_free == ManageStatus::FREE) d-none @endif">
                                                    <div class="row align-items-center gy-2">
                                                        <div class="col-xxl-12 col-sm-12">
                                                            <label class="form--label required">@lang('Price')</label>
                                                            <div class="input--group">
                                                                <input type="number" step="any" min="0" class="form--control assetPrice" name="price[]" value="{{ $file->price ? showAmount($file->price) : '' }}" @if (!$file->is_free) required @endif>
                                                                <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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

@push('page-script-lib')
    <script src="{{asset('assets/admin/js/page/ckEditor.js')}}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            'use strict';

            if ($(".trumEdit")[0]) {
                $('.editor-wrapper').find('.ck-editor').remove();
                window.editors = {};
                document.querySelectorAll('.trumEdit').forEach((node, index) => {
                    ClassicEditor
                    .create(node)
                    .then(newEditor => {
                        window.editors[index] = newEditor;
                    });
                });
            }

            // ___________________________________Extension for file type start____________________________________

            let selectedExtension = [
                @foreach($asset->extensions ?? [] as $i => $ext)
                    "{{ $ext }}"@if(!$loop->last),@endif
                @endforeach
            ];

            let fileTypeSelect    = $('select[name="file_type_id"]');
            let extensionSelect   = $('#addExtensionsSelect');
            let selectedFileType  = fileTypeSelect.find('option:selected');
            let defaultExtensions = selectedFileType.data('extensions') ?? [];

            populateExtensions(defaultExtensions, selectedExtension);

            fileTypeSelect.on('change', function() {
                let newExtensions = $(this).find('option:selected').data('extensions') ?? [];
                let type          = $(this).find('option:selected').data('type');

                $('[name=type]').val(type);

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
                    $(this).closest('.fileElement').find('.price').find('.assetPrice').val('0');
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
                }
            });

            $('#predefinedReason').on('change', function() {
                $('[name=reason]').val($(this).val());
            });
            // __________________________________Predefined reject reason end____________________________________

        })(jQuery);
    </script>
@endpush
