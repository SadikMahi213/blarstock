<div class="col-12">
    <div class="custom--card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="title">{{ __($formHeading) }}</h3>
            <button type="button" class="btn btn--sm btn--base form-generate-btn">
                <i class="ti ti-circle-plus"></i> @lang('Add New')
            </button>
        </div>
        <div class="card-body">
            <div class="row g-lg-4 g-3 addedField">
                @if($form && count((array) $form->form_data) > 0)
                    @foreach($form->form_data as $formData)
                        <div class="col-xl-3 col-lg-4 col-sm-6">
                            <div class="custom--card payment-method-card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-1.png') }}">
                                <div class="card-body" id="{{ $loop->index }}">
                                    <input type="hidden" name="form_generator[form_label][]" value="{{ $formData->name }}">
                                    <input type="hidden" name="form_generator[form_type][]" value="{{ $formData->type }}">
                                    <input type="hidden" name="form_generator[is_required][]" value="{{ $formData->is_required }}">
                                    <input type="hidden" name="form_generator[extensions][]" value="{{ $formData->extensions }}">
                                    <input type="hidden" name="form_generator[options][]" value="{{ implode(',', $formData->options) }}">

                                    <ul>
                                        <li>
                                            <i class="ti ti-tag text--success"></i> @lang('Label') : <span class="fw-semibold">{{ $formData->name }}</span>
                                        </li>
                                        <li>
                                            <i class="ti ti-forms text--info"></i> @lang('Type') : <span class="fw-semibold">{{ $formData->type }}</span>
                                        </li>
                                        <li>
                                            <i class="ti ti-asterisk text--danger"></i> @lang('Required') : <span class="fw-semibold">{{ $formData->is_required == 'required' ? trans('Yes') : trans('No') }}</span>
                                        </li>
                                    </ul>

                                    @php
                                        $jsonData = json_encode([
                                            'type'        => $formData->type,
                                            'is_required' => $formData->is_required,
                                            'label'       => $formData->name,
                                            'extensions'  => explode(',',$formData->extensions) ?? 'null',
                                            'options'     => $formData->options,
                                            'old_id'      => '',
                                        ]);
                                    @endphp

                                    <div class="d-flex pt-3 mt-3 border-top d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn--sm btn--base editFormData" data-form_item="{{ $jsonData }}" data-update_id="{{ $loop->index }}">
                                            <i class="ti ti-edit"></i> @lang('Edit')
                                        </button>
                                        <button type="button" class="btn btn--sm btn--danger removeFormData">
                                            <i class="ti ti-trash"></i> @lang('Delete')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        @include('partials.noData')
                    </div>
                @endif
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
            </div>
        </div>
    </div>
</div>

@push('page-script')
    <script>
        "use strict";

        let formGenerator = new FormGenerator();
        formGenerator.totalField = {{ $form ? count((array) $form->form_data) : 0 }};

        let noDataImage = `{{ asset('assets/universal/images/no-data.png') }}`;
        let emptyMessage = `{{ __($emptyMessage) }}`;
    </script>

    <script src="{{ asset('assets/universal/js/form_actions.js') }}"></script>
@endpush
