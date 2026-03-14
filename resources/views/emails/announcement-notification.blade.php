<x-mail::message>
# {{ $announcement->title }}

{{ $announcement->body }}

@if($announcement->image)
<img src="{{ asset('storage/' . $announcement->image) }}" alt="{{ $announcement->title }}" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 8px; margin-bottom: 20px;">
@endif

@if($announcement->attachments->count() > 0)
<x-mail::panel>
**Downloadable Attachments:**
@foreach($announcement->attachments as $attachment)
- [{{ $attachment->file_name }}]({{ asset('storage/' . $attachment->file_path) }})
@endforeach
</x-mail::panel>
@endif

<x-mail::button :url="route('student.dashboard')">
View in Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
