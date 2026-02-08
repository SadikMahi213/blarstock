@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="py-120">
        <div class="row gy-4">
            <div class="col-12">
                <div class="custom--card h-auto border-0">
                    <div class="card-header">
                        <h3 class="title">{{ __($pageTitle) }}</h3>
                    </div>
                    <table class="table table-borderless table--striped top-rounded-0 table--responsive--md">
                        <thead>
                            <tr>
                                <th>@lang('Asset')</th>
                                <th>@lang('Category')</th>
                                <th>@lang('Last Download')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($downloads as $download)
                                <tr>
                                    <td>
                                        <div>
                                            <p>{{ __($download->imageFile->image->title) }} | {{ $download->imageFile->resolution }}</p>
                                            <p>- @lang('By') <a href="{{ route('author.profile', [encrypt($download->author_id), slug($download->author->author_name)]) }}" class="fw-semibold">{{ __($download->author->author_name) }}</a></p>
                                        </div>
                                    </td>
                                    <td><span class="badge bg--warning">{{ __($download->imageFile->image->category->name) }}</span></td>
                                    <td>
                                        <div>
                                            <p>{{ showDateTime($download->created_at, 'F d, Y') }}</p>
                                            <p>{{ showDateTime($download->created_at, 'h:i A') }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <span role="button" class="btn btn--sm btn--icon btn--base downloadBtn"
                                            data-route="{{ route('user.download.file', encrypt($download->image_file_id)) }}"
                                            data-label="{{ __($download->imageFile->image->title) }} [{{ $download->imageFile->resolution }}]">
                                            <i class="ti ti-download"></i>
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="no-data-table" colspan="100%" rowspan="100%">
                                        <div class="no-data-found">
                                            <img src="{{ getImage('assets/universal/images/noData.png') }}" alt="{{ __($emptyMessage) }}">
                                            <span>@lang('No downloads found')</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>         
        </div>

        @if ($downloads->hasPages())
            <ul class="pagination">
                @if ($downloads->onFirstPage())
                    <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-left"></i></span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $downloads->previousPageUrl() }}"><i class="ti ti-chevrons-left"></i></a></li>
                @endif

                @foreach ($downloads->links()->elements[0] as $page => $url)
                    <li class="page-item {{ $page == $downloads->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endforeach

                @if ($downloads->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $downloads->nextPageUrl() }}"><i class="ti ti-chevrons-right"></i></a></li>
                @else
                    <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-right"></i></span></li>                
                @endif
            </ul>
        @endif
    </div>
@endsection