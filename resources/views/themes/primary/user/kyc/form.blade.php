@extends($activeTheme. 'layouts.frontend')
@section('frontend')
    @include($activeTheme . 'partials.breadcrumb')
    <section class="py-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="custom--card h-auto">
                        <div class="card-header">
                            <h5 class="title">@lang('KYC Form')</h5>
                        </div>
                        <div class="card-body">
                            <form action="" class="row g-4" method="post" enctype="multipart/form-data">
                                @csrf

                                <x-phinixForm identifier="act" identifierValue="kyc" />

                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> 
@endsection