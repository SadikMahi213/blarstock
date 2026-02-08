@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <table class="table table-borderless table--striped table--responsive--xl">
            <thead>
                <tr>
                    <th>@lang('S.N.')</th>
                    <th>@lang('Key')</th>
                    <th>{{ __($language->name) }}</th>
                    <th>@lang('Actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($json as $key => $value)   
                    <tr>
                        <td>{{ $json->firstItem() + $loop->index }}</td>
                        <td>{{ $key }}</td>
                        <td>{{ $value }}</td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn--sm btn--base editBtn" data-key="{{ $key }}" data-value="{{ $value }}">
                                    <i class="ti ti-edit"></i> @lang('Edit')
                                </button>
                                <button type="button" class="btn btn--sm btn-outline--danger deleteBtn" data-key="{{ $key }}" data-value="{{ $value }}">
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

        @php echo paginateLinks($json); @endphp
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="importKeywordModal" tabindex="-1" aria-labelledby="importKeywordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="importKeywordModalLabel">@lang('Import Keywords')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form--label required">@lang('Import From')</label>
                            <select class="form--control form-select" name=select_lang required>
                                <option value="">@lang('Select One')</option>
                                <option value="999">@lang('System')</option>

                                @foreach ($allLang as $lang)
                                    @if ($lang->id != $language->id)
                                        <option value="{{ $lang->id }}">{{ __($lang->name) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="button" class="btn btn--sm btn--base importLang">@lang('Import')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="addKeywordModal" tabindex="-1" aria-labelledby="addKeywordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addKeywordModalLabel">@lang('Add New Keyword')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="{{ route('admin.language.store.key', $language->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form--label required">@lang('Key')</label>
                                <textarea class="form--control form--control--sm" name="key" required></textarea>
                            </div>
                            <div class="form-group mb-0">
                                <label class="form--label required">@lang('Value')</label>
                                <textarea class="form--control form--control--sm" name="value" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer gap-2">
                            <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn--sm btn--base">@lang('Add')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="editKeywordModal" tabindex="-1" aria-labelledby="editKeywordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="editKeywordModalLabel">@lang('Update Keyword')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="{{ route('admin.language.update.key', $language->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="key">

                        <div class="modal-body">
                            <div class="form-group mb-0">
                                <label class="form--label formHeading required"></label>
                                <textarea class="form--control form--control--sm" name="value" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer gap-2">
                            <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn--sm btn--base">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="deleteKeywordModal" tabindex="-1" aria-labelledby="deleteKeywordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                 <div class="modal-content">
                      <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                      <div class="modal-body modal-alert">
                           <div class="text-center">
                                <div class="modal-thumb">
                                     <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                                </div>
                                <h2 class="modal-title" id="deleteKeywordModalLabel">@lang('Make Your Decision')</h2>
                                <div class="onboarding-info">@lang('Are you confirming the removal of this key from the current language')?</div>
    
                                <form action="{{route('admin.language.delete.key', $language->id)}}" method="POST">
                                    @csrf

                                    <input type="hidden" name="key">
                                    <input type="hidden" name="value">
    
                                    <div class="d-flex justify-content-center gap-2 mt-3">
                                        <button class="btn btn--sm btn--base" type="submit">@lang('Yes')</button>
                                        <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('No')</button>
                                    </div>
                                </form>
                           </div>
                      </div>
                 </div>
            </div>
       </div>
    </div>

@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search by key" />
    <button data-bs-toggle="modal" data-bs-target="#addKeywordModal" class="btn btn--sm btn--base">
        <i class="ti ti-circle-plus"></i> @lang('Add New')
    </button>
    <button data-bs-toggle="modal" data-bs-target="#importKeywordModal" class="btn btn--sm btn--info">
        <i class="ti ti-file-text"></i> @lang('Import')
    </button>
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $('.importLang').on('click', function(e){
                var id = $('[name=select_lang]').val();

                if(id ==''){
                    showToasts('error','Invalide selection');
                    return 0;
                }else{
                    $.ajax({
                        type:"post",
                        url:"{{ route('admin.language.import.lang') }}",
                        data:{
                            id : id,
                            toLangId : "{{ $language->id }}",
                            _token: "{{ csrf_token() }}"
                        },
                        success:function(data){
                            if (data == 'success'){
                                showToasts('success','Import keywords success');
                                window.location.href = "{{ url()->current() }}"
                            }
                        }
                    });
                }
            });

            $('.editBtn').on('click', function () {
                let modal = $('#editKeywordModal');

                modal.find('.formHeading').text($(this).data('key'));
                modal.find('[name=key]').val($(this).data('key'));
                modal.find('[name=value]').val($(this).data('value'));
                modal.modal('show');
            });

            $('.deleteBtn').on('click', function () {
                let modal = $('#deleteKeywordModal');

                modal.find('[name=key]').val($(this).data('key'));
                modal.find('[name=value]').val($(this).data('value'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

