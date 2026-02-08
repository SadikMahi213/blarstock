@extends('admin.layouts.master')
    @section('master')
        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table--striped table-borderless table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Slug')</th>
                            <th>@lang('icon')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Total Assets')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fileTypes as $fileType)
                            <tr>
                                <td>
                                    <div class="table-card-with-image">
                                        <div class="table-card-with-image__img">
                                            <img src="{{ getImage(getFilePath('fileTypes').'/'.$fileType->image) }}" alt="Image">
                                        </div>
                                        <div class="table-card-with-image__content">
                                            <p class="fw-semibold">{{ __(strLimit($fileType->name, 15)) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $fileType->slug }}</td>
                                <td>@php echo $fileType->icon; @endphp</td>
                                <td>
                                    @if ($fileType->type == 1)
                                        @lang('Image')
                                    @elseif ($fileType->type == 2)
                                        @lang('Video')
                                    @endif
                                </td>
                                <td>{{ formatNumber($fileType->approved_images_count) }}</td>
                                <td>@php echo $fileType->statusBadge; @endphp</td>
                                <td>
                                    <div>
                                        <button class="btn btn--sm btn-outline--base editBtn" 
                                            data-resource="{{ $fileType }}" 
                                            data-action="{{ route('admin.filetype.store', $fileType->id) }}"
                                            data-image="{{ getImage(getFilePath('fileTypes') . '/' . $fileType->image) }}">
                                            <i class="ti ti-edit"></i> @lang('Edit')
                                        </button>

                                        @if ($fileType->status)
                                            <button type="button" class="btn btn--sm btn--warning decisionBtn" 
                                                data-question="@lang('Are you confirming the inactivation of this file type')?" 
                                                data-action="{{ route('admin.filetype.status', $fileType->id) }}">
                                                <i class="ti ti-ban"></i> @lang('Inactive')
                                            </button>
                                        @else
                                            <button type="button" class="btn btn--sm btn--success decisionBtn" 
                                                data-question="@lang('Are you confirming the activation of this file type')?" 
                                                data-action="{{ route('admin.filetype.status', $fileType->id) }}">
                                                <i class="ti ti-circle-check"></i> @lang('Active')
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('partials.noData')
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($fileTypes->hasPages())
                {{ paginateLinks($fileTypes) }}
            @endif
        </div>



        {{-- Add Modal --}}
        <div class="modal custom--modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addModalLabel">@lang('New File Type')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="{{ route('admin.filetype.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
        
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">

                                <div class="col-12">
                                    <div class="row g-4">

                                        <div class="col-xl-6">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <div class="col-xxl-12 col-sm-12">
                                                        <div class="upload__img mb-2">
                                                            <label for="addimage" class="upload__img__btn" title="@lang('Image')"><i class="ti ti-camera"></i></label>
                    
                                                            <input type="file" id="addimage" class="image-upload" name="image" accept=".jpeg, .jpg, .png"  required>
                    
                                                            <label for="addimage" class="upload__img-preview image-preview">
                                                                <img src="{{ getImage(getFilePath('fileTypes')) }}" alt="image">
                                                            </label>
                    
                                                            <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none"><i class="ti ti-circle-x"></i></button>
                                                        </div>
                                                        <label class="text-center small">@lang('Supported files'):
                                                            <span class="fw-semibold text--base">@lang('jpeg'), @lang('jpg'), @lang('png').</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="row g-4">
                                                <div class="col-xl-12 col-sm-12">
                                                    <label class="form--label required">@lang('Name')</label>
                                                    <input type="text" class="form--control" name="name" required>
                                                </div>
                                                <div class="col-xl-12 col-sm-12">
                                                    <label class="form--label required">@lang('Slug')</label>
                                                    <input type="text" class="form--control" name="slug" required>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Icon')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input--group">
                                                <input type="text" class="form--control iconPicker icon" name="icon" autocomplete="off" required>
                                                <span class="input-group-text input-group-addon" data-icon="ti ti-home" role="iconpicker"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Type')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="form--control form-select" name="type" required>
                                                <option value="" selected disabled>@lang('Select One')</option>
                                                <option value="1">@lang('Image')</option>
                                                <option value="2">@lang('Video')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Supported Extensions')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="form--control form--select select-2" multiple data-tags="true" name="supported_file_extension[]" required></select>
                                            <p class="text--muted">@lang('Separate multiple extension by') "<code class="fw-bold">,</code>" (@lang('comma')) @lang('or') "<code class="fw-bold">@lang('enter')</code>" @lang('key')</p> 
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
        
                        <div class="modal-footer d-flex justify-content-end gap-2">
                            <button type="button" data-bs-dismiss="modal" class="btn btn--sm btn--secondary">@lang('Close')</button>
                            <button class="btn btn--sm btn--base" type="submit">@lang('Add')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{-- Update Modal --}}
        <div class="modal custom--modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="updateModalLabel">@lang('Update File Type')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
        
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">

                                <div class="col-12">
                                    <div class="row g-4">

                                        <div class="col-xl-6">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <div class="col-xxl-12 col-sm-12">
                                                        <div class="upload__img mb-2">
                                                            <label for="updateimage" class="upload__img__btn" title="@lang('Image')"><i class="ti ti-camera"></i></label>
                    
                                                            <input type="file" id="updateimage" class="image-upload" name="image" accept=".jpeg, .jpg, .png">
                    
                                                            <label for="updateimage" class="upload__img-preview image-preview">
                                                                <img src="" alt="image">
                                                            </label>
                    
                                                            <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none"><i class="ti ti-circle-x"></i></button>
                                                        </div>
                                                        <label class="text-center small">@lang('Supported files'):
                                                            <span class="fw-semibold text--base">@lang('jpeg'), @lang('jpg'), @lang('png').</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="row g-4">
                                                <div class="col-xl-12 col-sm-12">
                                                    <label class="form--label required">@lang('Name')</label>
                                                    <input type="text" class="form--control" name="name" required>
                                                </div>
                                                <div class="col-xl-12 col-sm-12">
                                                    <label class="form--label required">@lang('Slug')</label>
                                                    <input type="text" class="form--control" name="slug" required>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Icon')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input--group">
                                                <input type="text" class="form--control iconPicker icon" name="icon" autocomplete="off" required>
                                                <span class="input-group-text input-group-addon" data-icon="ti ti-home" role="iconpicker"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Type')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="form--control form-select" name="type" required>
                                                <option value="" selected disabled>@lang('Select One')</option>
                                                <option value="1">@lang('Image')</option>
                                                <option value="2">@lang('Video')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Supported Extensions')</label>
                                        </div>
                                        <div class="col-sm-8" id="fileExtensionContainer">
                                            <select class="form--control form--select px-4 select-2" multiple data-tags="true" name="supported_file_extension[]" tabindex="-1" required></select>
                                            <p class="text--muted">@lang('Separate multiple extension by') "<code class="fw-bold">,</code>" (@lang('comma')) @lang('or') "<code class="fw-bold">@lang('enter')</code>" @lang('key')</p> 
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
        
                        <div class="modal-footer d-flex justify-content-end gap-2">
                            <button type="button" data-bs-dismiss="modal" class="btn btn--sm btn--secondary">@lang('Close')</button>
                            <button class="btn btn--sm btn--base" type="submit">@lang('Add')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <x-decisionModal />
    @endsection

    @push('breadcrumb')
        <x-searchForm placeholder="Name or Slug" />
        <button type="button" class="btn btn--sm btn--base addBtn"><i class="ti ti-circle-plus"></i> @lang('Add New')</button>
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

                $('.addBtn').on('click', function() {
                    let modal = $('#addModal');

                    modal.find('select').select2({
                        containerCssClass: ":all:",
                        dropdownParent: modal,
                    });

                    modal.modal('show');
                });

                $('.editBtn').on('click', function() {
                    let modal                  = $('#updateModal');
                    let actionRoute            = $(this).data('action');
                    let resource               = $(this).data('resource');
                    let image                  = $(this).data('image');

                    modal.find('[name=name]').val(resource.name);
                    modal.find('[name=slug]').val(resource.slug);
                    modal.find('[name=icon]').val(resource.icon);
                    modal.find('[name=type]').val(resource.type);
                    modal.find('img').attr('src', image);
                    modal.find('form').attr('action', actionRoute);

                    let supportedExtensions = modal.find('select[name="supported_file_extension[]"]');
                    supportedExtensions.empty();

                    let optionHtml = '';

                    if (Array.isArray(resource.supported_file_extension)) {
                        resource.supported_file_extension.forEach(extension => {
                            optionHtml += `<option value="${extension}" selected>${extension}</option>`;
                        });
                    }

                    supportedExtensions.append(optionHtml);

                    modal.find('select').select2({
                        containerCssClass: ":all:",
                        dropdownParent: modal,
                    });
                    
                    modal.modal('show');
                });
            })(jQuery);
        </script>
    @endpush