<!-- ========== Product Section Start ========== -->
@php
    $excludedColumns = ['user_id', 'image_name', 'track_id', 'uploaded_date', 'image_width', 'image_height', 'extensions', 'description', 'tags', 'colors', 'total_like', 'is_featured', 'attribution', 'total_view', 'reason', 'admin_id', 'reviewer_id', 'total_earning'];
        
    $topAssets =  App\Models\Image::activeCheck()->with(['category', 'imageFiles' => fn($query) => $query->active()->premium(), 'likes' => fn($query) => $query->when(auth()->id(), fn($q) => $q->where('user_id', auth()->id()))])->select(getSelectedColumns('images', $excludedColumns))->latest()->limit(20)->get();
@endphp

<div class="product pb-120">
    <div class="container">
        <div class="section-heading-2">
            <div class="row align-items-end">
                <div class="col-sm-6">
                    <h2 class="section-heading-2__title">@lang('A Few Picks You’ll Enjoy')</h2>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex justify-content-sm-end justify-content-center">
                        <a href="{{ route('all.assets') }}" class="section-heading-2__link">@lang('View More') <i class="ti ti-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @if ($topAssets->isNotEmpty())
            <div class="product__row tiles-wrap">
                @foreach($topAssets as $asset)
                    <div class="product__card {{ $asset->video ? 'product-video' : 'product-image' }}" data-title="{{ __($asset->title) }}">
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
                    <img src="{{ asset('assets/universal/images/noData.png') }}" alt="@lang('No Top Contributor Found')">
                    <span>@lang('No Assets Found')</span>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- ========== Product Section End ========== -->
