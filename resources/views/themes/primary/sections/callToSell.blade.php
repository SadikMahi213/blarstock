@php
    $callToSellContent = getSiteData('call_to_sell.content', true);
@endphp
<!-- ========== Call To Sell Section Start ========== -->
<div class="call-to-sell py-120">
    <div class="container">
         <div class="call-to-action__card">
              <div class="row g-4 align-items-center justify-content-between">
                   <div class="col-lg-6 col-md-6">
                        <div class="call-to-sell__txt fade-bottom">
                             <div class="section-heading">
                                  <h6 class="section-heading__subtitle slide-to-left">{{ __($callToSellContent?->data_info?->heading) }}</h6>
                                  <h2 class="section-heading__title slide-to-left">{{ __($callToSellContent?->data_info?->subheading) }}</h2>
                             </div>
                             <p class="call-to-sell__desc">{{ __($callToSellContent?->data_info?->description) }}</p>
                             <a href="{{ route('user.asset.add') }}" class="btn btn--base">{{ __($callToSellContent?->data_info?->link_text) }}</a>
                        </div>
                   </div>
                   <div class="col-lg-5 col-md-6">
                        <div class="call-to-sell__img fade-bottom">
                             <img src="{{ getImage($activeThemeTrue . 'images/site/call_to_sell/' . $callToSellContent?->data_info?->image, '726x662') }}" alt="Image">
                        </div>
                   </div>
              </div>
         </div>
    </div>
</div>
<!-- ========== Call To Sell Section End ========== -->