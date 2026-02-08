@if ($assets->isNotEmpty())
     <div class="product__row">
          @foreach ($assets as $asset)
               <div class="product__card {{ $asset->video ? 'product-video' : 'product-image' }}">
                    <a href="{{ route('asset.detail', [encrypt($asset->id), slug($asset->title)]) }}" @if($asset->video) data-video-src="{{ videoFileUrl($asset->video) }}" @endif class="product__card__thumb">
                         <img src="{{ imageUrl(getFilePath('stockImage'), $asset->thumb) }}" alt="Image">
                    </a>
                    
                    <div class="product__card__txt">
                         <p class="product__card__name">{{ __($asset->category->name) }}</p>
                         <a href="{{ route('all.assets', ['category_id' => $asset->category_id]) }}" class="product__card__btn"><i class="ti ti-stack-2"></i> @lang('View Category')</a>
                    </div>

                    <div class="product__card__badges">
                         @if ($asset->video)
                              <span class="product__card__badge video"><i class="ti ti-video"></i></span>
                         @endif
               
                         @if ($asset->imageFiles->isNotEmpty())
                              <span class="product__card__badge premium"><i class="ti ti-crown"></i></span>
                         @endif
                    </div>
          
                    <div class="product__card__action">
                         @if ($user)
                              @php
                                   $isLiked = $asset->likes->isNotEmpty();
                              @endphp
               
                              <button title="{{ $isLiked ? trans('Liked') : trans('Like') }}" data-bs-placement="left" class="likeBtn @if ($isLiked) active @endif"
                                   data-asset_id="{{ $asset->id }}"
                                   data-user_id="{{ $user->id }}">
                                   <i class="ti ti-heart"></i>
                              </button>
                              <button class="collectionBtn" title="@lang('Collect')" 
                                   data-bs-placement="left"
                                   data-user_id="{{ $user->id }}"
                                   data-asset_id="{{ $asset->id }}">
                                   <i class="ti ti-folder-plus"></i>
                              </button>                    
                         @else
                              <button role="button" title="@lang('Like')" data-bs-placement="left" class="signInfoBtn" data-label_text="{{ trans('Like') }}"><i class="ti ti-heart"></i></button>
                              <button role="button" title="@lang('Collect')" data-bs-placement="left" class="signInfoBtn" data-label_text="{{ trans('Add to collection') }}"><i class="ti ti-folder-plus"></i></button>
                         @endif
               
                         <button title="@lang('Share')" data-bs-placement="left" class="shareAssetBtn"
                              data-route="{{ route('asset.detail', [encrypt($asset->id), slug($asset->title)]) }}"
                              data-encoded_route="{{ urlencode(route('asset.detail', [encrypt($asset->id), slug($asset->title)])) }}"
                              data-asset_title="{{ $asset->title }}">
                              <i class="ti ti-share-3"></i>
                         </button>
                    </div>
               </div>
          @endforeach
     </div>
@else
     <div class="product__row product__row-empty">
          <div class="no-data-found">
          <img src="{{ getImage('assets/universal/images/noData.png') }}" alt="No Data Found">
          <span>@lang('No Assets Found')</span>
          </div>
     </div>
@endif

@if ($assets->hasPages())
     <div class="search-result__pagination d-flex authorAssetsPagination" id="authorAssetsPagination">
          <div>
               @if ($assets->onFirstPage())
                    <a class="search-result__pagination__page-1 disabled"><i class="ti ti-arrow-bar-to-left"></i> @lang('Go to page 1')</a>
               @else
                    <a href="{{ $assets->url(1) }}" class="search-result__pagination__page-1"><i class="ti ti-arrow-bar-to-left"></i> @lang('Go to page 1')</a>                   
               @endif
          </div>
          <div class="d-flex gap-2">
               @if ($assets->previousPageUrl())
                    <a href="{{ $assets->previousPageUrl() }}" class="btn btn-outline--base py-2 px-3"><i class="ti ti-arrow-left"></i></a>                   
               @endif

               @if ($assets->nextPageUrl())
                    <a href="{{ $assets->nextPageUrl() }}" class="btn btn--base py-2 px-4">@lang('Next Page') <i class="ti ti-arrow-right"></i></a>                   
               @endif
          </div>
          <div class="search-result__pagination__input">
               <form class="authorAssetsPaginationForm">
                    <span>@lang('Page') <input type="number" class="form--control form--control--sm d-inline" id="pageNumberInput" value="{{ $assets->currentPage() }}" min="1" max="{{ $assets->lastPage() }}"> @lang('of') {{ $assets->lastPage() }}</span>
               </form>
          </div>
     </div> 
@endif