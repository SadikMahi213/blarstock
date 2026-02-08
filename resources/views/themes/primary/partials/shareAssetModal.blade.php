<!-- Add To Collection Modal -->
<div class="modal custom--modal fade" id="shareAssetModal" tabindex="-1" aria-labelledby="shareAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
              <div class="modal-header">
                   <h2 class="modal-title fs-5" id="shareAssetModalLabel">@lang('Share')</h2>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                   <ul class="social-list justify-content-center mb-4">
                        <li class="social-list__item">
                              <a target="_blank" href="" class="social-list__link flex-center facebookLink"><i class="ti ti-brand-facebook"></i></a> 
                         </li>
                        <li class="social-list__item">
                              <a target="_blank" href="" class="social-list__link flex-center xLink"><i class="ti ti-brand-x"></i></a> 
                         </li>
                        <li class="social-list__item">
                              <a target="_blank" href="" class="social-list__link flex-center linkedInLink"><i class="ti ti-brand-linkedin"></i></a> 
                         </li>
                        <li class="social-list__item">
                              <a href="https://www.instagram.com/" target="_blank" href="" class="social-list__link flex-center instagramLink"><i class="ti ti-brand-instagram"></i></a> 
                         </li>
                   </ul>
                   <div class="input--group">
                        <input type="text" class="form--control form--control--sm copyUrl" value="" readonly>
                        <button role="button" class="btn btn--sm btn--base shareLinkCopyBtn"><i class="ti ti-copy"></i></button>
                   </div>
              </div>
         </div>
    </div>
</div>