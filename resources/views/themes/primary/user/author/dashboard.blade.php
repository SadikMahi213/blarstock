@extends($activeTheme . 'layouts.auth')
@section('auth')
    <div class="author-dashboard">
        <!-- Dashboard Header -->
        <div class="dashboard-header mb-5">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="dashboard-title mb-1">@lang('Contributor Dashboard')</h1>
                    <p class="dashboard-subtitle text-muted mb-0">@lang('Track your earnings, downloads, and content performance')</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="time-frame-selector">
                        <select id="timeFrameSelector" class="form-select form-select-sm">
                            <option value="daily">@lang('Today')</option>
                            <option value="weekly">@lang('This Week')</option>
                            <option value="monthly" selected>@lang('This Month')</option>
                            <option value="yearly">@lang('This Year')</option>
                            <option value="lifetime">@lang('Lifetime')</option>
                        </select>
                    </div>
                    <div class="available-earnings-badge bg-success text-white px-3 py-2 rounded-pill">
                        <span class="fw-bold">@lang('AVAILABLE EARNINGS')</span>
                        <span class="ms-2 fs-5">{{ $setting->cur_sym }}{{ showAmount($initialData['available_earnings']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics Section -->
        <div class="metrics-section mb-5">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="metric-card downloads-card">
                        <div class="metric-content">
                            <div class="metric-icon bg-primary-light">
                                <i class="ti ti-download"></i>
                            </div>
                            <div class="metric-info">
                                <h3 class="metric-value" id="totalDownloads">
                                    {{ number_format($initialData['total_downloads']) }}
                                </h3>
                                <p class="metric-label">@lang('DOWNLOADS')</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="metric-card earnings-card">
                        <div class="metric-content">
                            <div class="metric-icon bg-success-light">
                                <i class="ti ti-currency-dollar"></i>
                            </div>
                            <div class="metric-info">
                                <h3 class="metric-value" id="totalEarnings">
                                    {{ $setting->cur_sym }}{{ showAmount($initialData['total_earnings']) }}
                                </h3>
                                <p class="metric-label">@lang('EARNINGS')</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-12">
                    <div class="metric-card balance-card">
                        <div class="metric-content">
                            <div class="metric-icon bg-info-light">
                                <i class="ti ti-wallet"></i>
                            </div>
                            <div class="metric-info">
                                <h3 class="metric-value" id="availableEarnings">
                                    {{ $setting->cur_sym }}{{ showAmount($initialData['available_earnings']) }}
                                </h3>
                                <p class="metric-label">@lang('AVAILABLE BALANCE')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Chart Section -->
        <div class="chart-section mb-5">
            <div class="performance-card">
                <div class="card-header border-0 pb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="chart-title mb-0">@lang('12-Week Performance')</h2>
                        <a href="#" class="view-statistics-link">@lang('View My Statistics') →</a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div id="performanceChart" class="chart-container"></div>
                </div>
            </div>
        </div>

        <!-- Content Gallery Section -->
        <div class="gallery-section">
            <div class="content-gallery-card">
                <!-- Tab Navigation -->
                <!-- Tab Navigation -->
                <div class="gallery-tabs mb-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="tab-navigation w-100 w-md-auto">
                            <div class="nav nav-tabs gallery-filter-tabs border-bottom-0" id="contentTabs" role="tablist">
                                <button class="nav-link active" data-type="all" type="button">@lang('All Content')</button>
                                <!-- Dynamic tabs will be inserted here -->
                            </div>
                        </div>
                        
                        <div class="gallery-controls d-flex flex-wrap gap-2 w-100 w-md-auto">
                            <div class="gallery-search">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0">
                                        <i class="ti ti-search"></i>
                                    </span>
                                    <input type="text" id="gallerySearch" class="form-control border-start-0" placeholder="@lang('Search files...')">
                                </div>
                            </div>
                            <div class="gallery-sort">
                                <select id="gallerySort" class="form-select">
                                    <option value="created_at" selected>@lang('Newest First')</option>
                                    <option value="downloads">@lang('Most Downloads')</option>
                                    <option value="title">@lang('Alphabetical')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Content -->
                <div class="tab-content" id="contentTabContent">
                    <div class="tab-pane fade show active" role="tabpanel">
                        <div id="galleryContainer" class="gallery-container">
                            <div class="loading-placeholder text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">@lang('Loading...')</span>
                                </div>
                                <p class="mt-3 text-muted">@lang('Loading your library...')</p>
                            </div>
                        </div>
                        <div id="galleryPagination" class="gallery-pagination mt-4 d-flex justify-content-center"></div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('page-script-lib')
    <script src="{{ asset($activeThemeTrue . 'js/apexcharts.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            'use strict';

            // Initial data from server
            let currentData = @json($initialData);
            let chart;

            // Initialize performance chart with dual axis
            function initializeChart(data) {
                // Simulate downloads data for demonstration
                const downloadsData = data.earnings.map(earning => {
                    return Math.floor(earning * 0.3) + Math.floor(Math.random() * 10);
                });
                
                const options = {
                    series: [
                        {
                            name: 'Earnings',
                            type: 'column',
                            data: data.earnings
                        },
                        {
                            name: 'Downloads',
                            type: 'line',
                            data: downloadsData
                        }
                    ],
                    chart: {
                        height: 400,
                        type: 'line',
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: false
                        }
                    },
                    stroke: {
                        width: [0, 3]
                    },
                    dataLabels: {
                        enabled: false
                    },
                    markers: {
                        size: 5,
                        hover: {
                            size: 7
                        }
                    },
                    xaxis: {
                        categories: data.labels,
                        labels: {
                            rotate: -45,
                            rotateAlways: true
                        }
                    },
                    yaxis: [
                        {
                            seriesName: 'Earnings',
                            axisTicks: {
                                show: true
                            },
                            axisBorder: {
                                show: true,
                                color: '#00E396'
                            },
                            labels: {
                                style: {
                                    colors: '#00E396'
                                },
                                formatter: function(value) {
                                    return '{{ $setting->cur_sym }}' + value.toFixed(2);
                                }
                            },
                            title: {
                                text: "Earnings",
                                style: {
                                    color: '#00E396'
                                }
                            }
                        },
                        {
                            seriesName: 'Downloads',
                            opposite: true,
                            axisTicks: {
                                show: true
                            },
                            axisBorder: {
                                show: true,
                                color: '#FF4560'
                            },
                            labels: {
                                style: {
                                    colors: '#FF4560'
                                }
                            },
                            title: {
                                text: "Downloads",
                                style: {
                                    color: '#FF4560'
                                }
                            }
                        }
                    ],
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(y, { series, seriesIndex, dataPointIndex, w }) {
                                if (typeof y !== "undefined") {
                                    if (seriesIndex === 0) {
                                        return '{{ $setting->cur_sym }}' + y.toFixed(2);
                                    } else {
                                        return y.toFixed(0) + ' downloads';
                                    }
                                }
                                return y;
                            }
                        }
                    },
                    legend: {
                        horizontalAlign: 'left'
                    },
                    colors: ['#00E396', '#FF4560'],
                    grid: {
                        borderColor: '#e7e7e7',
                        row: {
                            colors: ['transparent', 'transparent'],
                            opacity: 0.5
                        }
                    }
                };

                if (chart) {
                    chart.destroy();
                }

                chart = new ApexCharts(document.querySelector("#performanceChart"), options);
                chart.render();
            }

            // Update dashboard metrics
            function updateMetrics(data) {
                $('#totalDownloads').text(new Intl.NumberFormat().format(data.total_downloads));
                $('#totalEarnings').text('{{ $setting->cur_sym }}' + parseFloat(data.total_earnings).toFixed(2));
                $('#availableEarnings').text('{{ $setting->cur_sym }}' + parseFloat(data.available_earnings).toFixed(2));
            }

            // Load data for selected time frame
            function loadData(timeFrame) {
                $.ajax({
                    url: "{{ route('user.author.dashboard.data') }}",
                    method: 'GET',
                    data: {
                        time_frame: timeFrame
                    },
                    beforeSend: function() {
                        // Show loading state
                        $('#performanceChart').addClass('opacity-50');
                    },
                    success: function(response) {
                        currentData = response;
                        updateMetrics(response);
                        initializeChart(response);
                        $('#performanceChart').removeClass('opacity-50');
                    },
                    error: function(xhr) {
                        console.error('Error loading data:', xhr);
                        $('#performanceChart').removeClass('opacity-50');
                        // Show error message
                        alert('Failed to load dashboard data. Please try again.');
                    }
                });
            }

            // Initialize on page load
            $(document).ready(function() {
                initializeChart(currentData);
                initGallery();
                
                // Time frame selector change event
                $('#timeFrameSelector').on('change', function() {
                    const selectedTimeFrame = $(this).val();
                    loadData(selectedTimeFrame);
                });
            });

            // Content Management Functions
            $(document).on('click', '.view-content-btn', function() {
                const contentId = $(this).data('id');
                viewContent(contentId);
            });

            $(document).on('click', '.delete-content-btn', function() {
                const contentId = $(this).data('id');
                deleteContent(contentId);
            });

            $(document).on('click', '.view-all-btn', function() {
                const typeId = $(this).data('type-id');
                const typeName = $(this).data('type-name');
                viewAllContent(typeId, typeName);
            });

            // View content function
            function viewContent(contentId) {
                $.ajax({
                    url: "{{ route('user.author.dashboard.content.file', ['id' => '__ID__']) }}".replace('__ID__', contentId),
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            window.open(response.url, '_blank');
                        } else {
                            alert(response.message || 'Unable to view content');
                        }
                    },
                    error: function() {
                        alert('Error viewing content');
                    }
                });
            }

            // Delete content function
            function deleteContent(contentId) {
                if (!confirm('@lang("Are you sure you want to delete this content? This action cannot be undone.")')) {
                    return;
                }

                $.ajax({
                    url: "{{ route('user.author.dashboard.content.delete', ['id' => '__ID__']) }}".replace('__ID__', contentId),
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showToasts('success', response.message);
                            // Reload the page to refresh content
                            location.reload();
                        } else {
                            showToasts('error', response.message || 'Failed to delete content');
                        }
                    },
                    error: function() {
                        showToasts('error', 'Error deleting content');
                    }
                });
            }

            // View all content for a type
            function viewAllContent(typeId, typeName) {
                // Create modal for viewing all content
                const modalHtml = `
                    <div class="modal fade" id="contentModal" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">${typeName} @lang('Content')</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="content-search mb-3">
                                        <input type="text" class="form-control" id="contentSearch" placeholder="@lang('Search content...')">
                                    </div>
                                    <div class="content-list" id="contentList">
                                        <div class="text-center">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">@lang('Loading...')</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="content-pagination mt-3" id="contentPagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('body').append(modalHtml);
                const modal = new bootstrap.Modal(document.getElementById('contentModal'));
                modal.show();
                
                // Load content
                loadContentTypeContent(typeId, 1);
                
                // Search functionality
                let searchTimeout;
                $('#contentSearch').on('input', function() {
                    clearTimeout(searchTimeout);
                    const searchTerm = $(this).val();
                    searchTimeout = setTimeout(() => {
                        loadContentTypeContent(typeId, 1, searchTerm);
                    }, 500);
                });
                
                // Cleanup modal on close
                $('#contentModal').on('hidden.bs.modal', function() {
                    $(this).remove();
                });
            }

            // Load content for specific type with pagination
            function loadContentTypeContent(typeId, page, search = '') {
                $.ajax({
                    url: "{{ route('user.author.dashboard.content') }}",
                    method: 'GET',
                    data: {
                        type_id: typeId,
                        search: search,
                        page: page
                    },
                    success: function(response) {
                        if (response.success) {
                            renderContentList(response.content.data);
                            renderPagination(response.content, typeId, search);
                        }
                    },
                    error: function() {
                        $('#contentList').html('<div class="alert alert-danger">@lang("Error loading content")</div>');
                    }
                });
            }

            // Render content list in modal
            function renderContentList(contents) {
                let html = '<div class="table-responsive"><table class="table table-hover">';
                html += '<thead><tr><th>@lang("Title")</th><th>@lang("Category")</th><th>@lang("Upload Date")</th><th>@lang("Status")</th><th>@lang("Actions")</th></tr></thead><tbody>';
                
                contents.forEach(content => {
                    html += `<tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="content-thumb me-3">
                                    ${content.thumb ? 
                                        `<img src="${getImageUrl(content.thumb)}" alt="${content.title}" class="rounded" width="50" height="50">` :
                                        `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;"><i class="ti ti-file"></i></div>`
                                    }
                                </div>
                                <div>
                                    <strong>${truncateText(content.title, 50)}</strong>
                                    ${content.tags ? `<div class="mt-1">${renderTags(content.tags)}</div>` : ''}
                                </div>
                            </div>
                        </td>
                        <td>${content.category ? `<span class="badge bg-info">${content.category.name}</span>` : `<span class="text-muted">@lang('Uncategorized')</span>`}</td>
                        <td><span class="text-muted">${formatDate(content.created_at)}</span></td>
                        <td>${getStatusBadge(content.status)}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary view-content-btn" data-id="${content.id}" title="@lang('View')">
                                    <i class="ti ti-eye"></i>
                                </button>
                                <a href="/user/asset/update/${content.id}" class="btn btn-outline-secondary" title="@lang('Edit')">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <button class="btn btn-outline-danger delete-content-btn" data-id="${content.id}" title="@lang('Delete')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
                });
                
                html += '</tbody></table></div>';
                $('#contentList').html(html);
            }

            // Render pagination
            function renderPagination(paginationData, typeId, search) {
                if (paginationData.last_page <= 1) {
                    $('#contentPagination').empty();
                    return;
                }
                
                let html = '<nav><ul class="pagination justify-content-center">';
                
                // Previous button
                if (paginationData.current_page > 1) {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${paginationData.current_page - 1}">@lang('Previous')</a></li>`;
                }
                
                // Page numbers
                for (let i = Math.max(1, paginationData.current_page - 2); i <= Math.min(paginationData.last_page, paginationData.current_page + 2); i++) {
                    const activeClass = i === paginationData.current_page ? 'active' : '';
                    html += `<li class="page-item ${activeClass}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }
                
                // Next button
                if (paginationData.current_page < paginationData.last_page) {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${paginationData.current_page + 1}">@lang('Next')</a></li>`;
                }
                
                html += '</ul></nav>';
                $('#contentPagination').html(html);
                
                // Pagination click handlers
                $('#contentPagination .page-link').on('click', function(e) {
                    e.preventDefault();
                    const page = $(this).data('page');
                    loadContentTypeContent(typeId, page, search);
                });
            }

            // Helper functions
            function getImageUrl(thumb) {
                return "{{ asset('') }}" + "assets/images/stock/" + thumb;
            }

            function truncateText(text, length) {
                return text.length > length ? text.substring(0, length) + '...' : text;
            }

            function renderTags(tags) {
                if (!tags) return '';
                const tagArray = Array.isArray(tags) ? tags : JSON.parse(tags);
                let html = '';
                tagArray.slice(0, 3).forEach(tag => {
                    html += `<span class="badge bg-light text-dark me-1">${tag}</span>`;
                });
                if (tagArray.length > 3) {
                    html += `<span class="badge bg-light text-dark">+${tagArray.length - 3} @lang('more')</span>`;
                }
                return html;
            }

            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });
            }

            function getStatusBadge(status) {
                switch(status) {
                    case 1: return '<span class="badge badge--success">@lang("Approved")</span>';
                    case 2: return '<span class="badge badge--warning">@lang("Pending")</span>';
                    case 3: return '<span class="badge badge--danger">@lang("Rejected")</span>';
                    default: return '<span class="badge badge--secondary">@lang("Unknown")</span>';
                }
            }

            // Toast notification function
            function showToasts(type, message) {
                // Simple alert fallback - you can replace with your toast library
                alert(message);
            }

            // Gallery functionality
            let currentGalleryFilters = {
                type_id: null,
                search: '',
                sort_by: 'created_at',
                page: 1
            };

            // Render filter tabs
            function renderFilterTabs(fileTypes, totalCount) {
                const container = $('.gallery-filter-tabs');
                
                // Clear existing dynamic tabs (keep All)
                container.find('.nav-link:not([data-type="all"])').remove();
                
                // Ensure fileTypes is an array
                const typesArray = Array.isArray(fileTypes) ? fileTypes : Object.values(fileTypes || {});
                
                typesArray.forEach(type => {
                    const tab = `
                        <button class="nav-link" data-type="${type.id}" type="button">
                            ${type.name} <span class="badge bg-light text-dark ms-1">${type.images_count}</span>
                        </button>
                    `;
                    container.append(tab);
                });
            }

            // Initialize gallery
            function initGallery() {
                loadGalleryFilters();
                loadGalleryContent();
                
                // Event listeners: Use .nav-link inside the container
                $(document).on('click', '.gallery-filter-tabs .nav-link', function() {
                    $('.gallery-filter-tabs .nav-link').removeClass('active');
                    $(this).addClass('active');
                    currentGalleryFilters.type_id = $(this).data('type') === 'all' ? null : $(this).data('type');
                    currentGalleryFilters.page = 1;
                    loadGalleryContent();
                });
                
                $('#gallerySort').on('change', function() {
                    currentGalleryFilters.sort_by = $(this).val();
                    currentGalleryFilters.page = 1;
                    loadGalleryContent();
                });
                
                let searchTimeout;
                $('#gallerySearch').on('input', function() {
                    clearTimeout(searchTimeout);
                    const searchTerm = $(this).val();
                    searchTimeout = setTimeout(() => {
                        currentGalleryFilters.search = searchTerm;
                        currentGalleryFilters.page = 1;
                        loadGalleryContent();
                    }, 500);
                });
                
                $(document).on('click', '.stock-gallery-item', function() {
                    // Open view/edit modal or redirect
                    const id = $(this).data('id');
                    window.location.href = `/user/asset/update/${id}`;
                });
                
                $(document).on('click', '.gallery-pagination .page-link', function(e) {
                    e.preventDefault();
                    const page = $(this).data('page');
                    if (page) {
                        currentGalleryFilters.page = page;
                        loadGalleryContent();
                    }
                });
            }

            // Load gallery filters
            function loadGalleryFilters() {
                $.ajax({
                    url: "{{ route('user.author.dashboard.gallery.filters') }}",
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            renderFilterTabs(response.file_types, response.total_count);
                        }
                    },
                    error: function() {
                        console.error('Error loading gallery filters');
                    }
                });
            }

            // Load gallery content
            function loadGalleryContent() {
                $.ajax({
                    url: "{{ route('user.author.dashboard.gallery') }}",
                    method: 'GET',
                    data: currentGalleryFilters,
                    beforeSend: function() {
                        $('#galleryContainer').html(`
                            <div class="text-center py-5">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">@lang('Loading...')</span>
                                </div>
                                <p class="mt-2 text-muted">@lang('Loading your gallery...')</p>
                            </div>
                        `);
                    },
                    success: function(response) {
                        if (response.success) {
                            renderGalleryContent(response.grouped_content);
                            renderGalleryPagination(response.content);
                        } else {
                             $('#galleryContainer').html(`<div class="text-center py-5 text-danger"><i class="ti ti-alert-circle fs-1 mb-2"></i><p>${response.message || '@lang("Error loading content")'}</p></div>`);
                        }
                    },
                    error: function() {
                        $('#galleryContainer').html(`
                            <div class="text-center py-5">
                                <i class="ti ti-alert-circle fs-1 text-danger mb-3"></i>
                                <p class="text-danger">@lang('Error loading gallery content')</p>
                            </div>
                        `);
                    }
                });
            }

            // Render gallery content in Adobe Stock style
            function renderGalleryContent(groupedContent) {
                let html = '';
                
                if (groupedContent.length === 0) {
                    html = `
                        <div class="empty-gallery-state text-center py-5">
                            <i class="ti ti-photo-off fs-1 text-muted mb-3"></i>
                            <h4 class="mb-2">@lang('No content found')</h4>
                            <p class="text-muted mb-4">@lang('Upload your first asset to get started')</p>
                            <a href="{{ route('user.asset.add') }}" class="btn btn-primary">
                                <i class="ti ti-upload"></i> @lang('Upload Content')
                            </a>
                        </div>
                    `;
                } else {
                    groupedContent.forEach(group => {
                        html += `
                            <div class="gallery-date-group mb-4">
                                <h5 class="gallery-date-header text-muted mb-3 pb-2 border-bottom">
                                    ${group.date}
                                </h5>
                                <div class="gallery-grid">
                        `;
                        
                        group.items.forEach(item => {
                            const imageUrl = item.image_url || '/assets/images/placeholder.jpg';
                            const downloadCount = item.downloads_count || 0;
                            html += `
                                <div class="stock-gallery-item" data-image-url="${imageUrl}" data-title="${item.title || 'Untitled'}" data-id="${item.id}">
                                    <div class="gallery-item-wrapper">
                                        <div class="gallery-thumbnail">
                                            <img src="${imageUrl}" alt="${item.title || 'Image'}" class="thumbnail-image" loading="lazy">
                                            <div class="gallery-overlay">
                                                <div class="overlay-top">
                                                    <div class="item-checkbox">
                                                        <input type="checkbox" class="form-check-input">
                                                    </div>
                                                </div>
                                                <div class="overlay-bottom">
                                                    <div class="download-count">
                                                        <i class="ti ti-download me-1"></i>
                                                        <span>${downloadCount}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gallery-item-info">
                                            <h6 class="item-title">${item.title || 'Untitled'}</h6>
                                            <div class="item-meta">
                                                <span class="file-type badge bg-light text-dark">${item.file_type?.name || 'Unknown'}</span>
                                                <span class="upload-date text-muted small">${formatDate(item.created_at)}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        
                        html += `</div></div>`;
                    });
                }
                
                $('#galleryContainer').html(html);
            }

            // Render pagination
            function renderGalleryPagination(paginationData) {
                if (paginationData.last_page <= 1) {
                    $('#galleryPagination').empty();
                    return;
                }
                
                let html = '<nav><ul class="pagination justify-content-center">';
                
                // Previous button
                if (paginationData.current_page > 1) {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${paginationData.current_page - 1}">@lang('Previous')</a></li>`;
                }
                
                // Page numbers
                for (let i = Math.max(1, paginationData.current_page - 2); i <= Math.min(paginationData.last_page, paginationData.current_page + 2); i++) {
                    const activeClass = i === paginationData.current_page ? 'active' : '';
                    html += `<li class="page-item ${activeClass}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }
                
                // Next button
                if (paginationData.current_page < paginationData.last_page) {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${paginationData.current_page + 1}">@lang('Next')</a></li>`;
                }
                
                html += '</ul></nav>';
                $('#galleryPagination').html(html);
            }

            // Open lightbox
            function openLightbox(imageUrl, title) {
                const lightboxHtml = `
                    <div class="gallery-lightbox" id="galleryLightbox">
                        <div class="lightbox-overlay" onclick="closeLightbox()"></div>
                        <div class="lightbox-content">
                            <button class="lightbox-close btn btn-light" onclick="closeLightbox()">
                                <i class="ti ti-x"></i>
                            </button>
                            <div class="lightbox-image-container">
                                <img src="${imageUrl}" alt="${title}" class="lightbox-image">
                            </div>
                            <div class="lightbox-caption">
                                <h5>${title}</h5>
                            </div>
                        </div>
                    </div>
                `;
                
                $('body').append(lightboxHtml);
                $('body').addClass('lightbox-open');
            }

            // Close lightbox
            function closeLightbox() {
                $('#galleryLightbox').remove();
                $('body').removeClass('lightbox-open');
            }

            // Delete gallery item
            function deleteGalleryItem(id) {
                if (!confirm('@lang("Are you sure you want to delete this image?")')) {
                    return;
                }
                
                $.ajax({
                    url: "{{ route('user.author.dashboard.content.delete', ['id' => '__ID__']) }}".replace('__ID__', id),
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showToasts('success', response.message);
                            loadGalleryContent();
                            loadGalleryFilters();
                        } else {
                            showToasts('error', response.message);
                        }
                    },
                    error: function() {
                        showToasts('error', '@lang("Error deleting image")');
                    }
                });
            }

            // Initialize gallery on page load
            $(document).ready(function() {
                initGallery();
                
                // Handle tab switching
                $('#contentTabs .nav-link').on('click', function(e) {
                    e.preventDefault();
                    $(this).tab('show');
                });
                
                // Handle tab shown event
                $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                    const target = $(e.target).attr('data-bs-target');
                    if (target === '#all-content') {
                        loadGalleryContent();
                    }
                });
            });
        })(jQuery);
    </script>
@endpush

@push('page-style')
    <style>
        /* Author Dashboard Base Styles */
        .author-dashboard {
            padding: 2rem 0;
        }
        
        /* Dashboard Header */
        .dashboard-header {
            margin-bottom: 2.5rem;
        }
        
        .dashboard-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }
        
        .dashboard-subtitle {
            font-size: 1.1rem;
            color: #6c757d;
        }
        
        .available-earnings-badge {
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        /* Metrics Section */
        .metrics-section {
            margin-bottom: 2.5rem;
        }
        
        .metric-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }
        
        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .metric-content {
            display: flex;
            align-items: center;
            padding: 1.5rem;
        }
        
        .metric-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .bg-primary-light {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }
        
        .bg-success-light {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }
        
        .bg-info-light {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }
        
        .metric-icon i {
            font-size: 1.5rem;
        }
        
        .metric-info {
            flex: 1;
        }
        
        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: #2c3e50;
        }
        
        .metric-label {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Chart Section */
        .chart-section {
            margin-bottom: 2.5rem;
        }
        
        .performance-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
        }
        
        .chart-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .view-statistics-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        
        .view-statistics-link:hover {
            color: #0b5ed7;
            text-decoration: underline;
        }
        
        .chart-container {
            height: 400px;
            transition: opacity 0.3s ease;
        }
        
        /* Gallery Section */
        .gallery-section {
            margin-top: 2rem;
        }
        
        .content-gallery-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        
        .gallery-tabs {
            padding: 1.5rem 1.5rem 0 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .nav-tabs {
            border-bottom: none;
        }
        
        .nav-tabs .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            border-radius: 0;
            font-weight: 500;
            color: #6c757d;
            padding: 0.75rem 1.25rem;
            margin-right: 0.5rem;
        }
        
        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: #495057;
        }
        
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom-color: #0d6efd;
            background: transparent;
        }
        
        .gallery-controls {
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .gallery-search .input-group {
            width: 200px;
        }
        
        .gallery-search .input-group-text {
            background: #f8f9fa;
            border-color: #dee2e6;
        }
        
        .gallery-sort {
            min-width: 180px;
        }
        
        .gallery-container {
            min-height: 500px;
            padding: 1.5rem;
        }
        
        .loading-placeholder {
            min-height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        /* Stock Gallery Styles */
        .gallery-date-group {
            margin-bottom: 2rem;
        }
        
        .gallery-date-header {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1rem;
        }
        
        @media (min-width: 576px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }
        
        @media (min-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            }
        }
        
        @media (min-width: 992px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
        }
        
        @media (min-width: 1200px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            }
        }
        
        .stock-gallery-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }
        
        .stock-gallery-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .gallery-item-wrapper {
            position: relative;
        }
        
        .gallery-thumbnail {
            position: relative;
            aspect-ratio: 4/3;
            overflow: hidden;
        }
        
        .thumbnail-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .stock-gallery-item:hover .thumbnail-image {
            transform: scale(1.05);
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 0.75rem;
        }
        
        .stock-gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        .overlay-top {
            display: flex;
            justify-content: flex-end;
        }
        
        .item-checkbox {
            background: white;
            border-radius: 4px;
            padding: 2px;
        }
        
        .overlay-bottom {
            display: flex;
            justify-content: flex-start;
        }
        
        .download-count {
            background: rgba(255,255,255,0.9);
            color: #2c3e50;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .gallery-item-info {
            padding: 1rem;
        }
        
        .item-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .item-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .file-type {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .upload-date {
            font-size: 0.75rem;
        }
        
        /* Empty States */
        .empty-gallery-state {
            min-height: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .empty-state {
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 767px) {
            .author-dashboard {
                padding: 1rem 0;
            }
            
            .dashboard-title {
                font-size: 1.5rem;
            }
            
            .dashboard-subtitle {
                font-size: 0.9rem;
            }
            
            .available-earnings-badge {
                font-size: 0.8rem;
                padding: 0.5rem 1rem;
            }
            
            .metric-content {
                padding: 1rem;
            }
            
            .metric-icon {
                width: 50px;
                height: 50px;
                margin-right: 0.75rem;
            }
            
            .metric-icon i {
                font-size: 1.25rem;
            }
            
            .metric-value {
                font-size: 1.5rem;
            }
            
            .gallery-tabs {
                padding: 1rem 1rem 0 1rem;
            }
            
            .gallery-controls {
                flex-direction: column;
                align-items: stretch;
                width: 100%;
            }
            
            .gallery-search .input-group {
                width: 100%;
            }
            
            .gallery-sort {
                width: 100%;
                min-width: auto;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 0.75rem;
            }
            
            .gallery-container {
                padding: 1rem;
            }
        }
        
        @media (max-width: 575px) {
            .dashboard-header .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 1rem;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
            
            .metric-card {
                margin-bottom: 1rem;
            }
        }
        
        /* Loading States */
        .stock-gallery-item.loading {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }
        
        /* Chart Loading State */
        .chart-container.opacity-50 {
            opacity: 0.5;
            pointer-events: none;
        }
        
        /* Tab Pane Transitions */
        .tab-pane {
            transition: opacity 0.2s ease;
        }
        
        .tab-pane.fade:not(.show) {
            opacity: 0;
        }
        
        .tab-pane.fade.show {
            opacity: 1;
        }
        
        /* Focus States for Accessibility */
        .nav-link:focus {
            outline: 2px solid #0d6efd;
            outline-offset: 2px;
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        /* Print Styles */
        @media print {
            .dashboard-header,
            .gallery-tabs,
            .gallery-controls {
                display: none;
            }
            
            .metric-card,
            .performance-card,
            .stock-gallery-item {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
@endpush