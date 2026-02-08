@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="row g-4">
            <div class="col-xl-6">
                <div class="custom--card">
                    <div class="card-header">
                        <h3 class="title">@lang('Asset Upload Guideline')</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.setting.instruction') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-3 col-sm-4"><label class="col-form--label required">@lang('Heading')</label></div>
                                        <div class="col-xxl-9 col-sm-8">
                                            <input type="text" class="form--control" name="heading" value="{{ $setting->instruction?->heading ?? '' }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-3 col-sm-4"><label class="col-form--label required">@lang('Instruction')</label></div>
                                        <div class="col-xxl-9 col-sm-8 editor-wrapper">
                                            <textarea class="form--control trumEdit" name="instruction">{{ $setting->instruction?->instruction ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-3 col-sm-4"><label class="col-form--label required">@lang('Instruction Manual')</label></div>
                                        <div class="col-xxl-9 col-sm-8">
                                            <input type="file" class="form--control" name="instruction_manual" accept=".txt, .pdf" @if (!$setting->instruction_manual) required @endif>
                                            <p class="text--muted"><code class="fw-bold text--base">@lang('Please upload .txt or .pdf file')</code></p>
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
                </div>
            </div>
            <div class="col-xl-6">
                <div class="custom--card">
                    <div class="card-header">
                        <h3 class="title">@lang('Watermark Image')</h3>
                    </div>
                    <div class="card-body">
                        <div class="col-xxl-12 col-sm-12">
                            <div class="upload__img mb-2">
                                <label for="image" class="upload__img__btn" title="Background image"><i class="ti ti-camera"></i></label>
                
                                <input type="file" id="image" class="image-upload" name="watermark_image" accept=".png">
                
                                <label for="image" class="upload__img-preview image-preview">
                                    <img id="img" src="{{ getImage(getFilePath('watermarkImage') . '/' . $setting->watermark_image, getFileSize('watermarkImage')) }}" alt="image">
                                </label>
                
                                <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none">
                                    <i class="ti ti-circle-x"></i>
                                </button>
                            </div>
                            <label class="text-center small">@lang('Supported files'):
                                <span class="fw-semibold text--base">@lang('png').</span>
                                @lang('Image size') <span class="fw-semibold text--base">{{ getFileSize('watermarkImage') }}@lang('px').</span>
                            </label>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-12">
                <form action="{{ route('admin.basic.author.requirements') }}" method="POST">
                    @csrf

                    @include('admin.partials.formData', [$formHeading])
                </form>
            </div>
        </div>
    </div>

    <x-formGenerator />
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="btn btn--sm btn--base"><i class="ti ti-circle-arrow-left"></i> @lang('Back')</a>
@endpush

@push('page-script-lib')
    <script src="{{asset('assets/admin/js/page/ckEditor.js')}}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            'use strict';

            $('[name=watermark_image]').on('change', function(event) {
                event.preventDefault();

                let fileInput = event.target.files[0];

                let data = new FormData();
                data.append('watermark_image', fileInput);
                data.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    type       : "POST",
                    url        : "{{ route('admin.setting.watermark') }}",
                    data       : data,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            $('#img').attr('src', response.image_url);
                            showToasts('success', response.message);
                        } else {
                            showToasts('error', response.message);
                        }
                    },
                    error: function() {
                        showToasts('error', 'Something went wrong');
                    }
                });
            });

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
            
        })(jQuery);
    </script>
@endpush