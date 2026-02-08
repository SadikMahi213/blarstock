@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('Email')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Subject')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Contacted At')</th>
                        <th>@lang('Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contacts as $contact) 
                        <tr>
                            <td>{{ $contacts->firstItem() + $loop->index }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->subject }}</td>
                            <td>
                                @if ($contact->status)
                                    <span class="badge badge--success">@lang('Answered')</span>
                                @else
                                    <span class="badge badge--warning">@lang('Unanswered')</span>
                                @endif
                            </td>
                            <td>{{ showDateTime($contact->created_at) }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="#contactDetails" class="btn btn--sm btn-outline--base detailBtn"
                                        data-bs-toggle = "offcanvas"
                                        data-status    = "{{ $contact->status }}"
                                        data-message   = "{{ $contact->message }}">
                                        <i class="ti ti-info-square-rounded"></i> @lang('Details')
                                    </a>
                                    <div class="custom--dropdown">
                                        <button class="btn btn--icon btn--sm btn--base" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></button>
                                        <ul class="dropdown-menu">
                                            @if ($contact->status)
                                                <li>
                                                    <button type="button" class="dropdown-item decisionBtn" data-question="@lang('Are you confirming to mark this contact as unanswered')?" data-action="{{ route('admin.contact.status', $contact->id) }}">
                                                        <span class="dropdown-icon"><i class="ti ti-phone-x text--warning"></i></span> @lang('Unanswered')
                                                    </button>
                                                </li>
                                            @else
                                                <li>
                                                    <button type="button" class="dropdown-item decisionBtn" data-question="@lang('Are you confirming to mark this contact as answered')?" data-action="{{ route('admin.contact.status', $contact->id) }}">
                                                        <span class="dropdown-icon"><i class="ti ti-phone-incoming text--success"></i></span> @lang('Answered')
                                                    </button>
                                                </li>
                                            @endif
                                            <li>
                                                <button type="button" class="dropdown-item decisionBtn" data-question="@lang('Are you confirming the removal of this contact?')" data-action="{{ route('admin.contact.remove', $contact->id) }}">
                                                    <span class="dropdown-icon"><i class="ti ti-trash text--danger"></i></span> @lang('Delete')
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($contacts->hasPages())
            {{ paginateLinks($contacts) }}
        @endif
    </div>

    <div class="col-12">
        <div class="custom--offcanvas offcanvas offcanvas-end" tabindex="-1" id="contactDetails" aria-labelledby="contactDetailsLabel">
            <div class="offcanvas-header">
                 <h5 class="offcanvas-title" id="contactDetailsLabel">@lang('Contact Details')</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="custom--card h-auto mb-3">
                    <div class="card-body">
                        <h3 class="card-subtitle">@lang('User Message') <span class="statusDiv"></span></h3>
                        <p class="userMessage"></p>
                    </div>
                </div>
            </div>
       </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Email" dateSearch="yes" />
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $('.detailBtn').on('click', function () {
                let message    = $(this).data('message');
                let statusHtml = ``;

                if ($(this).data('status') == 1) {
                    statusHtml += `<span class="badge badge--success">@lang('Answered')</span>`;
                } else {
                    statusHtml += `<span class="badge badge--warning">@lang('Unanswered')</span>`
                }

                $('.statusDiv').html(statusHtml);
                $('.userMessage').text(message);
            });
        })(jQuery);
    </script>
@endpush
