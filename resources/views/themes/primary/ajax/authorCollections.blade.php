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
                $assets                   = $collection->images->pluck('tags')->toArray();
                $tags                     = array_slice(array_unique(array_merge(...$assets)), 0, 6);

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

            <div class="col-xl-3 col-lg-4 col-sm-6 col-xsm-6">
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
                        <span class="collection__card__info">@lang('Created by') <a href="{{ route('author.profile', [encrypt($author->id), slug($author->author_name)]) }}">{{ __($author->author_name) }}</a></span>
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
            <span>@lang('No Collection Found')</span>
        </div>
    </div>
@endif

@if ($collections->hasPages())
    <div class="search-result__pagination d-flex authorCollectionPagination">
        @if ($collections->onFirstPage())
            <a class="search-result__pagination__page-1 disabled"><i class="ti ti-arrow-bar-to-left"></i> @lang('Go to page 1')</a> 
        @else
            <a href="{{ $collections->url(1) }}" class="search-result__pagination__page-1"><i class="ti ti-arrow-bar-to-left"></i> @lang('Go to page 1')</a>            
        @endif

        <div class="d-flex gap-2">
            @if ($collections->previousPageUrl())
                <a href="{{ $collections->previousPageUrl() }}" class="btn btn-outline--base py-2 px-3"><i class="ti ti-arrow-left"></i></a>                   
            @endif

            @if ($collections->nextPageUrl())
                <a href="{{ $collections->nextPageUrl() }}" class="btn btn--base py-2 px-4">@lang('Next Page') <i class="ti ti-arrow-right"></i></a>                   
            @endif
        </div>
        <div class="search-result__pagination__input">
            <form class="authorCollectionPaginationForm">
                <span>@lang('Page') <input type="number" class="form--control form--control--sm d-inline" id="pageNumberInput" value="{{ $collections->currentPage() }}" min="1" max="{{ $collections->lastPage() }}"> @lang('of') {{ $collections->lastPage() }}</span>
            </form>
        </div>
    </div> 
@endif