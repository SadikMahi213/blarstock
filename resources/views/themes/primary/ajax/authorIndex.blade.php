@if ($authors->isNotEmpty())
    @foreach ($authors as $author)
        <div class="col-xl-3 col-md-4 col-sm-6 col-xsm-6">
            <div class="contributor__card">
                <div class="contributor__card__img">
                    <div class="contributor__card__img__cover">
                        <img src="{{ $author->cover_image_thumb ? getImage(getFilePath('userCover') . '/' . $author->cover_image_thumb, getFileSize('userCover')) : getImage($activeThemeTrue . 'images/site/default_user_cover/thumb_' . $defaultUserCover?->data_info?->image, '450x120') }}" alt="Image">
                    </div>
                    <div class="contributor__card__img__profile">
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $author->image, getFileSize('userProfile'), true) }}" alt="Image">
                    </div>
                </div>
                <div class="contributor__card__txt">
                    <h3 class="contributor__card__name"><a href="{{ route('author.profile', [encrypt($author->id), slug($author->author_name)]) }}">{{ __($author->author_name) }}</a></h3>
                    <span class="contributor__card__info">{{ formatNumber($author->approved_images_count) }} @lang('Resources')</span>
                </div>
            </div>
        </div>
    @endforeach

    @if ($authors->hasPages())
        <div class="col-12">
            <div class="row g-3 align-items-center">
                <div class="col-lg-4 col-md-5 d-flex justify-content-md-start justify-content-center">
                    <span>@lang('Showing') <span class="fw-semibold">{{ $authors->firstItem() }}</span> @lang('to') <span class="fw-semibold">{{ $authors->lastItem() }}</span> @lang('of') <span class="fw-semibold">{{ $authors->total() }}</span> @lang('results')</span>
                </div>
                <div class="col-lg-8 col-md-7 authorIndex">
                    <ul class="pagination mt-0 justify-content-md-end justify-content-center">
                        @if ($authors->onFirstPage())
                            <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-left"></i></span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $authors->previousPageUrl() }}"><i class="ti ti-chevrons-left"></i></a></li>
                        @endif
        
                        @foreach ($authors->links()->elements[0] as $page => $url)
                            <li class="page-item {{ $page == $authors->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endforeach
        
                        @if ($authors->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $authors->nextPageUrl() }}"><i class="ti ti-chevrons-right"></i></a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-right"></i></span></li>                
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="no-data-found">
        <img src="{{ asset('assets/universal/images/noData.png') }}" alt="@lang('No Author Found')">
        <span>@lang('No Author Found')</span>
   </div>
@endif