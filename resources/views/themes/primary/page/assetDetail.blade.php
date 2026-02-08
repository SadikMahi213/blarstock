@extends($activeTheme . 'layouts.frontend')
@section('frontend')
     <section class="product-details">
          <div class="header-search">
               <div class="container-fluid">
                    <div class="d-flex gap-2">
                         <form action="{{ route('all.assets') }}" class="banner-content__form input--group w-100 searchViaForm" method="GET">
                              <div class="banner-content__select">
                                   <button type="button" class="banner-content__select__btn"><span class="banner-content__select__btn__txt"><i class="ti ti-triangle-square-circle"></i> <span class="btn-txt">@lang('Assets')</span></span> <i class="ti ti-chevron-down"></i></button>
                                   <ul class="banner-content__select__list">
                                        <li>
                                             <input data-filter="library" type="radio" id="searchInAsset" name="data_type" value="assets" checked>
                                             <label for="searchInAsset"><i class="ti ti-triangle-square-circle"></i> @lang('Assets')</label>
                                        </li>
                                        <li>
                                             <input data-filter="library" type="radio" id="searchInCollection" name="data_type" value="collections">
                                             <label for="searchInCollection"><i class="ti ti-copy-plus"></i> @lang('Collections')</label>
                                        </li>
                                        <li class="dropdown-divider"></li>

                                        @php
                                             $fileTypes = App\Models\FileType::active()->get();
                                        @endphp

                                        @foreach ($fileTypes as $fileType)
                                             <li>
                                                  <input data-filter="category" type="checkbox" id="{{ $fileType->id }}" name="file_type_id" value="{{ $fileType->id }}">
                                                  <label for="{{ $fileType->id }}">@php echo $fileType->icon; @endphp {{ __($fileType->name) }}</label>
                                             </li>    
                                        @endforeach

                                        <li class="dropdown-divider"></li>
                                        <li>
                                             <input data-filter="access-level" type="checkbox" id="searchAll" name="license" value="">
                                             <label for="searchAll"><i class="ti ti-menu-2"></i> @lang('All')</label>
                                        </li>
                                        <li>
                                             <input data-filter="access-level" type="checkbox" id="searchPremium" name="license" value="premium">
                                             <label for="searchPremium"><i class="ti ti-crown text--warning"></i> @lang('Premium')</label>
                                        </li>
                                        <li>
                                             <input data-filter="access-level" type="checkbox" id="searchFree" name="license" value="free">
                                             <label for="searchFree"><i class="ti ti-creative-commons-zero"></i> @lang('Free')</label>
                                        </li>
                                   </ul>
                              </div>

                              <input type="search" class="form--control form--control--sm" placeholder="@lang('Search Here')" name="search_title" value="{{ old('search_title') }}">
                              <button class="btn btn--sm btn--base"><i class="ti ti-search"></i> <span class="btn-txt">@lang('Search')</span></button>
                         </form>
                    </div>
               </div>
          </div>
          <div class="container py-60">
               <div class="row g-4">
                    <div class="col-xl-8 col-lg-7">
                         <div class="product-details__inner">
                              <div class="product-details__thumb">
                                   @if ($asset->video)
                                        <video src="{{ videoFileUrl($asset->video) }}" controls autoplay muted loop></video>
                                   @elseif ($asset->image_name)
                                        <img src="{{ imageUrl(getFilePath('stockImage'), $asset->image_name) }}" alt="@lang('Image')">
                                   @endif
                              </div>
                              <p class="product-details__inner__caption">{{ __($asset->title) }}</p>
                              <div class="product-details__info">
                                   <div class="product-details__author">
                                        <div class="product-details__author__img">
                                             <a href="{{ route('author.profile', [encrypt($asset->user->id), slug($asset->user->author_name)]) }}"><img src="{{ getImage(getFilePath('userProfile') . '/' . $asset->user->image, getFileSize('userProfile'), true) }}" alt="@lang('Image')"></a>
                                        </div>
                                        <div class="product-details__author__txt">
                                             <a href="{{ route('author.profile', [encrypt($asset->user->id), slug($asset->user->author_name)]) }}" class="product-details__author__name">{{ __($asset->user->author_name) }}</a>
                                             @if ($user)
                                                  @if ($user->id != $asset->user_id)
                                                       <span role="button" class="product-details__author__follow followBtn" data-user_id="{{ $user->id }}">@if ($isFollowed)<i class="ti ti-user-check transform-2"></i> @else <i class="ti ti-user-plus transform-2"></i> @endif <span class="buttonText">{{ $isFollowed ? trans('Following') : trans('Follow') }}</span></span>
                                                  @endif
                                             @else
                                                  <span role="button" class="product-details__author__follow signInfoBtn" data-label_text="{{ trans('Follow') }}"><i class="ti ti-user-plus transform-2"></i> @lang('Follow')</span>
                                             @endif
                                        </div>
                                   </div>
                                   <div class="product-details__action">
                                        @if ($user)
                                             <button class="@if ($isLiked) active @endif likeBtn" data-asset_id="{{ $asset->id }}" data-user_id="{{ $user->id }}"><i class="ti ti-heart"></i> <span class="buttonText">{{ $isLiked ? trans('Liked') : trans('Like') }}</span></button>
                                             <button class="collectionBtn" 
                                                  data-user_id="{{ $user->id }}"
                                                  data-asset_id="{{ $asset->id }}"
                                                  ><i class="ti ti-folder-plus"></i> @lang('Add to collection')
                                             </button>
                                        @else
                                             <button class="signInfoBtn" type="button" data-label_text="{{ trans('Like') }}"><i class="ti ti-heart"></i> @lang('Like')</button>
                                             <button role="button" class="signInfoBtn" data-label_text="{{ trans('Add to collection') }}"><i class="ti ti-folder-plus"></i> @lang('Add to collection')</button>
                                        @endif

                                        <button class="shareAssetBtn"
                                             data-route="{{ route('asset.detail', [encrypt($asset->id), slug($asset->title)]) }}"
                                             data-encoded_route="{{ urlencode(route('asset.detail', [encrypt($asset->id), slug($asset->title)])) }}"
                                             data-asset_title="{{ $asset->title }}">
                                             <i class="ti ti-share-3"></i> @lang('Share')
                                        </button>
                                   </div>
                                   <div class="product-details__desc">
                                        <p class="border-bottom pb-1 mb-2"><strong>@lang('Description'):</strong></p>
                                        <p>@php echo $asset->description; @endphp</p>
                                   </div>
                                   <div class="product-details__keywords w-100">
                                        <p class="border-bottom pb-1 mb-3"><strong>@lang('Keywords'):</strong></p>
                                        <div class="d-flex flex-wrap gap-2">
                                             @foreach ($asset->tags as $tag)
                                                  <a href="{{ route('all.assets', ['tag' => $tag]) }}" class="tag-btn"><i class="ti ti-search"></i> {{ (ucfirst($tag)) }}</a>
                                             @endforeach
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                         <div class="product-details__sidebar">
                              <div class="custom--card mb-4 border-0">
                                   <table class="table table-borderless">
                                        <tbody>
                                             <tr>
                                                  <td><strong>@lang('Asset type'):</strong></td>
                                                  <td>
                                                       {{ implode(', ', array_map('strtoupper', $asset->extensions ?? [])) }}
                                                  </td>
                                             </tr>
                                             <tr>
                                                  <td><strong>@lang('Published'):</strong></td>
                                                  <td>{{ showDateTime($asset->created_at, 'F d, Y') }}</td>
                                             </tr>
                                             <tr>
                                                  <td><strong>@lang('Likes'):</strong></td>
                                                  <td>{{ __($asset->total_like) }}</td>
                                             </tr>
                                             <tr>
                                                  <td><strong>@lang('Views'):</strong></td>
                                                  <td>{{ __($asset->total_view) }}</td>
                                             </tr>
                                             <tr>
                                                  <td><strong>@lang('Downloads'):</strong></td>
                                                  <td>{{ __($asset->total_download) }}</td>
                                             </tr>
                                        </tbody>
                                   </table>
                              </div>

                              <div class="custom--card mb-4 border-0">
                                   <table class="table table-borderless">
                                        <tbody>
                                             @foreach ($asset->imageFiles as $file)
                                                  @if ($file->status == ManageStatus::ACTIVE)
                                                       <tr>
                                                            <td><span class="fw-medium">{{ $file->resolution }}</span></td>
                                                            <td>
                                                                 @if ($file->is_free)
                                                                      <span class="text--success">@lang('Free')</span>
                                                                 @else
                                                                      <span class="fw-bold">{{ $setting->cur_sym }}{{ showAmount($file->price) }}</span>
                                                                 @endif
                                                            </td>
                                                            <td>
                                                                 @if ($user)
                                                                      <button role="button" class="btn btn--sm btn--icon btn--gold downloadBtn" 
                                                                           data-route="{{ route('user.download.file', encrypt($file->id)) }}"
                                                                           data-label="{{ __($asset->title) }} [{{ $file->resolution }}]"><span><i class="ti ti-download"></i></span>
                                                                      </button>
                                                                 @else
                                                                      <button role="button" class="btn btn--sm btn--icon btn--gold signInfoBtn" data-label_text="{{ trans('Download') }}"><span><i class="ti ti-download"></i></span>
                                                                      </button>
                                                                 @endif
                                                            </td>
                                                       </tr>
                                                  @endif
                                             @endforeach
                                        </tbody>
                                   </table>
                              </div>

                              <div class="row g-3">
                                   @if ($setting->donation )
                                        @if (($user && $user->id != $asset->user_id) || !$user)
                                             <div class="col-12">
                                                  <span role="button" class="btn btn--base w-100 donationModalBtn">@php echo $setting->donation_setting?->icon ?? ''; @endphp @lang('Buy me') {{ __($setting->donation_setting?->subtitle ?? '') }}</span>
                                             </div>
                                        @endif
                                   @endif
                              </div>
                         </div>
                    </div>
               </div>
               <div class="related-product">
                    <h3 class="related-product__title">@lang('Related Assets')</h3>
                    @if ($relatedAssets->isNotEmpty())
                         <div class="related-product-wrap">
                              <div class="related-product__nav">
                                   <button class="related-product__btn" id="relatedProductLeft" disabled><i class="ti ti-arrow-left"></i></button>
                                   <button class="related-product__btn next" id="relatedProductRight"><i class="ti ti-arrow-right"></i></button>
                              </div>
                              <div class="related-product__slider related-product-scroll">
                                   <ul>
                                        @foreach ($relatedAssets as $relatedAsset)
                                             <li>
                                                  <div class="product__card {{ $relatedAsset->video ? 'product-video' : 'product-image' }}">
                                                       <a href="{{ route('asset.detail', [encrypt($relatedAsset->id), slug($relatedAsset->title)]) }}" @if($relatedAsset->video) data-video-src="{{ videoFileUrl($relatedAsset->video) }}" @endif class="product__card__thumb">
                                                            <img src="{{ imageUrl(getFilePath('stockImage'), $relatedAsset->thumb) }}" alt="Image">
                                                       </a>
                                                       <div class="product__card__txt">
                                                            <p class="product__card__name">{{ __($relatedAsset->category->name) }}</p>
                                                            <a href="{{ route('all.assets', ['category_id' => $relatedAsset->category_id]) }}" class="product__card__btn"><i class="ti ti-stack-2"></i> @lang('View Category')</a>
                                                       </div>
                                                       <div class="product__card__badges">
                                                            @if ($relatedAsset->video)
                                                                 <span class="product__card__badge video"><i class="ti ti-video"></i></span>
                                                            @endif
                                   
                                                            @if ($relatedAsset->imageFiles->isNotEmpty())
                                                                 <span class="product__card__badge premium"><i class="ti ti-crown"></i></span>
                                                            @endif
                                                       </div>
                                   
                                                       <div class="product__card__action">
                                                            @if ($user)
                                                                 @php
                                                                      $isLiked = $relatedAsset->likes->isNotEmpty();
                                                                 @endphp
                                             
                                                                 <button title="{{ $isLiked ? trans('Liked') : trans('Like') }}" data-bs-placement="left" class="likeBtn @if ($isLiked) active @endif"
                                                                      data-asset_id="{{ $relatedAsset->id }}"
                                                                      data-user_id="{{ $user->id }}">
                                                                      <i class="ti ti-heart"></i>
                                                                 </button>
                                                                 <button class="collectionBtn" title="@lang('Collect')" 
                                                                      data-bs-placement="left"
                                                                      data-user_id="{{ $user->id }}"
                                                                      data-asset_id="{{ $relatedAsset->id }}">
                                                                      <i class="ti ti-folder-plus"></i>
                                                                 </button>                    
                                                            @else
                                                                 <button role="button" title="@lang('Like')" data-bs-placement="left" class="signInfoBtn" data-label_text="{{ trans('Like') }}"><i class="ti ti-heart"></i></button>
                                                                 <button role="button" title="@lang('Collect')" data-bs-placement="left" class="signInfoBtn" data-label_text="{{ trans('Add to collection') }}"><i class="ti ti-folder-plus"></i></button>
                                                            @endif
                                             
                                                            <button title="@lang('Share')" data-bs-placement="left" class="shareAssetBtn"
                                                                 data-route="{{ route('asset.detail', [encrypt($relatedAsset->id), slug($relatedAsset->title)]) }}"
                                                                 data-encoded_route="{{ urlencode(route('asset.detail', [encrypt($relatedAsset->id), slug($relatedAsset->title)])) }}"
                                                                 data-asset_title="{{ $relatedAsset->title }}">
                                                                 <i class="ti ti-share-3"></i>
                                                            </button>
                                                       </div>
                                                  </div>
                                             </li>
                                        @endforeach
                                   </ul>     
                              </div>
                         </div>
                    @else
                         <div class="no-data-found">
                              <img src="{{ getImage('assets/universal/images/noData.png') }}" alt="No Data Found">
                              <span>@lang('No more related assets available')</span>
                         </div>
                    @endif
               </div>
               <div class="related-product pb-0 border-bottom-0">
                    <h3 class="related-product__title">@lang('More From The Author')</h3>
                    @if ($authorAssets->isNotEmpty())
                         <div class="related-product-wrap">
                              <div class="related-product__nav">
                                   <button class="related-product__btn" id="authorProductLeft" disabled><i class="ti ti-arrow-left"></i></button>
                                   <button class="related-product__btn next" id="authorProductRight"><i class="ti ti-arrow-right"></i></button>
                              </div>
                              <div class="related-product__slider author-product-scroll">
                                   <ul>
                                        @foreach ($authorAssets as $authorAsset)
                                             <li>
                                                  <div class="product__card {{ $authorAsset->video ? 'product-video' : 'product-image' }}" title="{{ __($authorAsset->title) }}">
                                                       <a href="{{ route('asset.detail', [encrypt($authorAsset->id), slug($authorAsset->title)]) }}" @if($authorAsset->video) data-video-src="{{ videoFileUrl($authorAsset->video) }}" @endif class="product__card__thumb">
                                                            <img src="{{ imageUrl(getFilePath('stockImage'), $authorAsset->thumb) }}" alt="Image">
                                                       </a>
                                                       <div class="product__card__txt">
                                                            <p class="product__card__name">{{ __($authorAsset->category->name) }}</p>
                                                            <a href="{{ route('all.assets', ['category_id' => $authorAsset->category_id]) }}" class="product__card__btn"><i class="ti ti-stack-2"></i> @lang('View Category')</a>
                                                       </div>
                                                       <div class="product__card__badges">
                                                            @if ($authorAsset->video)
                                                                 <span class="product__card__badge video"><i class="ti ti-video"></i></span>
                                                            @endif
                                   
                                                            @if ($authorAsset->imageFiles->isNotEmpty())
                                                                 <span class="product__card__badge premium"><i class="ti ti-crown"></i></span>
                                                            @endif
                                                       </div>
                                   
                                                       <div class="product__card__action">
                                                            @if ($user)
                                                                 @php
                                                                      $isLiked = $authorAsset->likes->isNotEmpty();
                                                                 @endphp
                                             
                                                                 <button title="{{ $isLiked ? trans('Liked') : trans('Like') }}" data-bs-placement="left" class="likeBtn @if ($isLiked) active @endif"
                                                                      data-asset_id="{{ $authorAsset->id }}"
                                                                      data-user_id="{{ $user->id }}">
                                                                      <i class="ti ti-heart"></i>
                                                                 </button>
                                                                 <button class="collectionBtn" title="@lang('Collect')" 
                                                                      data-bs-placement="left"
                                                                      data-user_id="{{ $user->id }}"
                                                                      data-asset_id="{{ $authorAsset->id }}">
                                                                      <i class="ti ti-folder-plus"></i>
                                                                 </button>                    
                                                            @else
                                                                 <button role="button" title="@lang('Like')" data-bs-placement="left" class="signInfoBtn" data-label_text="{{ trans('Like') }}"><i class="ti ti-heart"></i></button>
                                                                 <button role="button" title="@lang('Collect')" data-bs-placement="left" class="signInfoBtn" data-label_text="{{ trans('Add to collection') }}"><i class="ti ti-folder-plus"></i></button>
                                                            @endif
                                             
                                                            <button title="@lang('Share')" data-bs-placement="left" class="shareAssetBtn"
                                                                 data-route="{{ route('asset.detail', [encrypt($authorAsset->id), slug($authorAsset->title)]) }}"
                                                                 data-encoded_route="{{ urlencode(route('asset.detail', [encrypt($authorAsset->id), slug($authorAsset->title)])) }}"
                                                                 data-asset_title="{{ $authorAsset->title }}">
                                                                 <i class="ti ti-share-3"></i>
                                                            </button>
                                                       </div>
                                                  </div>
                                             </li>
                                        @endforeach
                                   </ul>
                              </div>
                         </div>
                    @else
                         <div class="no-data-found">
                              <img src="{{ getImage('assets/universal/images/noData.png') }}" alt="No Data Found">
                              <span>@lang('No more assets of available for') <strong>{{ __($asset->user->author_name) }}</strong></span>
                          </div>
                    @endif
               </div>
          </div>
          
          @include($activeTheme . 'partials.ads')
     </section>

     <!-- Modal -->
     <div class="modal custom--modal fade" id="buyCoffeeModal" tabindex="-1" aria-labelledby="buyCoffeeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
               <div class="modal-content">
                    <div class="modal-header">
                         <h2 class="modal-title fs-5" id="buyCoffeeModalLabel">@lang('Buy') <em>{{ __($asset->user->author_name) }}</em> {{ __($setting->donation_setting?->subtitle ?? '') }}</h2>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                         <form action="{{ route('donation.insert', $asset->id) }}" method="POST" class="row g-3 align-items-center">
                              @csrf

                              <input type="hidden" name="currency" required>
                              <input type="hidden" name="quantity" value="{{ isset($setting->donation_setting->unit) ? collect($setting->donation_setting->unit)->first() : 0 }}" class="quantity-field">
                              
                              <div class="col-12">
                                   <div class="custom--card overflow-hidden">
                                        <div class="card-header buy-coffee-calculator">
                                             <span>@php echo $setting->donation_setting?->icon ?? ''; @endphp</span>
                                             <small><i class="ti ti-x"></i></small>
                                             <select class="buy-coffee-amount form--control form--select" data-coffee-price="{{ $setting->donation_setting?->amount ?? 0 }}">
                                                  @if (isset($setting->donation_setting->unit))
                                                      @foreach ($setting->donation_setting?->unit ?? [] as $unit)
                                                          <option value="{{ $unit }}">{{ $unit }} {{ $loop->first ? trans('Cup') : trans('Cups') }}</option>
                                                      @endforeach
                                                  @endif
                                                  <option value="custom">@lang('Custom')</option>
                                             </select>
                                             <span class="custom-buy-coffee-amount">
                                                  <small><i class="ti ti-plus"></i></small>
                                                  <input type="number" value="0" min="0" class="form--control">
                                             </span>
                                             <small><i class="ti ti-equal"></i></small>
                                             <span class="total-buy-coffee-amount">{{ $setting->cur_sym }}{{ showAmount((collect($setting->donation_setting?->unit)->first() ?? 1) * ($setting->donation_setting?->amount ?? 0)) }}
                                             </span>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-sm-4">
                                   <label class="col-form--label">@lang('Total Amount')</label>
                              </div>
                              <div class="col-sm-8">
                                   <div class="input--group">
                                        <input type="number" class="form--control form--control--sm amount" name="amount" value="{{ getAmount((collect($setting->donation_setting?->unit)->first() ?? 1) * ($setting->donation_setting?->amount ?? 0)) }}" required readonly>
                                        <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                   </div>
                              </div>
                              <div class="col-sm-4">
                                   <label class="col-form--label">@lang('Full Name')</label>
                              </div>
                              <div class="col-sm-8">
                                   <input type="text" class="form--control form--control--sm" name="name" value="{{ old('name', $user?->fullname) }}" required>
                              </div>
                              <div class="col-sm-4">
                                   <label class="col-form--label">@lang('Email Address')</label>
                              </div>
                              <div class="col-sm-8">
                                   <input type="email" class="form--control form--control--sm" name="email" value="{{ old('email', $user?->email) }}" required>
                              </div>
                              <div class="col-sm-4">
                                   <label class="col-form--label">@lang('Mobile Number')</label>
                              </div>
                              <div class="col-sm-8">
                                   <input type="tel" class="form--control form--control--sm" name="mobile" value="{{ old('mobile', $user?->mobile) }}" required>
                              </div>
                              <div class="col-sm-4">
                                   <label class="col-form--label">@lang('Select Payment Option')</label>
                              </div>
                              <div class="col-sm-8">
                                   <select class="form--control form--control--sm form-select" name="gateway" required>
                                        <option value="" selected disabled>@lang('Select One')</option>
                                        @if ($user)
                                            <option value="balance">@lang('Account Balance') ({{ $setting->cur_sym }}{{ showAmount($user->balance) }})</option>
                                        @endif
                                        @foreach ($gatewayCurrency as $data)
                                            <option value="{{ $data->method_code }}" @selected(old('gateway') == $data->method_code) data-gateway="{{ $data }}">{{ __($data->name) }}</option>
                                        @endforeach
                                   </select>
                              </div>
                              <div class="col-12 preview-details d-none">
                                   <table class="table table-borderless table-light">
                                        <tbody>
                                             <tr>
                                                  <td>@lang('Limit')</td>
                                                  <td><span class="min fw-semibold">0</span> {{ __($setting->site_cur) }} - <span class="max fw-semibold">0</span> {{ __($setting->site_cur) }}</td>
                                             </tr>
                                             <tr>
                                                  <td>@lang('Processing Charge') <span><i class="ti ti-help"></i></span></td>
                                                  <td><span class="charge fw-semibold">0</span> {{ __($setting->site_cur) }}</td>
                                             </tr>
                                             <tr>
                                                  <td><strong>@lang('Total')</strong></td>
                                                  <td><strong><span class="payable fw-semibold"></span> {{ __($setting->site_cur) }}</strong></td>
                                             </tr>
                                             <tr class="in-site-cur d-none">
                                                  <td><strong>@lang('In') <span class="method_currency"></span></strong></td>
                                                  <td><strong><span class="final_amo fw-semibold">0</span> {{ __($setting->site_cur) }}</strong></td>
                                             </tr>
                                        </tbody>
                                   </table>
                              </div>

                              <div class="col-12 rate-element">

                              </div>

                              <div class="col-12 crypto-currency d-none">
                                   <p class="small fw-semibold text-center">@lang('Conversion with') <span class="method_currency"></span> @lang('and final value will show on next step')</p>
                              </div>

                              <div class="col-12">
                                   <button class="btn btn--sm btn--base w-100">@lang('Confirm Donation')</button>
                              </div>
                              <div class="col-12">
                                   <p class="small fw-semibold text-center">@lang('Grow Your Funds Securely with Our Trusted Donation Process and Premium Payment Options')</p>
                              </div>
                         </form>
                    </div>
               </div>
          </div>
     </div>
@endsection

@push('page-script')
    <script>
          (function($) {
               'use strict';

               $('.followBtn').off('click').on('click', function(event) {
                    event.preventDefault();

                    let button  = $(this);
                    let userId  = button.data('user_id');
                    let authorId = '{{ $asset->user_id }}';
                    
                    let data = {
                         user_id : userId,
                         author_id: authorId
                    };

                    $.ajax({
                         type: "GET",
                         url : "{{ route('user.follow') }}",
                         data: data,
                         success: function (response) {
                              if (response.success) {
                                   button.find('.buttonText').text(`@lang('Following')`);
                                   button.find('i').removeClass('ti-user-plus').addClass('ti-user-check');
                                   
                                   showToasts('success', response.message);
                              } else if (response.warning) {
                                   button.find('.buttonText').text(`@lang('Follow')`);
                                   button.find('i').removeClass('ti-user-check').addClass('ti-user-plus');
                                   
                                   showToasts('success', response.message);
                              
                              } else {
                                   showToasts('error', response.message);
                              }
                         },
                         error: function() {
                              showToasts('error', 'Something went wrong while following');
                         }
                    });

               });

               $('.donationModalBtn').on('click', function() {
                    let modal = $('#buyCoffeeModal');
                    
                    
                    modal.off('hidden.bs.modal');
                    modal.find('select[name="gateway"]').off('change.payment');
                    modal.find('[name=amount]').off('input.amount');
                    modal.find('.buy-coffee-amount').off('change.coffee');
                    modal.find('.custom-buy-coffee-amount input').off('input.custom');


                    const updatePaymentDetails = function() {
                         let gateway = modal.find('select[name="gateway"]');
                         if (!gateway.val()) {
                              modal.find('.preview-details').addClass('d-none');
                              return;
                         }

                         let resource = gateway.find('option:selected').data('gateway');
                         
                         if (!resource) {
                              modal.find('.preview-details').addClass('d-none');
                              modal.find('.rate-element').addClass('d-none');
                              modal.find('.crypto-currency').addClass('d-none');
                              
                              return;
                         }

                         let amount        = parseFloat(modal.find('[name=amount]').val()) || 0;
                         let fixedCharge   = parseFloat(resource.fixed_charge);
                         let percentCharge = parseFloat(resource.percent_charge);
                         let rate          = parseFloat(resource.rate);
                         let toFixedDigit  = resource.method.crypto == 1 ? 8 : 2;

                         
                         modal.find('.crypto-currency').toggleClass('d-none', resource.method.crypto != 1);
                         modal.find('.min').text(parseFloat(resource.min_amount).toFixed(2));
                         modal.find('.max').text(parseFloat(resource.max_amount).toFixed(2));

                         if (amount <= 0) {
                              modal.find('.preview-details').addClass('d-none');
                              return;
                         }

                         modal.find('.preview-details').removeClass('d-none');

                         
                         let charge      = parseFloat(fixedCharge + (amount * percentCharge / 100)).toFixed(2);
                         let payable     = parseFloat(amount + parseFloat(charge)).toFixed(2);
                         let finalAmount = (payable * rate).toFixed(toFixedDigit);

                         
                         modal.find('.charge').text(charge);
                         modal.find('.payable').text(payable);
                         modal.find('.final_amo').text(finalAmount);

                         
                         if (resource.currency != '{{ $setting->site_cur }}') {
                              let rateElement = `<p class="small fw-semibold text-center">@lang('Conversion rate') <span class="fw-semibold">1 {{ __($setting->site_cur) }} = <span class="rate">${rate}</span> <span class="method_currency">${resource.currency}</span></span></p>`;
                              modal.find('.rate-element').html(rateElement).removeClass('d-none');
                              modal.find('.in-site-cur').removeClass('d-none');
                         } else {
                              modal.find('.rate-element').html('').addClass('d-none');
                              modal.find('.in-site-cur').addClass('d-none');
                         }

                         modal.find('.method_currency').text(resource.currency);
                         modal.find('[name=currency]').val(resource.currency);
                    };

                    
                    modal.find('[name=amount]').on('input.amount', function() {
                         let val = parseFloat($(this).val()) || 0;
                         modal.find('.amount').text(val.toFixed(2));
                         updatePaymentDetails();
                    });

                    
                    modal.find('.buy-coffee-amount').on('change.coffee', function() {
                         let coffeePrice    = $(this).data('coffee-price');
                         let selectedAmount = $(this).find('option:selected').val();
                         let amountInput    = modal.find('[name=amount]');
                         let quantityInput  = modal.find('.quantity-field');

                         if (selectedAmount == 'custom') {
                              modal.find('.custom-buy-coffee-amount').addClass('active');
                              let customInput = modal.find('.custom-buy-coffee-amount input');
                              
                              let inputVal         = customInput.val() || 0;
                              let totalCoffeePrice = inputVal * coffeePrice;
                              
                              modal.find('.total-buy-coffee-amount').text('{{ $setting->cur_sym }}' + totalCoffeePrice.toFixed(2));
                              amountInput.val(totalCoffeePrice).trigger('input.amount');
                              
                              customInput.off('input.custom').on('input.custom', function() {
                                   let inputVal = $(this).val() || 0;
                                   quantityInput.val(inputVal);
                                   
                                   let totalCoffeePrice = inputVal * coffeePrice;
                                   modal.find('.total-buy-coffee-amount').text('{{ $setting->cur_sym }}' + totalCoffeePrice.toFixed(2));
                                   amountInput.val(totalCoffeePrice).trigger('input.amount');
                              });
                         } else {
                              modal.find('.custom-buy-coffee-amount').removeClass('active');
                              let totalCoffeePrice = selectedAmount * coffeePrice;
                              modal.find('.total-buy-coffee-amount').text('{{ $setting->cur_sym }}' + totalCoffeePrice.toFixed(2));
                              amountInput.val(totalCoffeePrice).trigger('input.amount');
                              quantityInput.val(selectedAmount);
                         }
                    });

               
                    modal.find('select[name="gateway"]').on('change.payment', updatePaymentDetails);

                    
                    modal.on('hidden.bs.modal', function() {
                         modal.find('select[name="gateway"]').off('change.payment');
                         modal.find('[name=amount]').off('input.amount');
                         modal.find('.buy-coffee-amount').off('change.coffee');
                         modal.find('.custom-buy-coffee-amount input').off('input.custom');
                    });

                    modal.modal('show');
               });
          })(jQuery);
    </script>
@endpush