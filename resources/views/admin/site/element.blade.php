@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="custom--card">
            <div class="card-body">
                <form class="row g-4" action="{{ route('admin.site.sections.content', $key) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="type" value="element">

                    @isset($data)
                        <input type="hidden" name="id" value="{{$data->id}}">
                    @endisset

                    @php $imgCount = 0; @endphp

                    @foreach($section->element ?? [] as $k => $item)
                        @if($k == 'images')
                            @php $imgCount = collect($item)->count(); @endphp

                            @foreach($item as $imgKey => $image)
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="upload__img mb-2">
                                        <label for="image{{ $loop->index }}" class="upload__img__btn" title="{{ __(keyToTitle($imgKey)) }}"><i class="ti ti-camera"></i></label>

                                        <input type="file" id="image{{ $loop->index }}" class="image-upload" name="image_input[{{ $imgKey }}]" accept=".jpeg, .jpg, .png" @if (!isset($data)) required @endif>

                                        <label for="image{{ $loop->index }}" class="upload__img-preview image-preview">
                                            <img src="{{ getImage($activeThemeTrue . 'images/site/' . $key . '/' . ($data?->data_info?->$imgKey ?? ''), (optional($section->element->images->$imgKey)->size ?? '')) }}" alt="image">
                                        </label>

                                        <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none"><i class="ti ti-circle-x"></i></button>
                                    </div>
                                    <label class="text-center small">@lang('Supported files'):
                                        <span class="fw-semibold text--base">@lang('jpeg'), @lang('jpg'), @lang('png').</span>
                                        @if(optional($section->element->images->$imgKey)->size)
                                            @lang('Image size') <span class="fw-semibold text--base">{{ $section->element->images->$imgKey->size }}@lang('px').</span>
                                        @endif

                                        @if(optional($section->element->images->$imgKey)->thumb)
                                            @lang('Thumb size') <span class="fw-semibold text--base">{{ $section->element->images->$imgKey->thumb }}@lang('px').</span>
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
                                                            <input type="text" class="form--control iconPicker icon" name="{{ $k }}" value="{{ $data?->data_info?->$k ?? '' }}" autocomplete="off" required>
                                                            <span class="input-group-text input-group-addon" data-icon="ti ti-home" role="iconpicker">@php echo $data?->data_info?->$k ?? ''; @endphp</span>
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
                                                        <textarea class="form--control" name="{{ $k }}" required>{{ $data?->data_info?->$k ?? ''}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($item == 'textarea-editor')
                                            <div class="col-12 editor-wrapper">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-lg-3">
                                                        <label class="form--label required">{{ __(keyToTitle($k)) }}</label>
                                                    </div>
                                                    <div class="col-lg-9 editor-wrapper">
                                                        <textarea class="form--control trumEdit" name="{{ $k }}">{{ $data?->data_info?->$k ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($k == 'select')
                                            @php $selectName = $item->name; @endphp

                                            <div class="col-12">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-lg-3">
                                                        <label class="form--label required">{{ __(keyToTitle($selectName)) }}</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <select class="form--control form-select" name="{{ $selectName }}" required>
                                                            @foreach($item->options ?? [] as $selectItemKey => $selectOption)
                                                                <option value="{{ $selectItemKey }}" @if(($data?->data_info?->$selectName ?? '') == $selectItemKey) selected @endif>{{ $selectOption }}</option>
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
                                                        <input type="text" class="form--control" name="{{ $k }}" value="{{ $data?->data_info?->$k ?? '' }}" required>
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

                    <div class="col-12">
                        <div class="d-flex justify-content-center">
                            <button class="btn btn--base px-4" type="submit">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

            $('.iconPicker').iconpicker().on('iconpickerSelected', function (e) {
                $(this).closest('.input--group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
            });
        })(jQuery);
    </script>
@endpush
