<x-mail::message>
# 📅 New Event: {{ $event->name }}

A new event has been created that is relevant to your department. Here are the details:

**📍 Location:** {{ $event->location }}

@if($event->description)
{{ $event->description }}
@endif

@if($event->eventDates && $event->eventDates->count() > 0)
<x-mail::panel>
**Event Date(s):**
@foreach($event->eventDates->sortBy('date') as $date)
- {{ \Carbon\Carbon::parse($date->date)->format('F j, Y') }}
@endforeach
</x-mail::panel>
@endif

@if($event->capacity)
**Max Participants:** {{ $event->capacity }}
@else
**Max Participants:** Unlimited
@endif

<x-mail::button :url="route('student.dashboard')">
View Event in Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
