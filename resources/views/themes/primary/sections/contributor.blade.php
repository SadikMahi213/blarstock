<!-- ========== Top Contributor Section Start ========== -->
@php
    $topContributorContent = getSiteData('contributor.content', true);
    $defaultUserCover      = getSiteData('default_user_cover.content', true);
    $excludedColumns       = ['firstname', 'cover_image', 'lastname', 'username', 'email', 'country_code', 'country_name', 'mobile', 'plan_id', 'plan_expired_date', 'author_data', 'reason', 'total_follower', 'total_following', 'total_self_download', 'total_others_download', 'joined_at', 'ref_by', 'balance', 'password', 'address', 'kyc_data', 'kc', 'ec', 'sc', 'ver_code', 'ver_code_send_at', 'ts', 'tc', 'tsc', 'ban_reason', 'remember_token'];

    $topAuthors = App\Models\User::active()->approvedAuthor()
                    ->whereHas('images', fn($query) => $query->approved()->whereMonth('created_at', Carbon\Carbon::now()->month)->whereYear('created_at', Carbon\Carbon::now()->year))
                    ->select(getSelectedColumns('users', $excludedColumns))
                    ->withCount(['images' => fn($query) => $query->approved()->whereMonth('created_at', Carbon\Carbon::now()->month)->whereYear('created_at', Carbon\Carbon::now()->year)])
                    ->orderByDesc('images_count')->limit(8)->get();
@endphp

<section class="contributor py-120 bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/contributor/' . $topContributorContent?->data_info?->bg_image, '1920x1280') }}">
    <div class="container">
         <div class="row justify-content-center">
              <div class="col-xl-6 col-md-7">
                   <div class="section-heading text-center">
                        <h6 class="section-heading__subtitle slide-to-left">{{ __($topContributorContent?->data_info?->heading) }}</h6>
                        <h2 class="section-heading__title slide-to-left">{{ __($topContributorContent?->data_info?->subheading) }}</h2>
                   </div>
              </div>
         </div>
         <div class="row g-4 justify-content-center">
             @if($topAuthors->isNotEmpty())
                 @foreach($topAuthors as $author)
                     <div class="col-xl-3 col-md-4 col-sm-6 col-xsm-6 fade-bottom">
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
                                 <span class="contributor__card__info">{{ formatNumber($author->images_count) }} @lang('Resource')</span>
                             </div>
                         </div>
                     </div>
                 @endforeach
             @else
                 <div class="product__row product__row-empty">
                     <div class="no-data-found">
                         <img src="{{ asset('assets/universal/images/noData.png') }}" alt="@lang('No Top Contributor Found')">
                         <span>@lang('No Top Contributor Found')</span>
                     </div>
                 </div>
             @endif
         </div>
    </div>
</section>
<!-- ========== Top Contributor Section End ========== -->
