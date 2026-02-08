@extends('admin.layouts.master')

@section('master')
        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table--striped table-borderless table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('User')</th>
                            <th>@lang('title')</th>
                            <th>@lang('Total Assets')</th>
                            <th>@lang('Visibility')</th>
                            <th>@lang('Time')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($collections as $collection)
                            <tr>
                                <td>
                                    <div class="table-card-with-image">
                                        <div class="table-card-with-image__img">
                                            <img src="{{ getImage(getFilePath('userProfile').'/'.$collection->user->image, getFileSize('userProfile')) }}" alt="Image">
                                        </div>
                                        <div class="table-card-with-image__content">
                                            <p class="fw-semibold" title="{{ __($collection->user->fullname) }}">{{ __(strLimit($collection->user->fullname, 20)) }}</p>
                                            <p class="fw-semibold">
                                                <a href="{{ route('admin.user.details', $collection->user->id) }}"> <small>@</small>{{ $collection->user->username }}</a>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td title="{{ __($collection->title) }}">{{ __(strLimit($collection->title, 25)) }}</td>
                                <td>{{ formatNumber($collection->collectionImages()->count()) }}</td>
                                <td>
                                    @if ($collection->visibility == ManageStatus::PUBLIC_COLLECTION)
                                        <span class="badge badge--success">@lang('Public')</span>
                                    @else
                                        <span class="badge badge--warning">@lang('Private')</span>
                                    @endif
                                </td>
                                <td>{{ showDateTime($collection->created_at) }}</td>
                                <td>
                                    <div>
                                        @if ($collection->status)
                                            <button type="button" class="btn btn--sm btn--warning decisionBtn" 
                                                data-question="@lang('Are you confirming the inactivation of this collection')?" 
                                                data-action="{{ route('admin.collection.status', $collection->id) }}">
                                                <i class="ti ti-ban"></i> @lang('Inactive')
                                            </button>
                                        @else
                                            <button type="button" class="btn btn--sm btn--success decisionBtn" 
                                                data-question="@lang('Are you confirming the activation of this collection')?" 
                                                data-action="{{ route('admin.collection.status', $collection->id) }}">
                                                <i class="ti ti-circle-check"></i> @lang('Active')
                                            </button>
                                        @endif

                                        <button class="btn btn--sm btn-outline--danger editBtn"
                                            data-action="{{ route('admin.collection.delete', $collection->id) }}"
                                            data-question="@lang('Are you confirming the deletion of this collection')?">
                                            <i class="ti ti-trash"></i> @lang('Delete')
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('partials.noData')
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($collections->hasPages())
                {{ paginateLinks($collections) }}
            @endif
        </div>

        <x-decisionModal />
    @endsection

    @push('breadcrumb')
        <x-searchForm placeholder="Search" dateSearch="yes" />
    @endpush