@extends('admin.layouts.master')

    @section('master')
        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table--striped table-borderless table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Category') | @lang('Filetype')</th>
                            <th>@lang('Author')</th>
                            <th>@lang('Likes')</th>
                            <th>@lang('Views')</th>
                            <th>@lang('Downloads')</th>
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
                                            <img src="{{ imageUrl(getFilePath('stockImage'), $asset->thumb) }}" alt="Image">
                                        </div>
                                        <div class="table-card-with-image__content">
                                            <p class="fw-semibold" title="{{ __($asset->title) }}">{{ __(strLimit($asset->title, 15)) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td title="{{ __($asset->category->name) }}">
                                    <div>
                                        <p class="fw-semibold">{{ __(strLimit($asset->category->name, 15)) }}</p>
                                        <p>{{ __($asset->fileType->name) }}</p>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <p class="fw-semibold">{{ __($asset->user->author_name) }}</p>
                                        <p class="fw-semibold">
                                            <a href="{{ route('admin.user.details', $asset->user->id) }}"> <small>@</small>{{ $asset->user->username }}</a>
                                        </p>
                                    </div>
                                </td>
                                <td>{{ __($asset->total_like) }}</td>
                                <td>{{ __($asset->total_view) }}</td>
                                <td>{{ __($asset->total_download) }}</td>
                                <td>@php echo $asset->statusBadge; @endphp</td>
                                <td>
                                    <div>
                                        <a href="{{ route('admin.asset.detail', $asset->id) }}" class="btn btn--sm btn-outline--base editBtn">
                                            <i class="ti ti-info-square-rounded"></i> @lang('Details')
                                        </a>

                                        @if ($asset->is_featured)
                                            <button type="button" class="btn btn--sm btn--warning decisionBtn {{ $asset->status != ManageStatus::IMAGE_APPROVED ? 'disabled' : ''}}"
                                                data-question="@lang('Are you confirming to unfeature of this asset')?"
                                                data-action="{{ route('admin.asset.featured', $asset->id) }}"
                                                @if ($asset->status != ManageStatus::IMAGE_APPROVED) disabled @endif>
                                                <i class="ti ti-pinned-off"></i> @lang('Unfeature')
                                            </button>
                                        @else
                                            <button type="button" class="btn btn--sm btn--base decisionBtn {{ $asset->status != ManageStatus::IMAGE_APPROVED ? 'disabled' : ''}}"
                                                data-question="@lang('Are you confirming to feature of this asset')?"
                                                data-action="{{ route('admin.asset.featured', $asset->id) }}"
                                                @if ($asset->status != ManageStatus::IMAGE_APPROVED) disabled @endif>
                                                <i class="ti ti-flame"></i> @lang('Featur')
                                            </button>
                                        @endif
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
        <x-searchForm placeholder="Search" />
    @endpush
