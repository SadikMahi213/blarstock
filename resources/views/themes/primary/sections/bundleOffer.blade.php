@php
     $bundleOfferContent  = getSiteData('bundle_offer.content', true);
     $bundleOfferElements = getSiteData('bundle_offer.element');
@endphp
<!-- ========== Bundle Offer Section Start ========== -->
<section class="bundle-offer py-120">
     <div class="bundle-offer__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/bundle_offer/' . $bundleOfferContent?->data_info?->background_image, '1920x1280') }}"></div>
     <div class="container">
          <div class="row justify-content-center">
               <div class="col-xl-6 col-md-7">
                    <div class="section-heading text-center">
                         <h6 class="section-heading__subtitle slide-to-left">{{ __($bundleOfferContent?->data_info?->heading) }}</h6>
                         <h2 class="section-heading__title slide-to-left">{{ __($bundleOfferContent?->data_info?->subheading) }}</h2>
                    </div>
               </div>
          </div>
          <div class="row g-4 bundle-offer__row box-container">
               @foreach ($bundleOfferElements as $bundleOfferElement)
                    <div class="col-lg-3 col-sm-6 col-xsm-6 box">
                         <div class="bundle-offer__card">
                              <span class="bundle-offer__card__icon box__content">@php echo $bundleOfferElement?->data_info?->icon; @endphp</span>
                              <div class="bundle-offer__card__txt">
                                   <span class="bundle-offer__card__name box__content">{{ __($bundleOfferElement?->data_info?->title) }}</span>
                                   <span class="bundle-offer__card__desc box__content">{{ __($bundleOfferElement?->data_info?->description) }}</span>
                              </div>
                         </div>
                    </div>     
               @endforeach
          </div>
     </div>
</section>
<!-- ========== Bundle Offer Section End ========== -->