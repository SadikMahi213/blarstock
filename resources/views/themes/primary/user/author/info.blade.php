@extends($activeTheme. 'layouts.auth')
@section('auth')
    <section class="py-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="custom--card h-auto">
                        <div class="card-header">
                            <h5 class="title">@lang('Author Data')</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Author Name') <span class="fw-bold text--base">{{ __($user->author_name) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Status') @php echo $user->authorStatusBadge; @endphp
                                </li>

                                @if($user->author_data)
                                    @foreach($user->author_data as $val)
                                        @continue(!$val->value)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{__($val->name)}}
                                            <span>
                                                @if($val->type == 'checkbox')
                                                    @foreach ((array) $val->value as $checkboxValue)
                                                        <p class="mb-0 text-end">{{ __($checkboxValue) }}</p>
                                                    @endforeach
                                                @elseif($val->type == 'file')
                                                    <a href="{{ route('user.file.download') }}?filePath=verify&fileName={{ $val->value }}" class="me-3"><i class="las la-file-download"></i>  @lang('Attachment') </a>
                                                @else
                                                    <p>{{__($val->value)}}</p>
                                                @endif
                                            </span>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection