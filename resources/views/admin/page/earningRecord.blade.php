@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('Author')</th>
                        <th>@lang('Asset')</th>
                        <th>@lang('Resolution')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Date')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($earnings as $earning)
                        <tr>
                            <td>
                                <div class="table-card-with-image">
                                    <div class="table-card-with-image__img">
                                        <img src="{{ getImage(getFilePath('userProfile').'/'.$earning->author->image, getFileSize('userProfile')) }}" alt="Image">
                                    </div>
                                    <div class="table-card-with-image__content">
                                        <p class="fw-semibold" title="{{ __($earning->author->author_name) }}">{{ __(strLimit($earning->author->author_name, 20)) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td title="{{ __($earning->imageFile->image->title) }}">{{ __(strLimit($earning->imageFile->image->title, 25)) }}</td>
                            <td>{{ $earning->imageFile->resolution }}</td>
                            <td>{{ $setting->cur_sym }}{{ showAmount($earning->amount) }}</td>
                            <td>{{ showDateTime($earning->created_at) }}</td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($earnings->hasPages())
            {{ paginateLinks($earnings) }}
        @endif
    </div>
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search" dateSearch="yes" />
@endpush