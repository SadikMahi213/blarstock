@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <form action="" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-12">
                    <div class="custom--card">
                        <div class="card-body">
                            <div class="row g-lg-4 g-3">
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-3">
                                            <label class="col-form--label required">@lang('Heading')</label>
                                        </div>
                                        <div class="col-xxl-9">
                                            <input type="text" class="form--control" name="heading" value="{{ $cookie?->data_info?->heading ?? '' }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-3">
                                            <label class="col-form--label required">@lang('Short Details')</label>
                                        </div>
                                        <div class="col-xxl-9">
                                            <textarea class="form--control" name="short_details" required>{{ $cookie?->data_info?->short_details ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-3">
                                            <label class="col-form--label required">@lang('Details')</label>
                                        </div>
                                        <div class="col-xxl-9 editor-wrapper">
                                            <textarea class="form--control trumEdit" name="details" required>{{ $cookie?->data_info?->details ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row align-items-center gy-2">
                                        <div class="col-xxl-3">
                                            <label class="col-form--label required">@lang('Status')</label>
                                        </div>
                                        <div class="col-xxl-9">
                                            <div class="form-check form--switch">
                                                <input class="form-check-input" type="checkbox" name="status" @if($cookie?->data_info?->status) checked @endif>
                                            </div>
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

@push('page-script-lib')
    <script src="{{asset('assets/admin/js/page/ckEditor.js')}}"></script>
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
        })(jQuery);
    </script>
@endpush
