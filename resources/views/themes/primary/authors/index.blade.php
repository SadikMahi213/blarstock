@extends($activeTheme . 'layouts.frontend')
@section('frontend')
    @include($activeTheme . 'partials.breadcrumb')

    <section class="members py-120">
        <div class="container">
            <div class="row g-4 justify-content-center" id="authorIndex">
                @include($activeTheme . 'ajax.authorIndex', ['authors' => $authors, 'defaultUserCover' => $defaultUserCover])
            </div>
        </div>
    </section>
@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            $(document).on('click', '.authorIndex .pagination a', function(e) {
                e.preventDefault();

                let page = $(this).attr('href').split('page=')[1];
                authorSearchAndPaginationAjax(page);
            });

            function authorSearchAndPaginationAjax(page) {

                $.ajax({
                    type    : "GET",
                    url     : "{{ route('author.index') }}?page=" + page,
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $(window).scrollTop(0);
                            $('#authorIndex').html(response.html);
                        } else {
                            showToasts('warning', 'Something went wrong');
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