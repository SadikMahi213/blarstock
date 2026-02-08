@extends($activeTheme. 'layouts.frontend')
@section('frontend')
    @include($activeTheme . 'partials.breadcrumb')
    <section class="py-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card custom--card">
                        <div class="card-body">
                            @php echo $maintenance->data_info->details @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
