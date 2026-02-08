@extends('admin.layouts.master')

@section('master')
    @if(isset($section->content) && $section->content)
        <div class="col-12">
            <div class="custom--card">
                <form action="{{ route('admin.site.sections.content', $key) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row g-4">
                            <input type="hidden" name="type" value="content">

                            @php $imgCount = 0 @endphp

                            @foreach($section->content ?? [] as $k => $item)
                                @if($k == 'images')
                                    @php $imgCount = collect($item)->count() @endphp

                                    @foreach($item ?? [] as $imgKey => $image)
                                        <div class="col-lg-4 col-md-4 col-sm-6">
                                            <div class="upload__img mb-2">
                                                <label for="image{{ $loop->index }}" class="upload__img__btn" title="{{ __(keyToTitle($imgKey)) }}">
                                                    <i class="ti ti-camera"></i>
                                                </label>

                                                <input type="file" id="image{{ $loop->index }}" class="image-upload" name="image_input[{{ $imgKey }}]" accept=".jpeg, .jpg, .png">

                                                <label for="image{{ $loop->index }}" class="upload__img-preview image-preview">
                                                    <img src="{{ getImage($activeThemeTrue . 'images/site/' . $key . '/' . ($content->data_info->$imgKey ?? ''), optional($section->content->images->$imgKey)->size ?? '') }}" alt="image">
                                                </label>

                                                <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none">
                                                    <i class="ti ti-circle-x"></i>
                                                </button>
                                            </div>
                                            <label class="text-center small">@lang('Supported files'):
                                                <span class="fw-semibold text--base">@lang('jpeg'), @lang('jpg'), @lang('png').</span>

                                                @if(optional($section->content->images->$imgKey)->size)
                                                    @lang('Image Size') <span class="fw-semibold text--base">{{ $section->content->images->$imgKey->size }}@lang('px').</span>
                                                @endif

                                                @if(optional($section->content->images->$imgKey)->thumb)
                                                    @lang('Thumb Size') <span class="fw-semibold text--base">{{ $section->content->images->$imgKey->thumb }}@lang('px').</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach

                                    <div class="@if($imgCount > 1) col-lg-12 col-md-12 @else col-lg-8 col-md-8 @endif">
                                        <div class="row g-lg-4 g-3">
                                            @push('divend')
                                        </div>
                                    </div>
                                    @endpush
                                @else
                                    <div class="col-12">
                                        <div class="row g-lg-4 g-3">
                                            @if($k != 'images')
                                                @if($item == 'icon')
                                                    <div class="col-12">
                                                        <div class="row g-2 align-items-center">
                                                            <div class="col-lg-3">
                                                                <label class="form--label required">{{ __(keyToTitle($k)) }}</label>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <div class="input--group">
                                                                    <input type="text" class="form--control iconPicker icon" name="{{ $k }}" value="{{ $content->data_info->$k ?? '' }}" autocomplete="off" required>
                                                                    <span class="input-group-text input-group-addon" data-icon="ti ti-home">@php echo $content->data_info->$k ?? '' @endphp</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($item == 'textarea')
                                                    <div class="col-12">
                                                        <div class="row g-2 align-items-center">
                                                            <div class="col-lg-3">
                                                                <label class="form--label required">{{ __(keyToTitle($k)) }}</label>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <textarea class="form--control" name="{{ $k }}" required>{{ $content->data_info->$k ?? '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($item == 'textarea-editor')
                                                    <div class="col-12 editor-wrapper">
                                                        <div class="row g-2 align-items-center">
                                                            <div class="col-lg-3">
                                                                <label class="form--label required">{{ __(keyToTitle($k)) }}</label>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <textarea class="form--control trumEdit" name="{{ $k }}">{{ $content->data_info->$k ?? '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($k == 'select')
                                                    @php $selectName = $item->name ?? '' @endphp

                                                    <div class="col-12">
                                                        <div class="row g-2 align-items-center">
                                                            <div class="col-lg-3">
                                                                <label class="form--label required">{{ __(keyToTitle($selectName)) }}</label>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <select class="form--control form-select" name="{{ $selectName }}" required>
                                                                    @foreach($item->options ?? [] as $selectItemKey => $selectOption)
                                                                        <option value="{{ $selectItemKey }}" @selected(($content->data_info->$selectName ?? '') == $selectItemKey)>
                                                                            {{ $selectOption }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-12">
                                                        <div class="row g-2 align-items-center">
                                                            <div class="col-lg-3">
                                                                <label class="form--label required">{{ __(keyToTitle($k)) }}</label>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <input type="text" class="form--control" name="{{ $k }}" value="{{ $content->data_info->$k ?? '' }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @stack('divend')
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if(isset($section->element) && $section->element)
        <div class="col-12">
            <div class="custom--card">
                <div class="card-header d-flex justify-content-sm-between justify-content-center align-items-center flex-wrap gap-3">
                    <h3 class="title">@lang('Items')</h3>

                    <div class="d-flex flex-wrap gap-2 justify-content-center align-items-center">
                        <div class="input--group">
                            <input type="search" class="form--control form--control--sm" name="search_table" placeholder="@lang('Search')...">
                            <button type="submit" class="btn btn--sm btn--icon btn--base">
                                <i class="ti ti-search"></i>
                            </button>
                        </div>

                        @if($section->element->modal)
                            <button type="button" class="btn btn--sm btn--base addBtn">
                                <i class="ti ti-circle-plus"></i> @lang('Add New')
                            </button>
                        @else
                            <a href="{{ route('admin.site.sections.element', $key) }}" class="btn btn--sm btn--base">
                                <i class="ti ti-circle-plus"></i> @lang('Add New')
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table-borderless table--striped table--responsive--sm custom-data-table">
                    <thead>
                        <tr>
                            <th>@lang('S.N.')</th>

                            @if(isset($section->element->images) && $section->element->images)
                                <th>@lang('Image')</th>
                            @endif

                            @foreach($section->element as $k => $type)
                                @if($k != 'modal')
                                    @if($type == 'text' || $type == 'icon')
                                        <th>{{ __(keyToTitle($k)) }}</th>
                                    @elseif($k == 'select')
                                        <th>{{ keyToTitle($section->element->$k->name ?? '') }}</th>
                                    @endif
                                @endif
                            @endforeach

                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($elements ?? [] as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                @if(isset($section->element->images) && $section->element->images)
                                    @php $firstKey = collect($section->element->images)->keys()[0] ?? '' @endphp

                                    <td>
                                        <div class="table-card-with-image justify-content-center">
                                            <div class="table-card-with-image__img bg--secondary">
                                                <img src="{{ getImage($activeThemeTrue . 'images/site/' . $key . '/' . ($data->data_info->$firstKey ?? ''), optional($section->element->images->$firstKey)->size ?? '') }}" alt="Image">
                                            </div>
                                        </div>
                                    </td>
                                @endif

                                @foreach($section->element as $k => $type)
                                    @if($k != 'modal')
                                        @if($type == 'text' || $type == 'icon')
                                            @if($type == 'icon')
                                                <td>
                                                    @php echo $data->data_info->$k ?? '' @endphp
                                                </td>
                                            @else
                                                <td>{{ __($data->data_info->$k ?? '') }}</td>
                                            @endif
                                        @elseif($k == 'select')
                                            @php $dataVal = $section->element->$k->name ?? '' @endphp

                                            <td>{{ $data->data_info->$dataVal ?? '' }}</td>
                                        @endif
                                    @endif
                                @endforeach

                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        @if($section->element->modal)
                                            @php
                                                $images = [];

                                                if (isset($section->element->images) && $section->element->images) {
                                                    foreach ($section->element->images as $imgKey => $imgs) {
                                                        $images[] = getImage($activeThemeTrue . 'images/site/' . $key . '/' . ($data->data_info->$imgKey ?? ''), optional($section->element->images->$imgKey)->size ?? '');
                                                    }
                                                }
                                            @endphp

                                            <button type="button" class="btn btn--sm btn--base editBtn" data-id="{{ $data->id }}" data-all="{{ json_encode($data->data_info) }}" @if(isset($section->element->images) && $section->element->images) data-images="{{ json_encode($images) }}" @endif>
                                                <i class="ti ti-edit"></i> @lang('Edit')
                                            </button>
                                        @else
                                            <a href="{{ route('admin.site.sections.element', [$key, $data->id]) }}" class="btn btn--sm btn--base">
                                                <i class="ti ti-edit"></i> @lang('Edit')
                                            </a>
                                        @endif

                                        <button type="button" class="btn btn--sm btn-outline--danger decisionBtn" data-question="@lang('Are you confirming the removal of this item?')" data-action="{{ route('admin.site.remove', $data->id) }}">
                                            <i class="ti ti-trash"></i> @lang('Delete')
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('partials.noData')
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal custom--modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addModalLabel">@lang('Add New Item')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.site.sections.content', $key) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="element">
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">
                                @foreach($section->element as $k => $type)
                                    @if($k != 'modal')
                                        @if($type == 'icon')
                                            <div class="col-12">
                                                <div class="row gy-2">
                                                    <div class="col-sm-4">
                                                        <label class="col-form--label required">{{ __(keyToTitle($k)) }}</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="input--group">
                                                            <input type="text" class="form--control iconPicker icon" name="{{ $k }}" autocomplete="off" required>
                                                            <span class="input-group-text input-group-addon" data-icon="ti ti-home"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($k == 'select')
                                            @php
                                                $selectName    = optional($section->element->$k)->name ?? '';
                                                $selectOptions = optional($section->element->$k)->options ?? [];
                                            @endphp

                                            <div class="col-12">
                                                <div class="row gy-2">
                                                    <div class="col-sm-4">
                                                        <label class="col-form--label required">{{ __(keyToTitle($selectName)) }}</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <select class="form--control form-select" name="{{ $selectName }}" required>
                                                            @foreach($selectOptions as $selectKey => $options)
                                                                <option value="{{ $selectKey }}">{{ $options }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($k == 'images')
                                            @foreach($type ?? [] as $imgKey => $image)
                                                <div class="col-12">
                                                    <div class="row gy-2">
                                                        <div class="col-sm-4">
                                                            <label class="col-form--label required">{{ __(keyToTitle($imgKey)) }}</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="upload__img mb-2">
                                                                <label for="addimage{{ $loop->index }}" class="upload__img__btn" title="{{ __(keyToTitle($imgKey)) }}">
                                                                    <i class="ti ti-camera"></i>
                                                                </label>

                                                                <input type="file" id="addimage{{ $loop->index }}" class="image-upload" name="image_input[{{ $imgKey }}]" accept=".jpeg, .jpg, .png" required>

                                                                <label for="addimage{{ $loop->index }}" class="upload__img-preview image-preview">
                                                                    <img src="{{ getImage('/', optional($section->element->images->$imgKey)->size ?? '') }}" alt="image">
                                                                </label>

                                                                <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none">
                                                                    <i class="ti ti-circle-x"></i>
                                                                </button>
                                                            </div>
                                                            <label class="text-center small">@lang('Supported files'):
                                                                <span class="fw-semibold text--base">@lang('jpeg'), @lang('jpg'), @lang('png').</span>

                                                                @if(optional($section->element->images->$imgKey)->size)
                                                                    @lang('Image Size') <span class="fw-semibold text--base">{{ $section->element->images->$imgKey->size }}@lang('px').</span>
                                                                @endif

                                                                @if(optional($section->element->images->$imgKey)->thumb)
                                                                    @lang('Thumb Size') <span class="fw-semibold text--base">{{ $section->element->images->$imgKey->thumb }}@lang('px').</span>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @elseif($type == 'textarea')
                                            <div class="col-12">
                                                <div class="row gy-2">
                                                    <div class="col-sm-4">
                                                        <label class="col-form--label required">{{ __(keyToTitle($k)) }}</label>
                                                    </div>
                                                    <div class="col-sm-8 editor-wrapper">
                                                        <textarea class="form--control" name="{{ $k }}" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($type == 'textarea-editor')
                                            <div class="col-12">
                                                <div class="row gy-2">
                                                    <div class="col-sm-4">
                                                        <label class="col-form--label required">{{ __(keyToTitle($k)) }}</label>
                                                    </div>
                                                    <div class="col-sm-8 editor-wrapper">
                                                        <textarea class="form--control trumEdit" name="{{ $k }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-12">
                                                <div class="row gy-2">
                                                    <div class="col-sm-4">
                                                        <label class="col-form--label required">{{ __(keyToTitle($k)) }}</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form--control" name="{{ $k }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn--sm btn--base generatorSubmit">@lang('Add')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal custom--modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="editModalLabel">@lang('Edit Item')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.site.sections.content', $key) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="element">
                        <input type="hidden" name="id">
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">
                                @foreach($section->element as $k => $type)
                                    @if($k != 'modal')
                                        @if($type == 'icon')
                                            <div class="col-12">
                                                <div class="row gy-2">
                                                    <div class="col-sm-4">
                                                        <label class="col-form--label required">{{ __(keyToTitle($k)) }}</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="input--group">
                                                            <input type="text" class="form--control iconPicker icon" name="{{ $k }}" autocomplete="off" required>
                                                            <span class="input-group-text input-group-addon existedIcon" data-icon="ti ti-home"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($k == 'select')
                                            @php
                                                $selectName    = optional($section->element->$k)->name ?? '';
                                                $selectOptions = optional($section->element->$k)->options ?? [];
                                            @endphp

                                            <div class="col-12">
                                                <div class="row gy-2">
                                                    <div class="col-sm-4">
                                                        <label class="col-form--label required">{{ __(keyToTitle($selectName)) }}</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <select class="form--control form-select" name="{{ $selectName }}" required>
                                                            @foreach($selectOptions as $selectKey => $options)
                                                                <option value="{{ $selectKey }}">{{ $options }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($k == 'images')
                                            @foreach($type ?? [] as $imgKey => $image)
                                                <div class="col-12">
                                                    <div class="row gy-2">
                                                        <div class="col-sm-4">
                                                            <label class="col-form--label">{{ __(keyToTitle($imgKey)) }}</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="upload__img mb-2">
                                                                <label for="updateimage{{ $loop->index }}" class="upload__img__btn" title="{{ __(keyToTitle($imgKey)) }}">
                                                                    <i class="ti ti-camera"></i>
                                                                </label>

                                                                <input type="file" id="updateimage{{ $loop->index }}" class="image-upload" name="image_input[{{ $imgKey }}]" accept=".jpeg, .jpg, .png">

                                                                <label for="updateimage{{ $loop->index }}" class="upload__img-preview image-preview">
                                                                    <img src="" class="imageModalUpdate{{ $loop->index }}" alt="image">
                                                                </label>

                                                                <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none">
                                                                    <i class="ti ti-circle-x"></i>
                                                                </button>
                                                            </div>
                                                            <label class="text-center small">@lang('Supported files'):
                                                                <span class="fw-semibold text--base">@lang('jpeg'), @lang('jpg'), @lang('png').</span>

                                                                @if(optional($section->element->images->$imgKey)->size)
                                                                    @lang('Image Size') <span class="fw-semibold text--base">{{ $section->element->images->$imgKey->size }}@lang('px').</span>
                                                                @endif

                                                                @if(optional($section->element->images->$imgKey)->thumb)
                                                                    @lang('Thumb Size') <span class="fw-semibold text--base">{{ $section->element->images->$imgKey->thumb }}@lang('px').</span>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @elseif($type == 'textarea')
                                            <div class="col-12">
                                                <div class="row gy-2">
                                                    <div class="col-sm-4">
                                                        <label class="col-form--label required">{{ __(keyToTitle($k)) }}</label>
                                                    </div>
                                                    <div class="col-sm-8 editor-wrapper">
                                                        <textarea class="form--control" name="{{ $k }}" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($type == 'textarea-editor')
                                            <div class="col-12">
                                                <div class="row gy-2">
                                                    <div class="col-sm-4">
                                                        <label class="col-form--label required">{{ __(keyToTitle($k)) }}</label>
                                                    </div>
                                                    <div class="col-sm-8 editor-wrapper">
                                                        <textarea class="form--control trumEdit" name="{{ $k }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-12">
                                                <div class="row gy-2">
                                                    <div class="col-sm-4">
                                                        <label class="col-form--label required">{{ __(keyToTitle($k)) }}</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form--control" name="{{ $k }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn--sm btn--base generatorSubmit">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <x-decisionModal/>
    @endif
@endsection

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
    <script src="{{ asset('assets/admin/js/page/ckEditor.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $('.addBtn').on('click', function () {
                let modal = $('#addModal');

                $('.editor-wrapper').find('.ck-editor').remove();

                ckEditorInitiate()

                modal.modal('show');
            });

            $('.editBtn').on('click', function () {
                let modal = $('#editModal');
                let obj = $(this).data('all');
                let images = $(this).data('images');

                if (images) {
                    for (let i = 0; i < images.length; i++) {
                        let imglocation = images[i];
                        $(`.imageModalUpdate${i}`).attr("src", imglocation);
                    }
                }

                $.each(obj, function (index, value) {
                    let element = modal.find('[name=' + index + ']')
                    element.val(value);

                    if (element.hasClass('iconpicker-element')) {
                        let iconElement = $(element).parent().find(".existedIcon");
                        iconElement.html(value)
                    }
                });

                ckEditorInitiate()

                modal.find('[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            ckEditorInitiate();

            function ckEditorInitiate() {
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
            }

            $('.iconPicker').iconpicker().on('iconpickerSelected', function (e) {
                $(this).closest('.input--group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
            });
        })(jQuery);
    </script>
@endpush
