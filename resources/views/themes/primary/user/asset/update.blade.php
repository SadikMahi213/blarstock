@extends($activeTheme . 'layouts.auth')
@section('auth')
    <div class="py-120">
        <div class="row g-4">
            <div class="col-12">
                <div class="custom--card border--base">
                    <div class="card-body text-center guideline-txt">
                        <p class="h3 fw-bold">{{ __($setting->instruction?->heading ?? '') }}</p>
                        <p>@php echo $setting->instruction?->instruction ?? ''; @endphp</p>
                        <p class="fw-semibold">@lang('Click below to download the license file').</p>
                        <a href="{{ route('user.author.manual.download') }}" class="btn btn--base">@lang('Download Now')</a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="custom--card">
                    <div class="card-header">
                        <h3 class="title">@lang('Update Your Asset')</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.asset.store', $image->id) }}" method="POST" class="row g-4" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="type" value="{{ $image->type }}">

                            <div class="col-lg-5">
                                <label for="fileInput" class="dropzone" tabindex="0" data-drag-text="@lang('Drop Here')...">
                                    <span class="dropzone__icon"><i class="ti ti-upload"></i></span>
                                    <span class="dropzone__txt">@lang('Choose'), @lang('or drag and drop a file here')</span>
                                    <span class="dropzone__preview active">
                                        <img src="{{ imageUrl(getFilePath('stockImage'), $image->image_name) }}" alt="@lang('Image')">
                                    </span>
                                </label>
                                <span class="small fw-semibold pt-2 note-for-image">@lang('Supported files'): <span class="fw-bold text--base">.@lang('png')</span>, <span class="fw-bold text--base">.@lang('jpg')</span>, <span class="fw-bold text--base">.@lang('jpeg')</span></span>
                                <input type="file" id="fileInput" class="d-none" name="photo"  accept=".png, .jpg, .jpeg">
                            </div>
                            <div class="col-lg-7">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form--label required">@lang('Asset Title')</label>
                                        <input type="text" class="form--control" name="title" value="{{ $image->title }}" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label required">@lang('Asset Type')</label>
                                        <select id="assetType" class="form--control form--select select-2" name="file_type_id" required data-placeholder="@lang('Select one')">
                                            <option value="">@lang('Select one')</option>
                                            @foreach ($fileTypes as $fileType)
                                                <option value="{{ $fileType->id }}"
                                                    @if ($image->file_type_id == $fileType->id) selected @endif 
                                                    data-extensions="{{ json_encode($fileType->supported_file_extension ?? []) }}"
                                                    data-type="{{ $fileType->type }}">
                                                    {{ __($fileType->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label required">@lang('Category')</label>
                                        <select class="form--control form--select select-2" name="category" required data-placeholder="@lang('Select one')">
                                            <option value="">@lang('Select one')</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @if ($image->category_id == $category->id) selected @endif>{{ __($category->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label">@lang('Colors')</label>
                                        <select class="form--control form--select select-2" name="colors[]" multiple data-tags="false" data-placeholder="@lang('Select colors')">
                                            <option value="">@lang('Select Colors')</option>
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->code }}" @if (in_array($color->code, $image->colors ?? [])) selected @endif>{{ __($color->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label required">@lang('Extensions')</label>
                                        <select class="form--control form--select select-2" id="addExtensionSelect" name="extensions[]" required multiple data-tags="false">

                                        </select>
                                    </div>
                                    <div class="col-12 videoDiv @if (!$image->video && $image->type == ManageStatus::IMAGE) d-none @endif">
                                        <label class="form--label">@lang('Video') (@lang('Supported files:') <span class="fw-bold text--base">@lang('.mp4'), @lang('.3gp')</span>)</label>
                                        <input type="file" class="form--control" name="video" accept=".mp4, .3gp">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Tags') (@lang('maximum') <span class="text--base">{{ __($setting->tag_limit_per_asset) }}</span> @lang('tags'))</label>
                                <select class="form--control form--select select-2" name="tags[]" required multiple data-tags="true" data-placeholder="@lang('Enter keywords')">
                                    <option value="">@lang('Enter keywords')</option>
                                    @foreach ($mergedTags as $tag)
                                        <option value="{{ $tag }}" @if (is_array($image->tags) && in_array($tag, $image->tags)) selected @endif>{{ $tag }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 editor-wrapper">
                                <label class="form--label required">@lang('Description')</label>
                                <textarea class="form--control trumEdit" name="description">{{ $image->description ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <div class="custom--card border--dark-subtle removedFile">
                                    <div class="pricing-set-card">
                                        @forelse ($image->imageFiles ?? [] as $file)
                                            <div class="card-body">
                                                <div class="card-subtitle d-flex justify-content-between align-items-center">
                                                    <span class="pricing-set-card__title">@lang('Pricing Set') - {{ $loop->iteration }}</span>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <a href="{{ route('user.asset.download', $file->id) }}" class="btn btn--sm btn--base"><i class="ti ti-download"></i> @lang('Download File')</a>
                                                        <button type="button" class="btn btn--sm btn--danger py-1 remove-pricing-set"
                                                            data-id="{{ $file->id }}">
                                                            <i class="ti ti-circle-minus"></i> @lang('Remove')
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row g-4">
                                                    <div class="col-sm-6">
                                                        <label class="form--label">@lang('Resolution') <span class="text--base small lh-1" title="@lang('Separate width & height by x'), @lang('e.g.') 600x1200"><i class="ti ti-help-circle"></i></span></label>
                                                        <div class="input--group">
                                                            <input type="text" class="form--control" name="old_file[{{ $file->id }}][resolution]" value="{{ $file->resolution }}" readonly placeholder="@lang('E.g. 600x1200')">
                                                            <span class="input-group-text">@lang('PX')</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form--label required">@lang('Compressed File')</label>
                                                        <input type="file" class="form--control" name="old_file[{{ $file->id }}][file]" accept="{{ getArchiveExtensions() }}">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form--label">@lang('Status')</label>
                                                        <select class="form--control form--select select-2" name="old_file[{{ $file->id }}][status]" data-search="false">
                                                            <option value="1" @if ($file->status == 1) selected @endif>@lang('Enable')</option>
                                                            <option value="0" @if ($file->status == 0) selected @endif>@lang('Disable')</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 licenseDiv">
                                                        <label class="form--label required">@lang('License')</label>
                                                        <select class="form--control form--select select-2 license" name="old_file[{{ $file->id }}][is_free]" data-placeholder="" data-search="false">
                                                            <option value="">@lang('Select One')</option>
                                                            <option value="0" @if ($file->is_free == 0) selected @endif>@lang('Premium')</option>
                                                            <option value="1" @if ($file->is_free == 1) selected @endif>@lang('Free')</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 priceDiv">
                                                        <label class="form--label">@lang('Price') (@lang('You will get') <span class="text--base">{{ showAmount($setting->authors_commission) }}%</span> @lang('in each download'))</label>
                                                        <div class="input--group">
                                                            <input type="number" class="form--control assetPrice" name="old_file[{{ $file->id }}][price]" value="{{ getAmount($file->price) }}">
                                                            <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                                        </div>
                                                        <span class="d-block small fw-semibold pt-1">@lang('Maximum price') <span class="text--base">{{ showAmount($setting->max_price_limit) }} {{ __($setting->site_cur) }}</span></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="card-body">
                                                <div class="card-subtitle d-flex justify-content-between align-items-center">
                                                    <span class="pricing-set-card__title">@lang('Pricing Set') - 1</span>
                                                    <button type="button" class="btn btn--sm btn--danger py-1 remove-pricing-set"><i class="ti ti-circle-minus"></i> @lang('Remove')</button>
                                                </div>
                                                <div class="row g-4">
                                                    <div class="col-sm-6">
                                                        <label class="form--label">@lang('Resolution') <span class="text--base small lh-1" title="@lang('Separate width & height by x'), @lang('e.g.') 600x1200"><i class="ti ti-help-circle"></i></span></label>
                                                        <div class="input--group">
                                                            <input type="text" class="form--control" name="resolution[]" placeholder="@lang('E.g. 600x1200')">
                                                            <span class="input-group-text">@lang('PX')</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form--label required">@lang('Compressed File')</label>
                                                        <input type="file" class="form--control" name="file[]" accept="{{ getArchiveExtensions() }}">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form--label">@lang('Status')</label>
                                                        <select class="form--control form--select select-2" name="status[]" data-search="false">
                                                            <option value="1">@lang('Enable')</option>
                                                            <option value="0">@lang('Disable')</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 licenseDiv">
                                                        <label class="form--label required">@lang('License')</label>
                                                        <select class="form--control form--select select-2 license" name="is_free[]" data-placeholder="" data-search="false">
                                                            <option value="">@lang('Select One')</option>
                                                            <option value="0" selected>@lang('Premium')</option>
                                                            <option value="1">@lang('Free')</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 priceDiv">
                                                        <label class="form--label">@lang('Price') (@lang('You will get') {{ showAmount($setting->authors_commission) }}% @lang('in each download'))</label>
                                                        <div class="input--group">
                                                            <input type="number" class="form--control assetPrice" name="price[]">
                                                            <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                                        </div>
                                                        <span class="d-block small fw-semibold pt-1 text--muted">@lang('Maximum price') {{ showAmount($setting->max_price_limit) }} {{ __($setting->site_cur) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn--sm btn--base py-1 add-pricing-set"><i class="ti ti-circle-plus"></i> @lang('Add More')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit Asset')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script-lib')
    <script src="{{ asset($activeThemeTrue . 'js/page/ckEditor.js') }}"></script>
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

            // _______________________________________Extension for FileType Start_______________________________________


            let selectedExtension = [
                                        @foreach($image->extensions ?? [] as $i => $ext)
                                            "{{ $ext }}"@if(!$loop->last),@endif
                                        @endforeach
                                    ];

            let fileTypeSelect    = $('select[name="file_type_id"]');
            let extensionSelect   = $('#addExtensionSelect');
            let selectedFileType  = fileTypeSelect.find('option:selected');
            let defaultExtensions = selectedFileType.data('extensions') ?? [];

            populateExtensions(defaultExtensions, selectedExtension);

            fileTypeSelect.on('change', function() {
                let type          = $(this).find('option:selected').data('type');
                let newExtensions = $(this).find('option:selected').data('extensions') ?? [];

                fileInputManipulation(type);
                populateExtensions(newExtensions, []);
            });

            function populateExtensions(allExtensions, selectedExtensions) {
                let html = `<option value="" disabled>@lang('Select Extensions')</option>`;                

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

            // _______________________________________Extension for FileType End_________________________________________

            function fileInputManipulation(type) {
                if (type == 2) {
                    $('.videoDiv').removeClass('d-none');
                    $('.videoDiv').find('label').addClass('required');
                    $('[name=video]').attr('required', true);
                    $('[name=type]').val(type);
                } else {
                    $('.videoDiv').addClass('d-none');
                    $('.videoDiv').find('label').removeClass('required');
                    $('[name=video]').val('').removeAttr('required');
                    $('[name=type]').val(type);
                }
            }

            let initialTagAmount = 0;
            let tagLimit         = '{{ $setting->tag_limit_per_asset }}';

            @php
                $initialTagCount = isset($image) && isset($image->tags) ? count($image->tags) : 0;
            @endphp

            initialTagAmount = {{ $initialTagCount }} <= tagLimit ? {{ $initialTagCount }} : tagLimit;

            tagSelectionLimit($('select[name="tags[]"]'), initialTagAmount);

            $('select[name="tags[]"]').on('select2:select', function(e) {
                let selectedTags = $(this).find('option:selected');

                tagSelectionLimit($(this), selectedTags.length);
            });

            function tagSelectionLimit(selectFieldName, tagAmount) {
                if (tagAmount > tagLimit) {
                    selectFieldName.find('option:selected').last().prop('selected', false);

                    selectFieldName.trigger('change');

                    showToasts('warning', `@lang('You can select up to') ${tagLimit} @lang('tags only'))`);
                }
            }

            //_______________________________________Tag Selection End__________________________________

            // ========================= Pricing Set Form Add & Remove Start =====================
            let pricingSetCounter = $('.add-pricing-set > .card-body').length + 1;
            $('.add-pricing-set').on('click', function(){
                pricingSetCounter++;
                let newForm = `<div class="card-body">
                                <div class="card-subtitle d-flex justify-content-between align-items-center">
                                    <span class="pricing-set-card__title">@lang('Pricing Set') - ${pricingSetCounter}</span>
                                    <button type="button" class="btn btn--sm btn--danger py-1 remove-pricing-set"><i class="ti ti-circle-minus"></i> @lang('Remove')</button>
                                </div>
                                <div class="row g-4">
                                    <div class="col-sm-6">
                                    <label class="form--label">@lang('Resolution') <span class="text--base small lh-1" title="@lang('Separate width & height by x, e.g. 600x1200')"><i class="ti ti-help-circle"></i></span></label>
                                    <select class="form--control form--select select-2" name="resolution[]" data-search="true" required>
                                        <option value="" selected disabled>@lang('Select One')</option>
                                        @foreach ($resolutions as $resolution)
                                            <option value="{{ $resolution->resolution }}">{{ $resolution->resolution }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label required">@lang('Zip File')</label>
                                        <input type="file" class="form--control" name='file[]' accept="{{ getArchiveExtensions() }}" required>
                                    </div>
                                    <div class="col-sm-6">
                                    <label class="form--label">@lang('Status')</label>
                                    <select class="form--control form--select select-2" name=status[] data-search="false" required>
                                        <option value="1">@lang('Enable')</option>
                                        <option value="0">@lang('Disable')</option>
                                    </select>
                                    </div>
                                    <div class="col-sm-6 licenseDiv">
                                    <label class="form--label required">@lang('License')</label>
                                    <select class="form--control form--select select-2 license" name=is_free[] required data-placeholder="" data-search="false">
                                        <option value="">@lang('Select One')</option>
                                        <option value="0" selected>@lang('Premium')</option>
                                        <option value="1">@lang('Free')</option>
                                    </select>
                                    </div>
                                    <div class="col-12 priceDiv">
                                    <label class="form--label">@lang('Price') (@lang('You will get') <span class="text--base">{{ showAmount($setting->authors_commission) }}%</span> @lang('in each download'))</label>
                                    <div class="input--group">
                                        <input type="number" class="form--control assetPrice" name=price[] value="">
                                        <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                    </div>
                                    <span class="d-block small fw-semibold pt-1">@lang('Maximum price') <span class="text--base">{{ showAmount($setting->max_price_limit) }} {{ __($setting->site_cur) }}</span></span>
                                    </div>
                                </div>
                                </div>`;
                $('.pricing-set-card').append(newForm);

                updatePricingSetNumbers();
                
                $(".pricing-set-card .card-body:last-child .select-2").each(function() {
                    var $select = $(this);
                    var tags = $select.data('tags') === true;
                    var noSearch = $select.data('search') === false;
                
                    $select.select2({
                        containerCssClass: ":all:",
                        tags: tags,
                        templateResult: resultState,
                        minimumResultsForSearch: noSearch ? Infinity : 0,
                    });
                });

                function resultState(data, container) {
                    if(data.element) {
                        $(container).addClass($(data.element).attr("class"));
                    }

                    return data.text;
                }
            });

            
            $('.license').each(function() {
                applyLicenseLogic($(this));
            });

            $(document).on('change', '.license', function() {
                applyLicenseLogic($(this));
            });

            function applyLicenseLogic($license) {
                let $priceDiv = $license.closest('.licenseDiv').next('.priceDiv');

                if ($license.val() == 1) {
                    $priceDiv.addClass('d-none');
                    $priceDiv.find('input').val('0.00').removeAttr('required').trigger('change'); 
                } else {
                    $priceDiv.removeClass('d-none');
                    $priceDiv.find('input').attr('required', true);
                }
            }


            $(document).on('click', '.remove-pricing-set', function(){
                $(this).closest('.card-body').remove();
                updatePricingSetNumbers();

                if ($(this).data('id')) {
                    $('.removedFile').append(`<input type="hidden" name="removed_file[]" value="${$(this).data('id')}">`);
                }
            });
            
            function updatePricingSetNumbers() {
                $('.pricing-set-card__title').each(function(index) {
                    $(this).text(`Pricing Set - ${index + 1}`);
                });
            }

            $(document).on('input', '.assetPrice', function() {
                let amount = parseFloat($(this).val());
                let maximalPrice = parseFloat('{{ showAmount($setting->max_price_limit) }}');

                if (amount > maximalPrice) {
                    $(this).val('');
                    showToasts('warning', `@lang('Maximum price') ${maximalPrice.toFixed(2)} {{ __($setting->site_cur) }}`);
                    return;
                }

                if (amount <= 0) {
                    showToasts('warning', '@lang('Price must be greater than 0')');
                    return;
                }
            });
            // ========================= Pricing Set Form Add & Remove End ==========
        })(jQuery);
    </script>
@endpush
