@extends('reviewer.layouts.master')

    @section('master')
        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table--striped table-borderless table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Category')</th>
                            <th>@lang('Filetype')</th>
                            <th>@lang('Author')</th>
                            <th>@lang('Reviewed By')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assets as $asset)
                            <tr>
                                <td>
                                    <div class="table-card-with-image">
                                        <div class="table-card-with-image__img">
                                            @if ($asset->video)
                                                <video src="{{ videoFileUrl($asset->video) }}"></video>
                                            @elseif ($asset->image_name)
                                                <img src="{{ imageUrl(getFilePath('stockImage'), $asset->image_name) }}" alt="Image">
                                            @endif
                                        </div>
                                        <div class="table-card-with-image__content">
                                            <p class="fw-semibold" title="{{ __($asset->title) }}">{{ __(strLimit($asset->title, 30)) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td title="{{ __($asset->category->name) }}">{{ __(strLimit($asset->category->name, 15)) }}</td>
                                <td>{{ __($asset->fileType->name) }}</td>
                                <td>
                                    <p class="fw-semibold">{{ __($asset->user->author_name) }}</p>
                                </td>
                                <td>
                                    @if ($asset->admin_id)
                                        <p class="fw-semibold">@lang('Super Admin')</p>
                                    @else
                                        <p>@lang('Reviewer')</p> 
                                    @endif
                                </td>
                                <td>@php echo $asset->statusBadge; @endphp</td>
                                <td>
                                    <div>
                                        <a href="{{ route('reviewer.asset.detail', $asset->id) }}" class="btn btn--sm btn--base editBtn">
                                            <i class="ti ti-info-square-rounded"></i> @lang('Details')
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('partials.noData')
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($assets->hasPages())
                {{ paginateLinks($assets) }}
            @endif
        </div>


        <x-decisionModal />
    @endsection

    @push('breadcrumb')
        <x-searchForm placeholder="Name or Slug" />
    @endpush