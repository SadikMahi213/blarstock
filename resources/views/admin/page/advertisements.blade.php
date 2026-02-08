@extends('admin.layouts.master')
@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('Type')</th>
                        <th>@lang('Size')</th>
                        <th>@lang('Impression')</th>
                        <th>@lang('Clicked')</th>
                        <th>@lang('Redirect')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($advertisements as $advertisement)
                        <tr>
                            <td>
                                @if ($advertisement->type == 1)
                                    @lang('Image')
                                @else
                                    @lang('Script')
                                @endif
                            </td>
                            <td>{{ $advertisement->size }}</td>
                            <td>
                                <span class="badge badge--secondary">{{ $advertisement->impression }}</span>
                            </td>
                            <td>
                                <span class="badge badge--info">{{ $advertisement->click }}</span>
                            </td>
                            <td>
                                @if ($advertisement->redirect_url)
                                    <a target="_blank" class="text--primary" href="{{ $advertisement->redirect_url }}">
                                        <i class="ti ti-external-link"></i>
                                    </a>
                                @else
                                    <span class="text--secondary"><i class="ti ti-external-link"></i></span>
                                @endif
                            </td>
                            <td>
                                @php
                                    echo $advertisement->statusBadge;
                                @endphp
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn--sm btn-outline--base editBtn" 
                                        data-resource="{{ $advertisement }}" 
                                        data-action="{{ route('admin.advertisement.store', $advertisement->id) }}"
                                        @if ($advertisement->image)
                                            data-image="{{ getImage(getFilePath('advertisements').'/'.$advertisement->image, $advertisement->size) }}"
                                        @endif
                                        >
                                        <i class="ti ti-edit"></i> @lang('Edit')
                                    </button>

                                    <div class="custom--dropdown">
                                        <button class="btn btn--icon btn--sm btn--base" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button type="button" class="dropdown-item text--danger decisionBtn" 
                                                    data-question="@lang('Are you confirming the deletion of this advertisement')?" 
                                                    data-action="{{ route('admin.advertisement.delete', $advertisement->id) }}">
                                                    <span class="dropdown-icon"><i class="ti ti-trash"></i></span> @lang('Delete')
                                                </button>
                                            </li>
                                            @if ($advertisement->status)
                                                <li>
                                                    <button type="button" class="dropdown-item text--warning decisionBtn" 
                                                        data-question="@lang('Are you confirming the inactivation of this advertisement')?" 
                                                        data-action="{{ route('admin.advertisement.status', $advertisement->id) }}">
                                                        <span class="dropdown-icon"><i class="ti ti-ban"></i></span> @lang('Inactive')
                                                    </button>
                                                </li>
                                            @else
                                                <li>
                                                    <button type="button" class="dropdown-item text--success decisionBtn" 
                                                        data-question="@lang('Are you confirming the activation of this advertisement')?" 
                                                        data-action="{{ route('admin.advertisement.status', $advertisement->id) }}">
                                                        <span class="dropdown-icon"><i class="ti ti-circle-check"></i></span> @lang('Active')
                                                    </button>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($advertisements->hasPages())
            {{ paginateLinks($advertisements) }}
        @endif
    </div>

    {{-- Add Modal --}}
    <div class="modal custom--modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="addModalLabel"> </h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    @csrf
    
                    <div class="modal-body text-center">
                        <div class="row g-3 align-items-center">
                            <div class="col-12">
                                <div class="row gy-2">
                                    <div class="col-sm-4">
                                        <label class="col-form--label required">@lang('Type')</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select class="form--control form-select" name="type" required>
                                            <option value="" selected disabled>@lang('Select One')</option>
                                            <option value="1">@lang('Image')</option>
                                            <option value="2">@lang('Script')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row gy-2">
                                    <div class="col-sm-4">
                                        <label class="col-form--label required">@lang('Size')</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form--control" value="735x90" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12" id="imageDiv">
                                <div class="row gy-2">
                                    <div class="col-sm-4">
                                        <label class="col-form--label imageLevel">@lang('Image')</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="upload__img mb-2">
                                            <label for="addImage" class="upload__img__btn" title="@lang('Image')"><i class="ti ti-camera"></i></label>
    
                                            <input type="file" id="addImage" class="image-upload" name="image" accept=".jpeg, .jpg, .png">
    
                                            <label for="addImage" class="upload__img-preview image-preview" id="profilePicPreview"></label>
    
                                            <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none"><i class="ti ti-circle-x"></i></button>
                                        </div>
                                        <label class="text-center small">@lang('Supported files'):
                                            <span class="fw-semibold text--base">@lang('jpeg'), @lang('jpg'), @lang('png').</span>
                                            @lang('Image size') <span class="fw-semibold text--base imageSize"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-4">
                                        <label class="col-form--label required">@lang('Redirect Url')</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form--control" name="redirect_url">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12" id="scriptDiv">
                                <div class="row gy-2">
                                    <div class="col-sm-4">
                                        <label class="col-form--label required">@lang('Script')</label>
                                    </div>
                                    <div class="col-sm-8 editor-wrapper">
                                        <textarea class="form--control" name="script"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="modal-footer d-flex justify-content-end gap-2">
                        <button type="button" data-bs-dismiss="modal" class="btn btn--sm btn--secondary">@lang('Close')</button>
                        <button class="btn btn--sm btn--base submitBtn" type="submit">@lang('Add')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search with size"/>
    <button type="button" class="btn btn--sm btn--base addBtn"><i class="ti ti-circle-plus"></i> @lang('Add New')</button>
@endpush

@push('page-script')
    <script>
        (function($) {
            'use strict';

            let modal             = $('#addModal');
            let typeField         = modal.find('[name=type]');
            let imageField        = modal.find('[name=image]');
            let redirectUrlField  = modal.find('[name=redirect_url]');
            let scriptField       = modal.find('[name=script]');
            let imageDiv          = modal.find('#imageDiv');
            let scriptDiv         = modal.find('#scriptDiv');
            let profilePicPreview = modal.find('#profilePicPreview');
            let imageSizeLabel    = modal.find('.imageSize');
            let submitButton      = modal.find('.submitBtn');

            function resetModal() {
                modal.find('#addModalLabel').text(`@lang('New Advertisement')`);
                modal.find('form')[0].reset();
                imageDiv.addClass('d-none');
                scriptDiv.addClass('d-none');
                submitButton.text(`@lang('Add')`);
                toggleFields(false, false, false);
            }

            function toggleFields(isImage, isRedirectUrl, isScript) {
                imageField.prop('required', isImage);
                redirectUrlField.prop('disabled', !isRedirectUrl).prop('required', isRedirectUrl);
                scriptField.prop('disabled', !isScript).prop('required', isScript);
            }

            function setImagePreview(size) {
                let placeholderImageUrl = `{{ route('placeholder.image', ':size') }}`.replace(':size', size);

                profilePicPreview.html(`<img src="${placeholderImageUrl}">`);
                imageSizeLabel.text(`${size}@lang('px')`);
            }

            $('.addBtn').on('click', function() {
                resetModal();
                modal.find('form').attr('action', '{{ route('admin.advertisement.store') }}');
                modal.modal('show');
            });

            typeField.on('change', function() {
                let value = $(this).val();

                if (value == 1) {
                    setImagePreview('735x90');
                    toggleFields(true, true, false);
                } else {
                    toggleFields(false, false, true);
                }

                if (value == 1) {
                    imageDiv.removeClass('d-none');
                    scriptDiv.addClass('d-none');
                } else if (value == 2 ){
                    imageDiv.addClass('d-none');
                    scriptDiv.removeClass('d-none');
                }
            });             


            $('.editBtn').on('click', function() {
                let resource    = $(this).data('resource');
                let actionRoute = $(this).data('action');
                let image       = $(this).data('image');

                modal.find('#addModalLabel').text(`@lang('Update Advertisement')`);
                submitButton.text(`@lang('Update')`);

                typeField.val(resource.type);

                if (resource.type == 1) {
                    imageDiv.removeClass('d-none');
                    scriptDiv.addClass('d-none');
                    toggleFields(false, true, false);

                    redirectUrlField.val(resource.redirect_url);
                    profilePicPreview.html(`<img src="${image}" alt="image">`);
                    imageSizeLabel.text(`${resource.size}@lang('px')`);
                } else if (resource.type == 2) {
                    imageDiv.addClass('d-none');
                    scriptDiv.removeClass('d-none');
                    toggleFields(false, false, true);

                    scriptField.val(resource.script);
                }

                modal.find('form').attr('action', actionRoute);
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush