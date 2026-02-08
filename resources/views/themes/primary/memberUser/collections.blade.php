@extends($activeTheme . 'layouts.frontend')
@section('frontend')
    @include($activeTheme . 'memberUser.banner', ['user' => $user])

    <div class="product py-120">
        <div class="container">
            <div class="product__nav">
                <a href="{{ route('member.user.profile', [encrypt($user->id), slug($user->username)]) }}" class="product__nav__link active"><i class="ti ti-copy-plus"></i> @lang('Collections') ({{ formatNumber($user->collections->count()) }})</a>
                <a href="{{ route('member.user.following', [encrypt($user->id), slug($user->username)]) }}" class="product__nav__link"><i class="ti ti-user-check"></i> @lang('Following')</a>
            </div>

            <div id="authorCollectionDiv">
                @include($activeTheme . 'ajax.memberUserCollections', ['collections' => $collections, 'user' => $user])
            </div>
            
        </div>
   </div>
@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            $(document).on('submit', '.authorCollectionPaginationForm', function(e) {
                e.preventDefault();

                let pageNumber = $(this).find('#pageNumberInput').val();

                authorCollectionPaginationAjax(pageNumber);
            });

            $(document).on('click', '.authorCollectionPagination a', function(e) {
                e.preventDefault();

                let page = $(this).attr('href').split('page=')[1];
                
                authorCollectionPaginationAjax(page);
            });

            function authorCollectionPaginationAjax(page) {

                let baseUrl = `{{ route('member.user.profile', [encrypt($user->id), slug($user->username)]) }}`;
                
                $.ajax({
                    type    : "GET",
                    url     : baseUrl + "?page=" + page,
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $(window).scrollTop(0);
                            $('#authorCollectionDiv').html(response.html);
                        } else {
                            showToasts('error', response.message);
                        }
                    },
                    error: function() {
                        showToasts('error', 'Something went wrong');
                    }
                });
            }
        })(jQuery);
    </script>
@endpush