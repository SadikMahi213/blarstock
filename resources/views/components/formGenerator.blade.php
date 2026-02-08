<div class="modal custom--modal fade" id="formGenerateModal" tabindex="-1" aria-labelledby="formGenerateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
         <div class="modal-content">
              <div class="modal-header">
                   <h2 class="modal-title" id="formGenerateModalLabel">@lang('Generate Form')</h2>
                   <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
              </div>
              <form class="{{ $formClassName ?? 'generate-form' }}">
                @csrf
                <input type="hidden" name="update_id" value="">

                   <div class="modal-body text-center">
                        <div class="row g-3 align-items-center">
                             <div class="col-12">
                                  <div class="row gy-2">
                                       <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Form Type')</label>
                                       </div>
                                       <div class="col-sm-8">
                                            <select name="form_type" class="form--control form--control--sm form-select" required>
                                                <option value="">@lang('Select One')</option>
                                                <option value="text">@lang('Text')</option>
                                                <option value="email">@lang('Email')</option>
                                                <option value="url">@lang('URL')</option>
                                                <option value="number">@lang('Number')</option>
                                                <option value="datetime">@lang('Date & Time')</option>
                                                <option value="date">@lang('Date')</option>
                                                <option value="time">@lang('Time')</option>
                                                <option value="textarea">@lang('Textarea')</option>
                                                <option value="select">@lang('Select')</option>
                                                <option value="checkbox">@lang('Checkbox')</option>
                                                <option value="radio">@lang('Radio')</option>
                                                <option value="file">@lang('File')</option>
                                            </select>
                                       </div>
                                  </div>
                             </div>
                             <div class="col-12">
                                  <div class="row gy-2">
                                       <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Is Required')</label>
                                       </div>
                                       <div class="col-sm-8">
                                            <select class="form--control form--control--sm form-select no-select2" name="is_required" required>
                                                <option value="">@lang('Select One')</option>
                                                <option value="required">@lang('Required')</option>
                                                <option value="optional">@lang('Optional')</option>
                                            </select>
                                       </div>
                                  </div>
                             </div>
                             <div class="col-12">
                                  <div class="row gy-2">
                                       <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Form Label')</label>
                                       </div>
                                       <div class="col-sm-8">
                                            <input type="text" class="form--control form--control--sm" name="form_label" required>
                                       </div>
                                  </div>
                             </div>

                             <div class="extra_area"></div>
                        </div>
                   </div>
                   <div class="modal-footer d-flex justify-content-end gap-2">
                        <button type="button" data-bs-dismiss="modal" class="btn btn--sm btn--secondary">@lang('Close')</button>
                        <button class="btn btn--sm btn--base generatorSubmit" type="submit">@lang('Add')</button>
                   </div>
              </form>
         </div>
    </div>
</div>

@push('page-script-lib')
    <script src="{{asset('assets/universal/js/form_generator.js')}}"></script>
@endpush
