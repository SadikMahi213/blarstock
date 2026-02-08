@extends($activeTheme . 'layouts.auth')
@section('auth')
     <div class="py-120">
          <div class="row gy-5 justify-content-lg-between justify-content-center align-items-center">
               <div class="col-12">
                    <div class="custom--card">
                         <div class="card-body">
                              <ul class="profile-settings__list d-flex">
                                   <li>
                                        <div class="profile-settings__list__icon">
                                             <span class="ti ti-user"></span>
                                        </div>
                                        <div class="profile-settings__list__content" title="{{ $user->username }}">
                                             <span class="profile-settings__list__title">@lang('Username')</span>
                                             <span class="profile-settings__list__txt"><small>@</small>{{ (strLimit($user->username, 21)) }}</span>
                                        </div>
                                   </li>
                                   <li>
                                        <div class="profile-settings__list__icon">
                                             <span class="ti ti-mail"></span>
                                        </div>
                                        <div class="profile-settings__list__content" title="{{ $user->email }}">
                                             <span class="profile-settings__list__title">@lang('Email')</span>
                                             <span class="profile-settings__list__txt">{{ strLimit($user->email, 22) }}</span>
                                        </div>
                                   </li>
                                   <li>
                                        <div class="profile-settings__list__icon">
                                             <span class="ti ti-phone"></span>
                                        </div>
                                        <div class="profile-settings__list__content" title="{{ $user->mobile }}">
                                             <span class="profile-settings__list__title">@lang('Mobile')</span>
                                             <span class="profile-settings__list__txt">{{ $user->mobile }}</span>
                                        </div>
                                   </li>
                                   <li>
                                        <div class="profile-settings__list__icon">
                                             <span class="ti ti-map-pin-2"></span>
                                        </div>
                                        <div class="profile-settings__list__content" title="{{ __($user->country_name) }}">
                                             <span class="profile-settings__list__title">@lang('Country')</span>
                                             <span class="profile-settings__list__txt">{{ __(strLimit($user->country_name, 22)) }}</span>
                                        </div>
                                   </li>
                              </ul>
                         </div>
                    </div>
               </div>
               <div class="col-lg-5 col-sm-8 col-xsm-8 col-10">
                    <div class="profile-settings__thumb">
                         <img src="{{ getImage($activeThemeTrue . 'images/site/profile_setting/' . $siteData?->data_info?->image, '725x800') }}" alt="Image">
                    </div>
               </div>
               <div class="col-xl-6 col-lg-7">
                    <div class="custom--card">
                         <div class="card-header">
                              <h3 class="title">@lang('Update Profile')</h3>
                         </div>
                         <div class="card-body">
                              <form method="POST" action="{{ route('user.profile') }}" class="row gx-4 gy-3 register">
                                   @csrf
                                   
                                   <div class="col-sm-6">
                                        <label class="form--label required" for="firstName">@lang('First Name')</label>
                                        <input type="text" class="form--control" id="firstName" name="firstname" value="{{ $user->firstname }}" required>
                                   </div>
                                   
                                   <div class="col-sm-6">
                                        <label class="form--label required" for="lastName">@lang('Last Name')</label>
                                        <input type="text" class="form--control" id="lastName" name="lastname" value="{{ $user->lastname }}" required>
                                   </div>
                                   
                                   <div class="col-sm-6">
                                        <label class="form--label" for="cityName">@lang('City')</label>
                                        <input type="text" class="form--control" id="cityName" name="city" value="{{ $user->address?->city ?? '' }}">
                                   </div>

                                   <div class="col-sm-6">
                                        <label class="form--label" for="stateName">@lang('State')</label>
                                        <input type="text" class="form--control" id="stateName" name="state" value="{{ $user->address?->state ?? '' }}">
                                   </div>
                                   
                                   <div class="col-sm-6">
                                        <label class="form--label" for="zipCode">@lang('Zip Code')</label>
                                        <input type="text" class="form--control" id="zipCode" name="zip" value="{{ $user->address?->zip ?? '' }}">
                                   </div>

                                   <div class="col-sm-6">
                                        <label class="form--label" for="addressLine">@lang('Address')</label>
                                        <input type="text" class="form--control" id="addressLine" name="address" value="{{ $user->address?->address ?? '' }}">
                                   </div>

                                   <div class="col-12">
                                        <button type="submit" class="btn btn--base w-100 mt-2">{{ __($siteData?->data_info?->submit_button_text) }}</button>
                                   </div>
                              </form>
                         </div>
                    </div>
               </div>
          </div>
     </div>
@endsection