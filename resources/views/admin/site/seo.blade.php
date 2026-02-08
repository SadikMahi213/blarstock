@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="custom--card">
            <div class="card-body">
                <form class="row g-lg-4 g-3" action="{{ route('admin.site.sections.content', 'seo')}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="type" value="data">
                    <input type="hidden" name="seo_image" value="1">

                    <div class="col-xxl-9 col-lg-8 col-md-7 order-md-1 order-2">
                        <div class="row g-lg-4 g-3">
                            <div class="col-12">
                                <label class="form--label required">@lang('Meta Keywords')</label>
                                <select class="form--control select-2" name="keywords[]" multiple="multiple" required>
                                    @if($seo?->data_info?->keywords ?? null)
                                        @foreach($seo->data_info->keywords as $option)
                                            <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Social Title')</label>
                                <input type="text" class="form--control" name="social_title" value="{{ $seo?->data_info?->social_title ?? '' }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Meta Description')</label>
                                <textarea name="description" class="form--control" required>{{ $seo?->data_info?->description ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Social Description')</label>
                                <textarea class="form--control" name="social_description" required>{{ $seo?->data_info?->social_description ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-md-5 order-md-2 order-1">
                        <div class="upload__img mb-2">
                            <label for="seo" class="upload__img__btn"><i class="ti ti-camera"></i></label>
                            <input type="file" id="seo" class="image-upload" name="image_input" accept=".png, .jpg, .jpeg">
                            <label for="seo" class="upload__img-preview image-preview">
                                <img src="{{ getImage(getFilePath('seo').'/'. $seo?->data_info?->image ?? '', getFileSize('seo')) }}" alt="seo-image">
                            </label>
                            <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none"><i class="ti ti-circle-x"></i></button>
                        </div>
                        <label class="text-center small">@lang('Supported files'): <span class="fw-semibold text--warning">@lang('jpeg'), @lang('jpg'), @lang('png').</span> @lang('Image size') <span class="fw-semibold text--warning">{{getFileSize('seo')}}@lang('px').</span></label>
                    </div>

                    <div class="col-12 order-3">
                        <div class="d-flex justify-content-center">
                            <button class="btn btn--base px-4" type="submit">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection