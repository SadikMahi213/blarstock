{{-- Decision Modal --}}
<div class="col-12">
    <div class="custom--modal modal fade" id="decisionModal" tabindex="-1" aria-labelledby="decisionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
             <div class="modal-content">
                  <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                  <div class="modal-body modal-alert">
                       <div class="text-center">
                            <div class="modal-thumb">
                                 <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                            </div>
                            <h2 class="modal-title" id="decisionModalLabel">@lang('Make Your Decision')</h2>
                            <div class="onboarding-info question"></div>

                            <form action="" method="POST">
                                @csrf

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

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $(document).on('click','.decisionBtn', function () {
                let modal = $('#decisionModal');
                let data  = $(this).data();

                modal.find('.question').text(`${data.question}`);
                modal.find('form').attr('action', `${data.action}`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
