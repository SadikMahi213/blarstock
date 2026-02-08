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
                        <h3 class="title">@lang('Upload Your Asset')</h3>
                    </div>
                    <div class="card-body">


                        <form action="{{ route('user.asset.store') }}" method="POST" class="row g-4" enctype="multipart/form-data">
                            @csrf

                            

                            
                            <!-- Single Upload Section -->
                            <div class="row g-4">


                            <div class="col-lg-5">
                                <label for="fileInput" class="dropzone" tabindex="0" data-drag-text="@lang('Drop Here')...">
                                    <span class="dropzone__icon"><i class="ti ti-upload"></i></span>
                                    <span class="dropzone__txt">@lang('Choose'), @lang('or drag and drop files here')</span>
                                    <span class="dropzone__preview"></span>
                                </label>
                                <span class="d-block small fw-semibold pt-2 note-for-image">@lang('Supported files'): 
                                    <span class="fw-bold">@lang('Images'):</span> .@lang('png'), .@lang('jpg'), .@lang('jpeg') | 
                                    <span class="fw-bold">@lang('Videos'):</span> .@lang('mp4'), .@lang('mov'), .@lang('avi') | 
                                    <span class="fw-bold">@lang('Vectors'):</span> .@lang('svg'), .@lang('ai'), .@lang('eps') | 
                                    <span class="fw-bold">@lang('3D Objects'):</span> .@lang('obj'), .@lang('fbx'), .@lang('blend'), .@lang('max'), .@lang('ma') | 
                                    <span class="fw-bold">@lang('Documents'):</span> .@lang('pdf')
                                </span>
                                <input type="file" id="fileInput" class="d-none" name="photos[]" multiple accept=".png, .jpg, .jpeg, .mp4, .mov, .avi, .svg, .ai, .eps, .pdf, .obj, .fbx, .blend, .max, .ma" required>
                                
                                <!-- Preview Carousel -->
                                <div id="filePreviewCarousel" class="preview-carousel d-none mt-3">
                                    <div class="preview-header d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">@lang('Preview Files') (<span id="fileCount">0</span> @lang('selected'))</h6>
                                        <div class="preview-nav">
                                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" id="prevFile">
                                                <i class="ti ti-chevron-left"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="nextFile">
                                                <i class="ti ti-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="preview-container position-relative">
                                        <div class="preview-content">
                                            <div id="previewSlides" class="slides-container"></div>
                                        </div>
                                        <div class="preview-indicators text-center mt-2">
                                            <div id="slideIndicators" class="d-flex justify-content-center"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <input type="hidden" name="type" id="assetTypeHidden" value="1" required>
                                        <label class="form--label required">@lang('Asset Title')</label>
                                        <input type="text" class="form--control" name="title" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label required">@lang('Asset Type')</label>
                                        <select id="assetType" class="form--control form--select select-2" name="file_type_id" required data-placeholder="@lang('Select one')">
                                            <option value="">@lang('Select one')</option>
                                            @foreach ($fileTypes as $fileType)
                                                <option value="{{ $fileType->id }}"
                                                    data-extensions="{{ json_encode($fileType->supported_file_extension) }}"
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
                                                <option value="{{ $category->id }}">{{ __($category->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label">@lang('Colors')</label>
                                        <select class="form--control form--select select-2" name="colors[]" multiple data-tags="false" data-placeholder="@lang('Select colors')">
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->code }}">{{ __($color->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label required">@lang('Extensions')</label>
                                        <select class="form--control form--select select-2" id="addExtensionSelect" name="extensions[]" required multiple data-tags="false" data-placeholder="@lang('Enter extensions') @lang('e.g.') '.@lang('png')'"></select>
                                    </div>
                                    <div class="col-12 videoDiv d-none">
                                        <label class="form--label required">@lang('Video') (@lang('Supported files:') <span class="fw-bold text--base">@lang('.mp4'), @lang('.3gp')</span>)</label>
                                        <input type="file" class="form--control" name="video" accept=".mp4, .3gp" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Tags') (@lang('maximum') {{ __($setting->tag_limit_per_asset) }} @lang('tags'))</label>
                                <select class="form--control form--select select-2" name="tags[]" required multiple data-tags="true" data-placeholder="@lang('Enter keywords')">
                                    <option value="">@lang('Enter keywords')</option>
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag }}">{{ $tag }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 editor-wrapper">
                                <label class="form--label required">@lang('Description')</label>
                                <textarea class="form--control" name="description"></textarea>
                            </div>
                            
                            <div class="col-12">
                                <div class="custom--card border--dark-subtle">
                                    <div class="pricing-set-card">
                                        <div class="card-body">
                                            <div class="card-subtitle d-flex justify-content-between align-items-center">
                                                <span class="pricing-set-card__title">@lang('Pricing Set') - 1</span>
                                                <button type="button" class="btn btn--sm btn--danger py-1 remove-pricing-set"><i class="ti ti-circle-minus"></i> @lang('Remove')</button>
                                            </div>
                                            <div class="row g-4">
                                                <div class="col-sm-6">
                                                    <label class="form--label required">@lang('Resolution') <span class="text--base small lh-1" title="@lang('Separate width & height by x'), @lang('e.g.') 600x1200"><i class="ti ti-help-circle"></i></span></label>
                                                    <select class="form--control form--select select-2" name="resolution[]" data-search="true" required>
                                                        <option value="" selected disabled>@lang('Select One')</option>
                                                        @foreach ($resolutions as $resolution)
                                                            <option value="{{ $resolution->resolution }}">{{ $resolution->resolution }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label class="form--label required">@lang('Status')</label>
                                                    <select class="form--control form--select select-2" name="status[]" data-search="false" required>
                                                        <option value="1">@lang('Enable')</option>
                                                        <option value="0">@lang('Disable')</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6 licenseDiv">
                                                    <label class="form--label required">@lang('License')</label>
                                                    <select class="form--control form--select" name="is_free[]" required>
                                                        <option value="0" selected>@lang('Premium')</option>
                                                        <option value="1">@lang('Free')</option>
                                                    </select>
                                                </div>
                                                <div class="col-12 priceDiv">
                                                    <label class="form--label">@lang('Price') (@lang('You will get') <span class="text--base">{{ showAmount($setting->authors_commission) }}%</span> @lang('in each download'))</label>
                                                    <div class="input--group">
                                                        <input type="number" class="form--control assetPrice" name="price[]">
                                                        <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                                    </div>
                                                    <span class="d-block small fw-semibold pt-1">@lang('Maximum price') <span class="text--base">{{ showAmount($setting->max_price_limit) }} {{ __($setting->site_cur) }}</span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn--sm btn--base py-1 add-pricing-set"><i class="ti ti-circle-plus"></i> @lang('Add More')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn--base w-100" onclick="return validateForm();">@lang('Submit Asset')</button>
                            </div>
                            
                            <script>
                            function validateForm() {
                                console.log('Submit button clicked');
                                
                                // Check if at least one pricing set is filled out
                                const resolutionInputs = document.querySelectorAll('select[name="resolution[]"]');
                                let hasValidPricingSet = false;
                                
                                for (let i = 0; i < resolutionInputs.length; i++) {
                                    if (resolutionInputs[i].value !== '') {
                                        hasValidPricingSet = true;
                                        break;
                                    }
                                }
                                
                                if (!hasValidPricingSet) {
                                    alert('Please add at least one pricing set with a resolution.');
                                    return false;
                                }
                                
                                // Check if at least one file is selected
                                const fileInput = document.getElementById('fileInput');
                                if (!fileInput.files || fileInput.files.length === 0) {
                                    alert('Please select at least one file to upload.');
                                    return false;
                                }
                                
                                // Check description field (get content from CKEditor)
                                const descriptionTextarea = document.querySelector('textarea[name="description"]');
                                
                                // Try to get content from CKEditor
                                let descriptionValue = '';
                                
                                // CKEditor instances are usually stored in global CKEDITOR object or in a variable
                                // Try to find the CKEditor instance associated with this textarea
                                if (typeof ClassicEditor !== 'undefined' && typeof CKEditors !== 'undefined') {
                                    // If using a custom CKEditor instance storage
                                    const editorInstance = CKEditors['description']; // assuming it's stored by textarea name
                                    if (editorInstance) {
                                        descriptionValue = editorInstance.getData();
                                    }
                                } else if (typeof CKEDITOR !== 'undefined') {
                                    // Standard CKEditor 4 approach
                                    const editorInstance = CKEDITOR.instances['description'];
                                    if (editorInstance) {
                                        descriptionValue = editorInstance.getData();
                                    }
                                } else {
                                    // Try to get any existing CKEditor instance (for CKEditor 5)
                                    const allEditors = document.querySelectorAll('[data-ckeditor]');
                                    if (allEditors.length > 0) {
                                        // CKEditor 5 stores data differently, try to get it
                                        const editorElement = allEditors[0];
                                        // For CKEditor 5, the content might be in the original textarea after sync
                                        // Let's try to get content from the textarea after triggering any potential sync
                                        descriptionValue = descriptionTextarea.value || '';
                                    } else {
                                        // Fallback to textarea value
                                        descriptionValue = descriptionTextarea.value || '';
                                    }
                                }
                                
                                // If we still don't have content, check if there's a CKEditor 5 balloon/block editor
                                if (!descriptionValue) {
                                    // CKEditor 5 creates different DOM structures, try to find content in common locations
                                    const ckeditorMain = document.querySelector('.ck-content') || 
                                                         document.querySelector('.ck-editor__editable') ||
                                                         document.querySelector('[data-cke-filler]');
                                    
                                    if (ckeditorMain) {
                                        descriptionValue = ckeditorMain.textContent || ckeditorMain.innerText || '';
                                    }
                                }
                                
                                // Trim and check if it's empty
                                if (!descriptionValue.trim()) {
                                    alert('Please enter a description.');
                                    // Focus on the editor if possible
                                    descriptionTextarea.focus();
                                    return false;
                                }
                                
                                // Make sure content is synced back to textarea for form submission
                                descriptionTextarea.value = descriptionValue;
                                
                                console.log('Form validation passed');
                                return true;
                            }
                            </script>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-style')
<style>
.file-previews {
    position: relative;
    display: inline-block;
}
.preview-thumb {
    max-width: 100%;
    max-height: 200px;
    border-radius: 4px;
    object-fit: cover;
}
.file-icon {
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 4px;
    font-size: 2rem;
    color: #6c757d;
}
.file-count {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: bold;
}
.dropzone__preview.active {
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Preview Carousel Styles */
.preview-carousel {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    background: #f8f9fa;
}

.preview-header {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.preview-nav .btn {
    width: 30px;
    height: 30px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.slides-container {
    position: relative;
    height: 300px;
    overflow: hidden;
}

.slide-item {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.slide-item.active {
    opacity: 1;
}

.file-preview-image,
.file-preview-video,
.file-preview-other {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    height: 100%;
}

.preview-image {
    max-width: 100%;
    max-height: 200px;
    object-fit: contain;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.preview-video {
    max-width: 100%;
    max-height: 200px;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.file-icon-large {
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e9ecef;
    border-radius: 8px;
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 15px;
}

.file-info {
    text-align: center;
    margin-top: 15px;
    padding: 10px;
    background: white;
    border-radius: 4px;
    width: 100%;
    max-width: 300px;
}

.file-name {
    font-weight: 600;
    margin-bottom: 5px;
    word-break: break-all;
}

.file-size {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 3px;
}

.file-type {
    font-size: 0.8rem;
    color: #007bff;
    text-transform: uppercase;
}

.preview-indicators {
    margin-top: 15px;
}

.indicator-btn {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #007bff;
    background: transparent;
    margin: 0 4px;
    padding: 0;
    cursor: pointer;
    transition: all 0.2s ease;
}

.indicator-btn.active {
    background: #007bff;
}

.indicator-btn:hover {
    transform: scale(1.2);
}
</style>
@endpush

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

            let extensionSelect   = $('#addExtensionSelect');

            $('select[name="file_type_id"]').on('change', function() {
                let type       = $(this).find('option:selected').data('type');
                let extensions = $(this).find('option:selected').data('extensions');

                fileInputManipulation(type);
                populateExtensions(extensions, []);
            });

            function populateExtensions(allExtensions) {
                let html = ``;

                if (allExtensions.length) {
                    allExtensions.forEach(extension => {
                        html += `<option value="${extension}">${extension.toUpperCase()}</option>`;
                    });
                }

                extensionSelect.html(html).trigger('change');
            }

            function fileInputManipulation(type) {
                if (type == 2) {
                    $('.videoDiv').removeClass('d-none');
                    $('[name=video]').attr('required', true);
                    $('[name=type]').val(type);
                } else {
                    $('.videoDiv').addClass('d-none');
                    $('[name=video]').val('').removeAttr('required');
                    $('[name=type]').val(type);
                }
            }

            // ___________________________________Tag Selection Start_____________________________
            let tagLimit = '{{ $setting->tag_limit_per_asset }}';

            $('select[name="tags[]"]').on('select2:select', function (e) {
               let selelctedTags = $(this).find('option:selected');

               if (selelctedTags.length > tagLimit) {
                   $(this).find('option:selected').last().prop('selected', false);

                   $(this).trigger('change');

                   showToasts('warning', `@lang('You can select up to') ${tagLimit} @lang('tags only')`);
               }
            });
            // ___________________________________Tag Selection End_______________________________

            // ___________________________Price div & field manipulation start____________________
            $('select[name="is_free[]"]').each(function() {
                tooglePriceField($(this));
            });

            $(document).on('change', 'select[name="is_free[]"]', function() {
               tooglePriceField($(this));
            });

            function tooglePriceField(selectElement) {
                let priceFieldDiv = selectElement.closest('.licenseDiv').next('.priceDiv');
                let priceField    = priceFieldDiv.find('[name="price[]"]');

                if (selectElement.val() == 1) {
                    priceField.removeAttr('required').val('0.00');
                    priceFieldDiv.addClass('d-none');
                } else {
                    priceFieldDiv.removeClass('d-none');
                    priceField.attr('required', true);
                }
            }
            // ___________________________Price div & field manipulation end______________________


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
                                        <label class="form--label required">@lang('Resolution') <span class="text--base small lh-1" title="@lang('Separate width & height by x, e.g. 600x1200')"><i class="ti ti-help-circle"></i></span></label>
                                        <select class="form--control form--select select-2" name="resolution[]" data-search="true" required>
                                            <option value="" selected disabled>@lang('Select One')</option>
                                            @foreach ($resolutions as $resolution)
                                                <option value="{{ $resolution->resolution }}">{{ $resolution->resolution }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="form--label required">@lang('Status')</label>
                                        <select class="form--control form--select select-2" name=status[] data-search="false">
                                            <option value="1">@lang('Enable')</option>
                                            <option value="0">@lang('Disable')</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 licenseDiv">
                                        <label class="form--label required">@lang('License')</label>
                                        <select class="form--control form--select select-2" name=is_free[] required>
                                            <option value="0" selected>@lang('Premium')</option>
                                            <option value="1">@lang('Free')</option>
                                        </select>
                                    </div>
                                    <div class="col-12 priceDiv">
                                        <label class="form--label">@lang('Price') (@lang('You will get') <span class="text--base">{{ showAmount($setting->authors_commission) }}%</span> @lang('in each download'))</label>
                                        <div class="input--group">
                                            <input type="number" class="form--control assetPrice" name=price[] value="">
                                            <span class="input-group-text">{{ $setting->site_cur }}</span>
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

            $(document).on('click', '.remove-pricing-set', function(){
                $(this).closest('.card-body').remove();
                updatePricingSetNumbers();
            });

            function updatePricingSetNumbers() {
                $('.pricing-set-card__title').each(function(index) {
                    $(this).text(`Pricing Set - ${index + 1}`);
                });
            }
            // ========================= Pricing Set Form Add & Remove End ==========

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
            
            // Update the type field based on selected asset type
            $(document).on('change', '#assetType', function() {
                var selectedType = $(this).find('option:selected').data('type');
                // Update the hidden type field: 1 for image, 2 for video
                $('input[name="type"]').val(selectedType);
                
                // Show/hide video input based on selection
                if (selectedType == 2) {
                    $('.videoDiv').removeClass('d-none');
                    $('[name="video"]').attr('required', true);
                } else {
                    $('.videoDiv').addClass('d-none');
                    $('[name="video"]').removeAttr('required');
                }
            });
        })(jQuery);
    </script>
@endpush
