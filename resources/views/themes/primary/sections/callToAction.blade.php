@php
    $callToActionContent = getSiteData('call_to_action.content', true);
@endphp
<!-- ========== Call To Action Section Start ========== -->
<section class="call-to-action py-120">
     <div class="container">
          <div class="call-to-action__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/call_to_action/' . $callToActionContent?->data_info?->bg_image, '1920x1080') }}">
               <div class="row justify-content-end align-items-center g-4">
                    <div class="col-lg-6">
                         <div class="call-to-action__txt fade-bottom">
                              <div class="section-heading section-heading-light">
                                   <h6 class="section-heading__subtitle slide-to-left">{{ __($callToActionContent?->data_info?->heading) }}</h6>
                                   <h2 class="section-heading__title slide-to-left">{{ __($callToActionContent?->data_info?->subheading) }}</h2>
                              </div>
                              <p>{{ __($callToActionContent?->data_info?->description) }}</p>
                              <a href="{{ route('user.register') }}" class="btn btn--base">{{ __($callToActionContent?->data_info?->link_text) }}</a>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</section>
<!-- ========== Call To Action Section End ========== -->