# Quick Reference - Admin Dashboard Components

## 📁 File Structure
```
resources/views/
├── layouts/admin.blade.php              ← Main layout
├── components/admin/
│   ├── sidebar.blade.php                ← Navigation
│   ├── header.blade.php                 ← Top bar
│   ├── footer.blade.php                 ← Footer
│   └── theme-customizer.blade.php       ← Theme settings
└── admin/
    ├── main.blade.php                   ← Original (reference)
    └── dashboard.blade.php              ← New dashboard
```

## 🚀 Create New Page (3 Steps)

### Step 1: Create File
`resources/views/admin/my-page.blade.php`

### Step 2: Add Code
```blade
@extends('layouts.admin')

@section('title', 'My Page Title')

@section('content')
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">My Page</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">My Page</li>
        </ul>
    </div>
</div>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Card Title</h5>
                </div>
                <div class="card-body">
                    <!-- Your content -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

### Step 3: Add Route
`routes/web.php`
```php
Route::get('/admin/my-page', function () {
    return view('admin.my-page');
})->name('admin.my-page');
```

Done! ✅

## 🎨 Common Customizations

### Add Custom CSS
```blade
@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<style>
    .my-custom-class { color: red; }
</style>
@endpush
```

### Add Custom JavaScript
```blade
@push('scripts')
<script src="{{ asset('js/custom.js') }}"></script>
<script>
    console.log('Page loaded');
</script>
@endpush
```

### Change Page Title
```blade
@section('title', 'Custom Page Title')
```

## 📦 Component Locations

| Component | File Path |
|-----------|-----------|
| Layout | `resources/views/layouts/admin.blade.php` |
| Sidebar | `resources/views/components/admin/sidebar.blade.php` |
| Header | `resources/views/components/admin/header.blade.php` |
| Footer | `resources/views/components/admin/footer.blade.php` |
| Theme | `resources/views/components/admin/theme-customizer.blade.php` |

## 🔧 Edit Components

### Update Sidebar Menu
Edit: `resources/views/components/admin/sidebar.blade.php`

### Update Header
Edit: `resources/views/components/admin/header.blade.php`

### Update Footer
Edit: `resources/views/components/admin/footer.blade.php`

## 📋 Common Blade Directives

```blade
@extends('layouts.admin')           // Extend layout
@section('title', 'Page')           // Set title
@section('content')                 // Start content
@endsection                         // End section
@push('styles')                     // Add CSS
@push('scripts')                    // Add JS
{{ asset('path/to/file') }}         // Asset URL
{{ route('route.name') }}           // Route URL
{{ Auth::user()->name }}            // User data
@if(condition)                      // Conditional
@foreach($items as $item)           // Loop
```

## 🎯 Layout Sections

| Section | Purpose | Usage |
|---------|---------|-------|
| `@yield('content')` | Main content | Required |
| `@yield('title')` | Page title | Optional |
| `@stack('styles')` | Custom CSS | Optional |
| `@stack('scripts')` | Custom JS | Optional |

## 📚 Documentation

- `REFACTORING_SUMMARY.md` - Overview & summary
- `ADMIN_COMPONENTS_GUIDE.md` - Detailed guide
- `COMPONENT_STRUCTURE.md` - Visual diagrams

## 💡 Pro Tips

1. **Keep it DRY**: Use the layout for all admin pages
2. **Reference Original**: Check `main.blade.php` for full features
3. **Component First**: Create reusable components for repeated UI
4. **Test Often**: Check each page after changes
5. **Use Stacks**: Add page-specific assets with `@push`

## ⚡ Quick Commands

```bash
# Create new blade file
touch resources/views/admin/my-page.blade.php

# Clear view cache
php artisan view:clear

# List routes
php artisan route:list
```

## 🔗 Asset Helper

```blade
{{ asset('assets/css/style.css') }}
{{ asset('assets/js/script.js') }}
{{ asset('assets/images/logo.png') }}
```

All assets are in `/public/assets/`

## ✅ Checklist for New Page

- [ ] Create blade file in `resources/views/admin/`
- [ ] Extend layout with `@extends('layouts.admin')`
- [ ] Set title with `@section('title')`
- [ ] Add content with `@section('content')`
- [ ] Add route in `routes/web.php`
- [ ] Test the page
- [ ] Add to sidebar menu if needed

---

**Need Help?** Check the documentation files or reference `main.blade.php` for examples.
