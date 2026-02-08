<div class="modal custom--modal fade" id="signInfoModal" tabindex="-1" aria-labelledby="signInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
              <div class="modal-header">
                   <h2 class="modal-title fs-5" id="signInfoModalLabel"></h2>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                   <div class="row g-3 align-items-center">
                        <div class="col-sm-12">
                             <label class="col-form--label">@lang('Authentication required. Please sign in to proceed.')</label>
                        </div>
                        
                        <div class="col-12">
                             <a href="{{ route('user.login.form') }}" class="btn btn--sm btn--base w-100">@lang('Sign in')</a>
                        </div>
                    </div>
              </div>
         </div>
    </div>
</div>