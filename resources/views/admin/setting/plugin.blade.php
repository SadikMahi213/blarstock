@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="row g-lg-4 g-3">
            @foreach ($plugins as $plugin)    
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="custom--card bg-img" data-background-image="{{ asset('assets/admin/images/card-bg-2.png') }}">
                        <div class="card-body plugin-card">
                            <div class="plugin-card__icon">
                                <img src="{{ getImage(getFilePath('plugin').'/'.$plugin->image) }}" alt="Image">
                            </div>
                            <h3 class="plugin-card__name">{{ __($plugin->name) }}</h3>

                            @php echo $plugin->statusBadge; @endphp

                            <div class="d-flex justify-content-center gap-2 border-top pt-3 mt-3">
                                <button type="button" class="btn btn--sm btn--base editBtn"
                                    data-route="{{ route('admin.plugin.setting.update', $plugin->id) }}"
                                    data-name="{{ __($plugin->name) }}"
                                    data-shortcode="{{ json_encode($plugin->shortcode) }}"
                                    data-status="{{ $plugin->status }}">

                                    <i class="ti ti-edit"></i> @lang('Edit')
                                </button>

                                @if ($plugin->status)
                                    <button type="button" class="btn btn--sm btn--warning decisionBtn" data-question="@lang('Are you confirming the inactivation of this plugin')?" data-action="{{ route('admin.plugin.status', $plugin->id) }}"><i class="ti ti-ban"></i> @lang('Inactive')</button>
                                @else
                                    <button type="button" class="btn btn--sm btn--success decisionBtn" data-question="@lang('Are you confirming the activation of this plugin')?" data-action="{{ route('admin.plugin.status', $plugin->id) }}"><i class="ti ti-circle-check"></i> @lang('Active')</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="updatePluginModal" tabindex="-1" aria-labelledby="updatePluginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                 <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="updatePluginModalLabel">
                            @lang('Update plugin') : <span class="plugin-name"></span>
                        </h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-4 plugin-html">
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

    <x-decisionModal />
@endsection

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $('.editBtn').on('click', function () {
                let modal     = $('#updatePluginModal');
                let shortcode = $(this).data('shortcode');
                let id        = $(this).data('id');
                let html      = '';

                modal.find('.plugin-name').text($(this).data('name'));
                modal.find('form').attr('action', $(this).data('route'));

                $.each(shortcode, function (key, item) {
                    html += `<div class="col-12">
                                <label class="form--label required">${item.title}</label>
                                <input type="text" class="form--control form--control--sm" name="${key}" placeholder="----" value="${item.value}" required>
                            </div>`;
                });

                modal.find('.plugin-html').html(html);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

