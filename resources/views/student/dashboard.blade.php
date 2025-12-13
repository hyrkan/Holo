<!DOCTYPE html>
<html lang="zxx">
<head>
    <title>Holo Board || Student Dashboard</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome, {{ Auth::guard('student')->user()->name ?? 'Student' }}!</h1>
        <p>This is your student dashboard.</p>
        
        <form action="{{ route('student.logout') }}" method="POST">
            @csrf
            <button class="btn btn-danger">Logout</button>
        </form>
    </div>
</body>
</html>
