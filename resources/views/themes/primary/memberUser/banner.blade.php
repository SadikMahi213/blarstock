@php
    $authUser         = auth()->user();
    $defaultUserCover = getSiteData('default_user_cover.content', true);
@endphp
<div class="user-profile__banner bg-img" id="coverImageContainer" data-background-image="{{ $user->cover_image ? getImage(getFilePath('userCover') . '/' . $user->cover_image, getFileSize('userCover')) : getImage($activeThemeTrue . 'images/site/default_user_cover/' . $defaultUserCover?->data_info?->image, '1920x500') }}">
    <div class="update-cover-image">
        @if ($authUser && $authUser->id == $user->id)
            <input type="file" id="updateCoverImage">
            <label for="updateCoverImage"><i class="ti ti-photo-up"></i></label>
        @endif
    </div>
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-4">
                <div class="user-profile__banner__profile">
                    <div class="user-profile__banner__profile__img">
                        <img id="profileImage" src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile'), true) }}" alt="{{ __($user->username) }}">

                        @if ($authUser && $authUser->id == $user->id)
                            <input type="file" id="updateProfileImage">
                            <label for="updateProfileImage"><i class="ti ti-photo-up"></i></label>
                        @endif
                    </div>
                    <div class="user-profile__banner__profile__txt">
                        <h2 class="user-profile__banner__profile__title">{{ __($user->fullname) }}</h2>
                        <span class="user-profile__banner__profile__info">@lang('Member since') {{ showDateTime($user->created_at, 'Y') }}</span>
                        <div class="d-flex gap-2 mb-3">
                            @if ($authUser && $authUser->id == $user->id)
                                <a href="{{ route('user.profile') }}" class="btn btn--sm btn--base py-0"><small><i class="ti ti-edit"></i></small> @lang('Edit Profile')</a>
                            @endif
                        </div>
                        <ul class="social-list">
                            @foreach ($user->socialProfiles as $account)
                                <li class="social-list__item"><a href="{{ $account->url }}" class="social-list__link flex-center">@php echo $account->icon; @endphp</a> </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="user-profile__banner__info">
                    {{-- Removed Downloads and Following statistics as per requirements --}}
                </div>
            </div>
        </div>
    </div>
</div>
@push('page-script')
    <script>
        (function($) {
            'use strict';

            $('#updateProfileImage').on('change', function(event) {
                event.preventDefault();

                let fileInput = event.target.files[0];

                let data = new FormData();
                data.append('profile_image', fileInput);
                data.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    type       : "POST",
                    url        : "{{ route('user.profile.image.upload') }}",
                    data       : data,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            $('#profileImage').attr('src', response.image_url);
                            showToasts('success', response.message);
                        } else {
                            showToasts('error', response.message);
                        }
                    },
                    error: function() {
                        showToasts('error', 'Something went wrong');
                    }
                });
            });

            $('#updateCoverImage').on('change', function(event) {
                event.preventDefault();

                let fileInput = event.target.files[0];

                let data = new FormData();
                data.append('cover_image', fileInput);
                data.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    type       : "POST",
                    url        : "{{ route('user.cover.image.upload') }}",
                    data       : data,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            $('#coverImageContainer').css('background-image', `url(${response.image_url})`);
                            showToasts('success', response.message);
                        } else {
                            showToasts('error', response.message);
                        }
                    },
                    error: function() {
                        showToasts('error', 'Something went wrong');
                    }
                });
            });
          
        })(jQuery);
    </script>
@endpush