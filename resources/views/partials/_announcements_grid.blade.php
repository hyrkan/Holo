<div class="row">
    @forelse($announcements as $announcement)
        <div class="col-lg-4 col-md-6 mb-30">
            <div class="event-item mb-30 shadow-sm" style="border-radius: 15px; overflow: hidden; height: 100%; display: flex; flex-direction: column;">
                <div class="event-img" style="height: 200px; overflow: hidden;">
                    @if($announcement->image)
                        <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light" style="width: 100%; height: 100%;">
                            <i class="fas fa-bullhorn fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="event-date">
                        <span>{{ $announcement->start_date->format('d') }}</span>
                        {{ $announcement->start_date->format('M') }}
                    </div>
                </div>
                <div class="event-content p-4" style="flex-grow: 1; display: flex; flex-direction: column;">
                    <div class="mb-3">
                        <span class="badge badge-audience">{{ ucfirst($announcement->target_audience) }}</span>
                        @if($announcement->target_year_levels)
                            @foreach($announcement->target_year_levels as $level)
                                <span class="badge badge-year">{{ $level }}</span>
                            @endforeach
                        @endif
                    </div>
                    <h3 class="mb-3"><a href="javascript:void(0)" class="text-dark hover-primary view-announcement" 
                        data-title="{{ $announcement->title }}" 
                        data-body="{{ $announcement->body }}" 
                        data-image="{{ $announcement->image ? $announcement->image_url : '' }}"
                        data-date="{{ $announcement->start_date->format('d M, Y') }}"
                        data-time="{{ $announcement->start_date->format('h:i A') }}"
                        data-audience="{{ ucfirst($announcement->target_audience) }}"
                                    data-years="{{ $announcement->target_year_levels ? implode(', ', $announcement->target_year_levels) : '' }}"
                                    data-attachments="{{ $announcement->attachments->map(function($a) { 
                                        return [
                                            'name' => $a->file_name, 
                                            'url' => $a->file_url,
                                            'type' => $a->file_type
                                        ]; 
                                    })->toJson() }}">
                                    {{ $announcement->title }}</a></h3>
                    <p class="text-muted mb-4" style="font-size: 0.95rem; line-height: 1.6; flex-grow: 1;">{{ Str::limit(strip_tags($announcement->body), 100) }}</p>
                    <div class="event-meta mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-medium"><i class="far fa-clock me-2 text-primary"></i> {{ $announcement->start_date->format('h:i A') }}</span>
                        <a href="javascript:void(0)" class="btn-read-more view-announcement"
                            data-title="{{ $announcement->title }}" 
                            data-body="{{ $announcement->body }}" 
                            data-image="{{ $announcement->image ? $announcement->image_url : '' }}"
                            data-date="{{ $announcement->start_date->format('d M, Y') }}"
                            data-time="{{ $announcement->start_date->format('h:i A') }}"
                            data-audience="{{ ucfirst($announcement->target_audience) }}"
                            data-years="{{ $announcement->target_year_levels ? implode(', ', $announcement->target_year_levels) : '' }}"
                            data-attachments="{{ $announcement->attachments->map(function($a) { 
                                return [
                                    'name' => $a->file_name, 
                                    'url' => $a->file_url,
                                    'type' => $a->file_type
                                ]; 
                            })->toJson() }}">
                            Read More <i class="fas fa-chevron-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="mb-3">
                <i class="fas fa-search fa-3x text-muted"></i>
            </div>
            <h3>No announcements found</h3>
            <p class="text-muted">Try adjusting your filters or check back later for updates.</p>
            <a href="javascript:void(0)" class="btn btn-primary mt-3 reset-filters">View All Announcements</a>
        </div>
    @endforelse
</div>

<div class="row mt-50">
    <div class="col-12 d-flex justify-content-center">
        {{ $announcements->appends(request()->query())->links() }}
    </div>
</div>
