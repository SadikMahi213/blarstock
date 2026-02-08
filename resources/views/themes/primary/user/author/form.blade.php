@extends($activeTheme . 'layouts.auth')
@section('auth')
    <div class="py-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="custom--card h-auto">
                        <div class="card-header">
                            <h5 class="title">@lang('Author Form')</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('user.author.store') }}" class="row g-4" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="col-12">
                                    <label class="form--label" for="authorName">@lang('Author Name')</label>
                                    <input type="text" class="form--control" id="authorName" name="author_name" value="{{ old('author_name') }}">
                                </div>

                                <x-phinixForm identifier="act" identifierValue="author" />

                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection