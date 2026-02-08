@extends('reviewer.layouts.master')

@section('master')
    <div class="col-12">
        <div class="row g-lg-4 g-3">
            <div class="col-xl-3 col-sm-6">
                <span class="dashboard-widget-1">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-users"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $assetCount['total'] }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Total Assets')</p>
                    </div>
                </span>
            </div>
            
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('reviewer.asset.pending') }}" class="dashboard-widget-1 dashboard-widget-1__info">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-user-check"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $assetCount['pending'] }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Pending Assets')</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('reviewer.asset.approved') }}" class="dashboard-widget-1 dashboard-widget-1__warning">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-at"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $assetCount['approvedByMyself'] }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Approved By Myself')</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('reviewer.asset.rejected') }}" class="dashboard-widget-1 dashboard-widget-1__danger">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-message-off"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $assetCount['rejectedByMyself'] }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Rejected By Myself')</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="custom--card h-auto">
             <div class="card-header">
                  <h3 class="title">@lang('Approved Report')</h3>
                  <small>@lang('My Approval of last 12 months')</small>
             </div>
             <div class="card-body px-0 pb-0">
                  <div id="chartApproved"></div>
             </div>
        </div>
   </div>

   <div class="col-xl-6">
        <div class="custom--card h-auto">
            <div class="card-header">
                <h3 class="title">@lang('Rejection Report')</h3>
                <small>@lang('My Rejection of last 12 months')</small>
            </div>
            <div class="card-body px-0 pb-0">
                <div id="chartRejected"></div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="change-password-modal">
            <div class="change-password-modal__body">
                <button class="btn btn--sm btn--icon btn-outline--secondary change-password-modal__close modal-close"><i class="ti ti-x"></i></button>
                <div class="change-password-modal__img">
                    <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                </div>
                <h3 class="change-password-modal__title">@lang('Security Advisory')</h3>
                <p class="change-password-modal__desc">@lang('Immediate Default Password and Username Change Required')</p>
                <div class="change-password-modal__btn">
                    <a href="{{ route('reviewer.profile') }}" class="btn btn--sm btn--base">@lang('Go For Change')</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script-lib')
    <script src="{{ asset('assets/admin/js/page/apexcharts.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            'use strict';

            @if ($passwordAlert)
                $('.change-password-modal').addClass('active');
            @endif
            
            let months = [
                @foreach($months as $month)
                    "{{ $month }}"@if(!$loop->last),@endif
                @endforeach
            ];

            let approved = [
                @foreach($report['approved'] as $approved)
                    "{{ $approved }}"@if(!$loop->last),@endif
                @endforeach
            ];

            let rejected = [
                @foreach($report['rejected'] as $rejected)
                    "{{ $rejected }}"@if(!$loop->last),@endif
                @endforeach
            ]


            var options = {
                series: [{
                    name: 'Total Approved',
                    data: approved
                }],
                chart: {
                    type: 'bar',
                    height: 392,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: months,
                },
                yaxis: {
                    title: {
                        style: {
                            color: '#7c97bb'
                        }
                    }
                },
                grid: {
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    },
                },
                fill: {
                    opacity: 1
                }
            };

            var chart = new ApexCharts(document.querySelector("#chartApproved"), options);
            chart.render();

            var rejectedOptions = {
                series: [{
                    name: 'Total Rejected',
                    data: rejected
                }],
                chart: {
                    type: 'bar',
                    height: 392,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: months,
                    labels: {
                        style: {
                            colors: '#FF0000'
                        }
                    }
                },
                yaxis: {
                    title: {
                        style: {
                            color: '#FF0000'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#FF0000'
                        },
                    }
                },
                grid: {
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    },
                },
                fill: {
                    colors: ['#FF4C4C'],
                    opacity: 1
                },
                colors: ['#FF4C4C']
            };

            var rejectedChart = new ApexCharts(document.querySelector("#chartRejected"), rejectedOptions);
            rejectedChart.render();

        })(jQuery);
    </script>
@endpush