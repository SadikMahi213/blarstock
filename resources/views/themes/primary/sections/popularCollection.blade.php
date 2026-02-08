@php
    $popularCollectionContent = getSiteData('popular_collection.content', true);
    $userExcludedColumn = ['image', 'cover_image', 'firstname', 'lastname', 'email', 'country_code', 'country_name', 'mobile', 'author_data', 'reason', 'joined_at', 'ref_by', 'balance', 'password', 'address', 'status', 'kyc_data', 'kc', 'ec', 'sc', 'ver_code', 'ver_code_send_at', 'ts', 'tc', 'tsc', 'ban_reason', 'remember_token'];
        
     $collections = App\Models\Collection::active()->public()->whereHas('images')
                    ->with([
                        'user' => fn($query) => $query->select(getSelectedColumns('users', $userExcludedColumn)),
                        'images' => fn($query) => $query->select('images.id', 'images.image_name', 'images.status', 'images.tags')])
                    ->latest()->limit(8)->get();
@endphp

<!-- ========== Popular Collection Section Start ========== -->
<section class="collection py-120">
    <div class="container">
         <div class="row justify-content-center">
              <div class="col-xl-6 col-md-7">
                   <div class="section-heading text-center">
                        <h6 class="section-heading__subtitle slide-to-left">{{ __($popularCollectionContent?->data_info?->title) }}</h6>
                        <h2 class="section-heading__title slide-to-left">{{ __($popularCollectionContent?->data_info?->subtitle) }}</h2>
                   </div>
              </div>
         </div>

         @if ($collections->isNotEmpty())
               <div class="row g-4 justify-content-center">
                    @php
                         $defaultCollectionContent = getSiteData('default_collection.content', true);

                         $defaultImages = [
                              getImage($activeThemeTrue . 'images/site/default_collection/' . $defaultCollectionContent?->data_info?->first_image, null),
                              getImage($activeThemeTrue . 'images/site/default_collection/' . $defaultCollectionContent?->data_info?->second_image, null),
                              getImage($activeThemeTrue . 'images/site/default_collection/' . $defaultCollectionContent?->data_info?->third_image, null),
                         ];
                    @endphp
                    @foreach ($collections as $collection)
                         @php
                              $assets = $collection->images->pluck('tags')->toArray();
                              $tags   = array_slice(array_unique(array_merge(...$assets)), 0, 6);

                              $finalImages = [];

                              $collectionImages = $collection->images->shuffle();

                              foreach ($collectionImages as $img) {
                                   $finalImages[] = imageUrl(getFilePath('stockImage'), $img->image_name);
                              }

                              if (count($finalImages) < 3) {
                                   $remaining = 3 - count($finalImages);
                                   $finalImages = array_merge($finalImages, array_slice($defaultImages, 0, $remaining));
                              }

                              shuffle($finalImages);
                              $finalImages = array_slice($finalImages, 0, 3);
                         @endphp

                         <div class="col-xl-3 col-lg-4 col-sm-6 col-xsm-6 fade-bottom">
                              <div class="collection__card">
                                   <a href="{{ route('collection.detail', [encrypt($collection->id), slug($collection->title)]) }}" class="collection__card__thumb">
                                        <span class="collection__card__thumb__badge">{{ formatNumber($collection->images->count()) }} @lang('Assets')</span>
                                        <div class="collection__card__thumb__inner">
                                             @foreach ($finalImages as $imgSrc)
                                                  <div class="collection__card__thumb__img">
                                                       <img src="{{ $imgSrc }}" alt="Image">
                                                  </div>
                                             @endforeach
                                        </div>
                                   </a>
                                   <div class="collection__card__txt">
                                        <h3 class="collection__card__name"><a href="{{ route('collection.detail', [encrypt($collection->id), slug($collection->title)]) }}">{{ __($collection->title) }}</a></h3>
                                        <span class="collection__card__info">@lang('Created by') <a href="{{ $collection->user->author_status == ManageStatus::AUTHOR_APPROVED ? route('author.profile', [encrypt($collection->user_id), slug($collection->user->author_name)]) : route('member.user.profile', [encrypt($collection->user_id), slug($collection->user->username)]) }}">{{ __($collection->user->username) }}</a></span>
                                        @if (count($tags))
                                             <div class="collection__card__tags">
                                                  @foreach ($tags as $tag)
                                                  <a href="{{ route('all.assets', ['tag' => $tag]) }}" class="collection__card__tag">{{ (ucfirst($tag)) }}</a>
                                                  @endforeach
                                             </div>
                                        @endif
                                   </div>
                              </div>
                         </div>
                    @endforeach
               </div>
          @else
               <div class="product__row product__row-empty">
                   <div class="no-data-found">
                       <img src="{{ getImage('assets/universal/images/noData.png') }}" alt="No Data Found">
                       <span>@lang('No Trending Collection Found')</span>
                   </div>
               </div>
           @endif
    </div>
</section>
<!-- ========== Popular Collection Section End ========== -->