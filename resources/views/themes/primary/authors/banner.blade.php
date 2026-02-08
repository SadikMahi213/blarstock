@php
    $user             = auth()->user();
    $isFollowing      = $user ? $user->following()->where('following_id', $author->id)->exists() : false;
    $defaultUserCover = getSiteData('default_user_cover.content', true);
@endphp
<div class="user-profile__banner bg-img" id="coverImageContainer" data-background-image="{{ $author->cover_image ? getImage(getFilePath('userCover') . '/' . $author->cover_image, getFileSize('userCover')) : getImage($activeThemeTrue . 'images/site/default_user_cover/' . $defaultUserCover?->data_info?->image, '1920x500') }}">
    <div class="update-cover-image">
        @if ($user && $user->id == $author->id)
            <input type="file" id="updateCoverImage">
            <label for="updateCoverImage"><i class="ti ti-photo-up"></i></label>
        @endif
    </div>
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-4">
                <div class="user-profile__banner__profile">
                    <div class="user-profile__banner__profile__img">
                        <img id="profileImage" src="{{ getImage(getFilePath('userProfile') . '/' . $author->image, getFileSize('userProfile'), true) }}" alt="{{ __($author->author_name) }}">

                        @if ($user && $user->id == $author->id)
                            <input type="file" id="updateProfileImage">
                            <label for="updateProfileImage"><i class="ti ti-photo-up"></i></label>
                        @endif
                    </div>
                    <div class="user-profile__banner__profile__txt">
                        <h2 class="user-profile__banner__profile__title">{{ __($author->author_name) }}</h2>
                        <span class="user-profile__banner__profile__info">@lang('Member since') {{ showDateTime($author->joined_at, 'Y') }}</span>
                        <div class="d-flex gap-2 mb-3">
                            @if ($user && $user->id == $author->id)
                                <a href="{{ route('user.profile') }}" class="btn btn--sm btn--base py-0"><small><i class="ti ti-edit"></i></small> @lang('Edit Profile')</a>
                            @elseif ($user && $user->id != $author->id)
                                <span class="btn btn--sm btn--base py-0 followBtn" data-user_id="{{ $user->id }}"><small><i class="ti ti-user-{{ $isFollowing ? 'check' : 'plus' }}"></i></small> <span class="buttonText">{{ $isFollowing ? trans('Following') : trans('Follow') }}</span></span>
                            @else 
                                <span class="btn btn--sm btn--base py-0 signInfoBtn" data-label_text="{{ trans('Follow') }}"><small><i class="ti ti-user-plus"></i></small> @lang('Follow')</span>
                            @endif
                        </div>
                        <ul class="social-list">
                            @foreach ($author->socialProfiles as $account)
                                <li class="social-list__item"><a href="{{ $account->url }}" class="social-list__link flex-center">@php echo $account->icon; @endphp</a> </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="user-profile__banner__info">
                    {{-- Removed Assets, Downloads, and Followers statistics as per requirements --}}
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
                    url        : "{{ route('user.author.profile.image.update') }}",
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
                    url        : "{{ route('user.author.cover.image.update') }}",
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

            $('.followBtn').off('click').on('click', function(event) {
                event.preventDefault();

                let button   = $(this);
                let userId   = button.data('user_id');
                let authorId = '{{ $author->id }}';
                
                let data = {
                    user_id  : userId,
                    author_id: authorId
                }

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
          
        })(jQuery);
    </script>
@endpush