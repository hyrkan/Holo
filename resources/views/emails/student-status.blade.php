<x-mail::message>
# Hello {{ $student->first_name }},

@if ($status === \App\Models\Student::STATUS_APPROVED)
Congratulations! Your account registration on Holo Board has been **approved**.

@if ($student->program)
Your assigned program is: **{{ $student->program }}**.
@endif

@if ($student->year_level)
Your assigned year level is: **{{ $student->year_level }}**.
@endif

You can now log in to your account using your email address and the password you created during registration.

<x-mail::button :url="route('student.login')">
Login Now
</x-mail::button>

@elseif ($status === \App\Models\Student::STATUS_DENIED)
We regret to inform you that your account registration on Holo Board has been **denied**.

@if ($status_message)
**Reason:** {{ $status_message }}
@endif

If you believe this is a mistake, please contact the administration.

@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
