@php
     $whyChooseContent  = getSiteData('why_choose.content', true);
     $whyChooseElements = getSiteData('why_choose.element');
@endphp
<!-- ========== Why Choose Us Section Start ========== -->
<section class="why-choose-us py-120">
     <div class="why-choose-us__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/why_choose/' . $whyChooseContent?->data_info?->background_image, '2500x1500') }}"></div>
     <div class="container">
          <div class="row justify-content-center">
               <div class="col-xl-6 col-md-8">
                    <div class="section-heading text-center">
                         <h6 class="section-heading__subtitle slide-to-left">{{ __($whyChooseContent?->data_info?->title) }}</h6>
                         <h2 class="section-heading__title slide-to-left">{{ __($whyChooseContent?->data_info?->subtitle) }}</h2>
                    </div>
               </div>
          </div>
          <div class="row g-4 align-items-center justify-content-xl-between justify-content-center">
               <div class="col-xxl-6 col-xl-7 col-lg-10">
                    <div class="row g-4 why-choose-us__row box-container">
                         @foreach ($whyChooseElements as $whyChooseElement)
                              <div class="col-sm-6 box">
                                   <div class="why-choose-us__card">
                                        <div class="why-choose-us__card__icon box__content">@php echo $whyChooseElement?->data_info?->icon; @endphp</div>
                                        <div class="why-choose-us__card__content">
                                             <span class="why-choose-us__card__name box__content">{{ __($whyChooseElement?->data_info?->principle) }}</span>
                                             <span class="why-choose-us__card__desc box__content">{{ __($whyChooseElement?->data_info?->our_assurance) }}</span>
                                        </div>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </div>
               <div class="col-xl-5 col-lg-6 col-md-8">
                    <div class="why-choose-us__thumb">
                         <img src="{{ getImage($activeThemeTrue . 'images/site/why_choose/' . $whyChooseContent?->data_info?->image, '726x777') }}" alt="Image">
                    </div>
               </div>
          </div>
     </div>
</section>
<!-- ========== Why Choose Us Section End ========== -->
