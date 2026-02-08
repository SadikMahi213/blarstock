@extends('admin.layouts.master')

    @section('master')
        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table--striped table-borderless table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Slug')</th>
                            <th>@lang('Total Assets')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>
                                    <div class="table-card-with-image">
                                        <div class="table-card-with-image__img">
                                            <img src="{{ getImage(getFilePath('categories').'/'.$category->image, getFileSize('categories')) }}" alt="Image">
                                        </div>
                                        <div class="table-card-with-image__content">
                                            <p class="fw-semibold" title="{{ __($category->name) }}">{{ __(strLimit($category->name, 20)) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ formatNumber($category->approved_images_count) }}</td>
                                <td>@php echo $category->statusBadge; @endphp </td>
                                <td>
                                    <div>
                                        <button class="btn btn--sm btn-outline--base editBtn" 
                                            data-resource="{{ $category }}" 
                                            data-action="{{ route('admin.category.store', $category->id) }}"
                                            data-image="{{ getImage(getFilePath('categories').'/'.$category->image, getFileSize('categories')) }}">
                                            <i class="ti ti-edit"></i> @lang('Edit')
                                        </button>
        
                                        @if ($category->status)
                                            <button type="button" class="btn btn--sm btn--warning decisionBtn" 
                                                data-question="@lang('Are you confirming the inactivation of this category')?" 
                                                data-action="{{ route('admin.category.status', $category->id) }}">
                                                <i class="ti ti-ban"></i> @lang('Inactive')
                                            </button>
                                        @else
                                            <button type="button" class="btn btn--sm btn--success decisionBtn" 
                                                data-question="@lang('Are you confirming the activation of this category')?" 
                                                data-action="{{ route('admin.category.status', $category->id) }}">
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
            
            @if ($categories->hasPages())
                {{ paginateLinks($categories) }}
            @endif
        </div>

        {{-- Add Modal --}}
        <div class="modal custom--modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addModalLabel">@lang('New Category')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
        
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">
                                
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Image')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="upload__img mb-2">
                                                <label for="addimage" class="upload__img__btn" title="@lang('Image')"><i class="ti ti-camera"></i></label>
        
                                                <input type="file" id="addimage" class="image-upload" name="image" accept=".jpeg, .jpg, .png" required>
        
                                                <label for="addimage" class="upload__img-preview image-preview">
                                                    <img src="{{ getImage(getFilePath('categories'), getFileSize('categories')) }}" alt="image">
                                                </label>
        
                                                <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none"><i class="ti ti-circle-x"></i></button>
                                            </div>
                                            <label class="text-center small">@lang('Supported files'):
                                                <span class="fw-semibold text--base">@lang('jpeg'), @lang('jpg'), @lang('png').</span>
                                                @lang('Image size') <span class="fw-semibold text--base">{{ getFileSize('categories') }}@lang('px').</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Name')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="name" required>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Slug')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="slug" required>
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

        {{-- Add Modal --}}
        <div class="modal custom--modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="updatedModalLabel">@lang('Update Category')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        @csrf
        
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">
                                
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Image')</label>
                                        </div>
                                        <div class="col-sm-8">
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
                                                @lang('Image size') <span class="fw-semibold text--base">{{ getFileSize('categories') }}@lang('px').</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Name')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="name" required>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Slug')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="slug" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="modal-footer d-flex justify-content-end gap-2">
                            <button type="button" data-bs-dismiss="modal" class="btn btn--sm btn--secondary">@lang('Close')</button>
                            <button class="btn btn--sm btn--base" type="submit">@lang('Update')</button>
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

    @push('page-script')
        <script>
            (function($) {
                'use strict';

                $('.addBtn').on('click', function() {
                    let modal = $('#addModal');

                    modal.modal('show');
                });

                $('.editBtn').on('click', function() {
                    let modal       = $('#updateModal');
                    let resource    = $(this).data('resource');
                    let actionRoute = $(this).data('action');
                    let image       = $(this).data('image');

                    modal.find('[name=name]').val(resource.name);
                    modal.find('[name=slug]').val(resource.slug);
                    modal.find('img').attr('src', image);
                    modal.find('form').attr('action', actionRoute);

                    modal.modal('show');
                });
            })(jQuery);
        </script>
    @endpush