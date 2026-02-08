@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <table class="table table-borderless table--striped table--responsive--sm custom-data-table">
            <thead>
                <tr>
                    <th>@lang('S.N.')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Subject')</th>
                    <th>@lang('Action')</th>
                    </tr>
                </tr>
            </thead>
            <tbody>
                @forelse ($templates as $template)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ __($template->name) }}</td>
                        <td>{{ __($template->subj) }}</td>
                        <td>
                            <a href="{{ route('admin.notification.template.edit', $template->id) }}" class="btn btn--sm btn-outline--base"><i class="ti ti-edit"></i> @lang('Edit')</a>
                        </td>
                    </tr>
                @empty
                    @include('partials.noData')
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('breadcrumb')
    <div class="input--group">
        <input type="search" class="form--control form--control--sm" name="search_table" placeholder="Name / Subject">
        <button class="btn btn--sm btn--icon btn--base" type="submit"><i class="ti ti-search"></i></button>
    </div>
@endpush
