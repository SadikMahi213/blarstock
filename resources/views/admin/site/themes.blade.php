@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="row g-lg-4 g-3">
            @foreach ($themes as $theme)
                <div class="col-lg-4 col-sm-6">
                    <div class="custom--card theme-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="title">{{ __(keyToTitle($theme['name'])) }}</h3>
                            @if ($setting->active_theme == $theme['name'])
                                <button type="button" class="btn btn--sm btn--success" disabled>
                                    <i class="ti ti-circle-check"></i> @lang('Activated')
                                </button>
                            @else
                                <button type="button" class="btn btn--sm btn--base activeBtn" data-name="{{ $theme['name'] }}">
                                    @lang('Activate')
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="theme-card__img">
                                <img src="{{ $theme['image'] }}" alt="Theme">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="themeActivateModal" tabindex="-1" aria-labelledby="themeActivateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                 <div class="modal-content">
                      <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                      <div class="modal-body modal-alert">
                           <div class="text-center">
                                <div class="modal-thumb">
                                     <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                                </div>
                                <h2 class="modal-title" id="themeActivateModalLabel">@lang('Make Your Decision')</h2>
                                <div class="onboarding-info">@lang('Are you confirming the activation of this theme for frontend')?</div>
    
                                <form action="" method="POST">
                                    @csrf

                                    <input type="hidden" name="name">
    
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

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $('.activeBtn').on('click', function () {
                let modal = $('#themeActivateModal');

                modal.find('[name=name]').val($(this).data('name'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
