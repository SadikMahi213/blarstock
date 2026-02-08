@extends('admin.layouts.master')

    @section('master')
        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table--striped table-borderless table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('User')</th>
                            <th>@lang('Asset')</th>
                            <th>@lang('Resolution')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Author')</th>
                            <th>@lang('Date')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($downloads as $download)
                            <tr>
                                <td>
                                    <div class="table-card-with-image">
                                        <div class="table-card-with-image__img">
                                            <img src="{{ getImage(getFilePath('userProfile').'/'.$download->user->image, getFileSize('userProfile')) }}" alt="Image">
                                        </div>
                                        <div class="table-card-with-image__content">
                                            <p class="fw-semibold" title="{{ __($download->user->fullname) }}">{{ __(strLimit($download->user->fullname, 20)) }}</p>
                                            <p class="fw-semibold">
                                                <a href="{{ route('admin.user.details', $download->user->id) }}"> <small>@</small>{{ $download->user->username }}</a>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td title="{{ __($download->imageFile->image->title) }}">{{ __(strLimit($download->imageFile->image->title, 25)) }}</td>
                                <td>{{ $download->imageFile->resolution }}</td>
                                <td>
                                    @if ($download->premium == ManageStatus::PREMIUM)
                                        <span class="badge badge--success">@lang('Premium')</span>
                                    @else
                                        <span class="badge badge--secondary">@lang('Free')</span>
                                    @endif
                                </td>
                                <td>{{ __($download->author->author_name) }}</td>
                                <td>{{ showDateTime($download->processed_at) }}</td>
                            </tr>
                        @empty
                            @include('partials.noData')
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($downloads->hasPages())
                {{ paginateLinks($downloads) }}
            @endif
        </div>
    @endsection

    @push('breadcrumb')
        <x-searchForm placeholder="Search" dateSearch="yes" />
    @endpush