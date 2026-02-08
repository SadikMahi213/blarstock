@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="alert alert--base">@lang('When adding a new keyword, ensure it\'s entered precisely, with no extra spaces, as it will only apply to the chosen language').</div>
    </div>
    <div class="col-12">
        <div class="row g-lg-4 g-3">
            @foreach ($languages as $language)
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="custom--card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-2.png') }}">
                        <div class="card-body language-card">
                            <div class="language-card__icon">
                                <i class="ti ti-language"></i>
                            </div>
                            <h3 class="language-card__name">{{ __($language->name) }} - {{ __($language->code) }}</h3>

                            @php echo $language->statusBadge; @endphp
                            
                            @if ($language->is_default)
                                <span class="badge badge--primary">@lang('Default')</span>
                            @endif

                            <div class="d-flex justify-content-center align-items-center gap-2 border-top pt-3 mt-3">
                                <button type="button" class="btn btn--sm btn--icon btn--base editBtn" data-resource="{{ $language }}" title="@lang('Edit')">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <a href="{{ route('admin.language.translate.keyword', $language->id) }}" class="btn btn--sm btn--icon btn--info" title="@lang('Translate')">
                                    <i class="ti ti-language"></i>
                                </a>

                                @if ($language->status)
                                    <button type="button" class="btn btn--sm btn--icon btn--warning decisionBtn" title="@lang('Inactive')" data-question="@lang('Are you confirming the inactivation of this language')?" data-action="{{ route('admin.language.status', $language->id) }}">
                                        <i class="ti ti-ban"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn--sm btn--icon btn--success decisionBtn" title="@lang('Active')" data-question="@lang('Are you confirming the activation of this language')?" data-action="{{ route('admin.language.status', $language->id) }}">
                                        <i class="ti ti-circle-check"></i>
                                    </button>
                                @endif

                                @if($language->id != 1)
                                    <button type="button" class="btn btn--sm btn--icon btn--danger decisionBtn" title="@lang('Delete')" data-question="@lang('Are you confirming the removal of this language from the system')?" data-action="{{ route('admin.language.delete', $language->id) }}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="addLanguageModal" tabindex="-1" aria-labelledby="addLanguageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addLanguageModalLabel">@lang('Add New Language')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="{{ route('admin.language.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form--label required">@lang('Name')</label>
                                <input type="text" class="form--control form--control--sm" name="name" placeholder="@lang('Turkish')" required>
                            </div>
                            <div class="form-group">
                                <label class="form--label required">@lang('Code')</label>
                                <input type="text" class="form--control form--control--sm" name="code" placeholder="@lang('tr')" required>
                            </div>
                            <div class="d-flex flex wrap justify-content-between">
                                <label class="form--label required">@lang('Default Language')</label>
                                <div class="form-check form--switch">
                                    <input type="checkbox" class="form-check-input" name="is_default">
                                </div>
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
        <div class="custom--modal modal fade" id="editLanguageModal" tabindex="-1" aria-labelledby="editLanguageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                 <div class="modal-content">
                      <div class="modal-header">
                           <h2 class="modal-title" id="editLanguageModalLabel">@lang('Update Language')</h2>
                           <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                      </div>
                      <form method="POST">
                            @csrf
                           <div class="modal-body">
                                <div class="form-group">
                                    <label class="form--label required">@lang('Name')</label>
                                    <input type="text" class="form--control form--control--sm" name="name" placeholder="@lang('Portuguese')" required>
                                </div>

                                <div class="d-flex flex wrap justify-content-between">
                                     <label class="form--label required">@lang('Default Language')</label>
                                     <div class="form-check form--switch">
                                         <input type="checkbox" class="form-check-input" name="is_default">
                                     </div>
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
        <div class="custom--modal modal fade" id="languageKeywordModal" tabindex="-1" aria-labelledby="languageKeywordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="languageKeywordModalLabel">@lang('All Keywords')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert--base mb-3">@lang('Most language keywords are here, but some may be missing due to database variations. You can manually add or import them from any language\'s translate page').</div>
                        <div class="keyword-wrap">
                            <button class="btn btn--sm btn--base copy-keywords"><i class="ti ti-copy"></i> @lang('Copy')</button>
                            <textarea class="form--control form--control--sm keyword-textarea langKeys" readonly></textarea>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <button data-bs-toggle="modal" data-bs-target="#addLanguageModal" class="btn btn--sm btn--base">
        <i class="ti ti-circle-plus"></i> @lang('Add New')
    </button>
    <button data-bs-toggle="modal" data-bs-target="#languageKeywordModal" class="btn btn--sm btn--info keywordBtn">
        <i class="ti ti-file-text"></i> @lang('Keywords')
    </button>
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $('.editBtn').on('click', function () {
                let modal = $('#editLanguageModal');
                let resource = $(this).data().resource;

                modal.find('[name=name]').val(resource.name);

                if (resource.is_default) {
                    modal.find('[name=is_default]').prop('checked', true);
                } else {
                    modal.find('[name=is_default]').prop('checked', false);
                }

                modal.find('form').attr('action', `${ '{{ route('admin.language.store') }}' }/${resource.id}`);

                modal.modal('show');
            });

            $('.keywordBtn').on('click', function (e) {
                e.preventDefault();

                $.get("{{ route('admin.language.keywords') }}", {}, function (data) {
                    $('.langKeys').text(data);
                });
            });

            $('.copy-keywords').on('click', function () {
                let inputElement = $('.keyword-textarea');
                inputElement.select();
                document.execCommand('copy');
                $(this).addClass('btn--success').removeClass('btn--base').html('<i class="ti ti-copy-check"></i> @lang("Copied")');

                setTimeout(function () {
                    $('.copy-keywords').addClass('btn--base').removeClass('btn--success').html('<i class="ti ti-copy"></i> @lang("Copy")');
                }, 1500);
            });
        })(jQuery);
    </script>
@endpush
