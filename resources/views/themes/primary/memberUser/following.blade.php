@extends($activeTheme . 'layouts.frontend')
@section('frontend')
    @include($activeTheme . 'memberUser.banner', ['user' => $user])

    <div class="product py-120">
        <div class="container">
            <div class="product__nav">
                <a href="{{ route('member.user.profile', [encrypt($user->id), slug($user->username)]) }}" class="product__nav__link"><i class="ti ti-copy-plus"></i> @lang('Collections') ({{ formatNumber($user->collections->count()) }})</a>
                <a href="{{ route('member.user.following', [encrypt($user->id), slug($user->username)]) }}" class="product__nav__link active"><i class="ti ti-user-check"></i> @lang('Following')</a>
            </div>
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="custom--card">
                        <div class="card-header">
                            <h3 class="title">@lang('Following')</h3>
                        </div>
                        <div class="card-body">
                            <div class="user-list" id="followings-list" data-offset="56">
                                @if ($followings->isNotEmpty())
                                    @include($activeTheme . 'ajax.following', ['followings' => $followings])
                                @else
                                    <div class="no-data-found">
                                        <p>@lang('Not following anyone yet')</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            let loadingFollowings = false;
            let noMoreFollowings  = false;

            $('#followings-list').on('scroll', function() {
                if (loadingFollowings || noMoreFollowings) {
                    return;
                }

                let userId  = '{{ $user->id }}';
                let offset    = parseInt($(this).attr('data-offset'));
                let scrollDiv = $(this);

                let scrollPosition = $('#followings-list')[0].scrollHeight - $('#followings-list').scrollTop() - $('#followings-list').outerHeight();

                if (scrollPosition <= 0) {
                    loadingFollowings = true;

                    let data = {
                        user_id: userId,
                        offset   : offset
                    }

                    $.ajax({
                        type: "GET",
                        url : "{{ route('member.user.load.more.followings') }}",
                        data: data,
                        success: function (response) {
                            if (response.success && response.html) {
                                $('#followings-list').append(response.html);

                                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
                                tooltipTriggerList.map(function (tooltipTriggerEl) {
                                    return new bootstrap.Tooltip(tooltipTriggerEl)
                                });
                            }

                            if (!response.hasMore) {
                                noMoreFollowings = true;
                            } else {
                                let newOffset = offset + response.followings.length;
                                scrollDiv.attr('data-offset', newOffset).data('offset', newOffset);
                            }

                            if (!response.success) {
                                showToasts('error', response.message);
                            }
                        },
                        error: function() {
                            showToasts('error', 'Something went wrong while loading following authors');
                        }
                    });
                }
            });

        })(jQuery);
    </script>
@endpush