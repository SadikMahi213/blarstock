@extends($activeTheme. 'layouts.frontend')
@section('frontend')
    @include($activeTheme . 'partials.breadcrumb')
    <section class="py-120">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card custom--card">
                        <div class="card-body">
                            @php
                                echo $policy->data_info->details
                            @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
