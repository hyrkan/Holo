@extends('layouts.landing')

@section('content')
    <!-- slider-area -->
    <section id="parallax" class="slider-area slider-bg second-slider-bg slider-bg2 d-flex align-items-center justify-content-center fix" style="background-image:url({{ asset('landing/img/background.jpg') }})">
        <div class="slider-shape ss-one layer" data-depth="0.10"><img src="{{ asset('landing/img') }}/doddle_6.png" alt="shape"></div>
        <div class="slider-shape ss-two layer" data-depth="0.30"><img src="{{ asset('landing/img') }}/doddle_8.png" alt="shape"></div>
        <div class="slider-shape ss-three layer" data-depth="0.40"><img src="{{ asset('landing/img') }}/doddle_9.png" alt="shape"></div>
        <div class="slider-shape ss-four layer" data-depth="0.60"><img src="{{ asset('landing/img') }}/doddle_7.png" alt="shape"></div>
        <div class="slider-active">
            <div class="single-slider">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="slider-content second-slider-content">
                                <h2 data-animation="fadeInUp animated" data-delay=".4s">Welcome to Holo Board</h2> 
                                <p data-animation="fadeInUp animated" data-delay=".6s">Stay updated with the latest campus announcements and events.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- slider-area-end -->

    <!-- announcements-area -->
    <section id="announcements" class="event-area pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="section-title text-center mb-80">
                        <span>Latest Updates</span>
                        <h2>Campus Announcements</h2>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row mb-50">
                <div class="col-12">
                    <div class="card shadow-sm border-0 p-4" style="background: #ffffff; border-radius: 20px; border: 1px solid #eee;">
                        <form id="filter-form" action="{{ route('welcome') }}" method="GET" class="row g-4 align-items-end">
                            <div class="col-xl-3 col-lg-3 col-md-6">
                                <label class="form-label fw-bold text-dark small mb-2 text-uppercase tracking-wider">Category</label>
                                <select name="category" class="form-select custom-select-styled">
                                    <option value="">All Categories</option>
                                    <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>General</option>
                                    <option value="students" {{ request('category') == 'students' ? 'selected' : '' }}>Students Only</option>
                                    <option value="guests" {{ request('category') == 'guests' ? 'selected' : '' }}>Guests Only</option>
                                </select>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6">
                                <label class="form-label fw-bold text-dark small mb-2 text-uppercase tracking-wider">Year Level</label>
                                <select name="year_level" class="form-select custom-select-styled">
                                    <option value="">All Year Levels</option>
                                    <option value="1st Year" {{ request('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2nd Year" {{ request('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3rd Year" {{ request('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4th Year" {{ request('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4">
                                <label class="form-label fw-bold text-dark small mb-2 text-uppercase tracking-wider">From Date</label>
                                <input type="date" name="date_from" class="form-control custom-input-styled" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4">
                                <label class="form-label fw-bold text-dark small mb-2 text-uppercase tracking-wider">To Date</label>
                                <input type="date" name="date_to" class="form-control custom-input-styled" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4">
                                <div class="d-flex align-items-center">
                                    <button type="submit" class="btn btn-primary-custom flex-grow-1 py-2 me-3">
                                        <i class="fas fa-filter me-2"></i> Filter
                                    </button>
                                    <a href="{{ route('welcome') }}" class="btn btn-outline-custom py-2 px-3 reset-filters" title="Reset Filters">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="announcements-grid-container">
                @include('partials._announcements_grid')
            </div>
        </div>
    </section>
    <!-- announcements-area-end -->

    <!-- Announcement Modal -->
    <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 2rem; padding: 1rem 1.5rem; outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 p-md-5 pt-0">
                    <div id="modal-image-container" class="mb-4 rounded-4 overflow-hidden d-none">
                        <img id="modal-image" src="" alt="" class="img-fluid w-100" style="max-height: 400px; object-fit: cover;">
                    </div>
                    
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span id="modal-audience" class="badge badge-audience"></span>
                        <span id="modal-years" class="badge badge-year d-none"></span>
                    </div>

                    <h2 id="modal-title" class="mb-3"></h2>
                    
                    <div class="d-flex align-items-center gap-3 text-muted small mb-4 pb-3 border-bottom">
                        <span><i class="far fa-calendar-alt me-2 text-primary"></i> <span id="modal-date"></span></span>
                        <span><i class="far fa-clock me-2 text-primary"></i> <span id="modal-time"></span></span>
                    </div>

                    <div id="modal-body" class="announcement-content text-dark mb-4" style="font-size: 1.1rem; line-height: 1.8;">
                    </div>

                    <div id="modal-attachments-container" class="d-none">
                        <h5 class="mb-3">Attachments</h5>
                        <div id="modal-attachments-list" class="list-group list-group-flush">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-custom" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Function to handle AJAX updates
    function updateAnnouncements(url, formData = null) {
        const container = $('#announcements-grid-container');
        container.css('opacity', '0.5'); // Visual feedback
        
        $.ajax({
            url: url,
            data: formData,
            success: function(response) {
                container.html(response);
                container.css('opacity', '1');
                
                // Update URL without refreshing (optional but good for UX)
                if (formData) {
                    const newUrl = url + '?' + formData;
                    window.history.pushState({path: newUrl}, '', newUrl);
                } else {
                    window.history.pushState({path: url}, '', url);
                }
            },
            error: function() {
                container.css('opacity', '1');
                alert('Something went wrong. Please try again.');
            }
        });
    }

    // Handle filter form submission
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const url = $(this).attr('action');
        updateAnnouncements(url, formData);
    });

    // Handle reset button
    $('.reset-filters').on('click', function(e) {
        e.preventDefault();
        $('#filter-form')[0].reset();
        const url = "{{ route('welcome') }}";
        updateAnnouncements(url);
    });

    // Handle pagination clicks
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        updateAnnouncements(url);
    });

    // Modal trigger (delegated because grid is dynamic)
    $(document).on('click', '.view-announcement', function() {
        const data = $(this).data();
        
        $('#modal-title').text(data.title);
        $('#modal-body').html(data.body);
        $('#modal-date').text(data.date);
        $('#modal-time').text(data.time);
        $('#modal-audience').text(data.audience);
        
        if (data.image) {
            $('#modal-image').attr('src', data.image);
            $('#modal-image-container').removeClass('d-none');
        } else {
            $('#modal-image-container').addClass('d-none');
        }
        
        // Handle year levels
        if (data.years) {
            $('#modal-years').text(data.years).removeClass('d-none');
        } else {
            $('#modal-years').addClass('d-none');
        }

        // Handle attachments
        const attachmentsContainer = $('#modal-attachments-container');
        const attachmentsList = $('#modal-attachments-list');
        attachmentsList.empty();
        
        if (data.attachments && data.attachments.length > 0) {
            data.attachments.forEach(file => {
                let icon = 'fa-file';
                if (['jpg', 'jpeg', 'png', 'gif'].includes(file.type.toLowerCase())) icon = 'fa-file-image';
                if (['pdf'].includes(file.type.toLowerCase())) icon = 'fa-file-pdf';
                if (['doc', 'docx'].includes(file.type.toLowerCase())) icon = 'fa-file-word';
                if (['xls', 'xlsx'].includes(file.type.toLowerCase())) icon = 'fa-file-excel';

                attachmentsList.append(`
                    <a href="${file.url}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between p-3 rounded-3 mb-2 border shadow-sm hover-primary">
                        <div class="d-flex align-items-center">
                            <i class="fas ${icon} fa-lg text-primary me-3"></i>
                            <span class="fw-medium">${file.name}</span>
                        </div>
                        <i class="fas fa-download text-muted"></i>
                    </a>
                `);
            });
            attachmentsContainer.removeClass('d-none');
        } else {
            attachmentsContainer.addClass('d-none');
        }
        
        // Show modal
        $('#announcementModal').modal('show');
    });
});
</script>
@endpush

@push('css')
<style>
    :root {
        --primary-color: #002691;
        --primary-hover: #001b66;
        --secondary-color: #6c757d;
        --light-bg: #f8f9fa;
    }

    .event-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #fff;
        border: 1px solid #f0f0f0;
    }
    .event-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .event-img {
        position: relative;
    }
    .event-date {
        position: absolute;
        bottom: 15px;
        left: 15px;
        background: var(--primary-color);
        color: #fff;
        padding: 8px 15px;
        border-radius: 10px;
        text-align: center;
        font-weight: 700;
        line-height: 1.2;
        box-shadow: 0 4px 10px rgba(0,38,145,0.3);
    }
    .event-date span {
        display: block;
        font-size: 1.3rem;
    }
    
    /* Styled Form Controls */
    .custom-select-styled, .custom-input-styled {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .custom-select-styled:focus, .custom-input-styled:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0,38,145,0.1);
    }

    /* Custom Buttons */
    .btn-primary-custom {
        background-color: var(--primary-color);
        border: none;
        color: white;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s;
        box-shadow: none !important;
    }
    .btn-primary-custom:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,38,145,0.3) !important;
        color: white;
    }
    .btn-outline-custom {
        background-color: transparent;
        border: 1px solid #e0e0e0;
        color: var(--secondary-color);
        border-radius: 10px;
        padding-left: 15px;
        padding-right: 15px;
        transition: all 0.2s;
        box-shadow: none !important;
    }
    .btn-outline-custom:hover {
        background-color: #f8f9fa;
        border-color: #ccc;
        color: #333;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05) !important;
    }

    /* Badges */
    .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge-audience {
        background-color: rgba(0,38,145,0.1);
        color: var(--primary-color);
    }
    .badge-year {
        background-color: #f1f3f5;
        color: #495057;
    }

    /* Read More Button */
    .btn-read-more {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s;
    }
    .btn-read-more:hover {
        color: var(--primary-hover);
        transform: translateX(5px);
    }

    .hover-primary:hover {
        color: var(--primary-color) !important;
    }

    .tracking-wider {
        letter-spacing: 0.05em;
    }

    /* Shorten Parallax Section */
    #parallax {
        min-height: 500px !important;
        padding-top: 100px;
    }
    .slider-content h2 {
        font-size: 3.5rem !important;
    }

    .pagination {
        gap: 5px;
    }
    .page-link {
        border-radius: 8px !important;
        margin: 0 2px;
        border: 1px solid #eee;
        color: var(--primary-color);
        font-weight: 600;
    }
    .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
</style>
@endpush
