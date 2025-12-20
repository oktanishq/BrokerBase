🚀 Step-by-Step: Adding New Admin Pages
Step 1: Create New Page File
# Create file: laravel/resources/views/admin/new-page.blade.php
Step 2: Extend Admin Layout
{{-- admin/new-page.blade.php --}}
@extends('layouts.admin')  {{-- 🎯 FETCHES admin.blade.php layout --}}
Step 3: Add Page-Specific Content
@extends('layouts.admin')

{{-- Optional: Custom page title --}}
@section('title', 'My New Page')

{{-- Optional: Extra head content --}}
@section('head')
    {{-- Custom CSS/JS for this page --}}
@endsection

{{-- Required: Header content (title/breadcrumbs) --}}
@section('header-content')
    <h2>My New Page Title</h2>
    {{-- OR --}}
    <nav aria-label="Breadcrumb"><!-- breadcrumbs --></nav>
@endsection

{{-- Required: Main page content --}}
@section('content')
    {{-- Your page-specific content here --}}
    <div class="bg-white p-6 rounded-xl">
        <h3>My page content</h3>
        {{-- Stats, tables, forms, etc. --}}
    </div>
@endsection

{{-- Optional: Extra scripts --}}
@section('scripts')
    {{-- Page-specific JavaScript --}}
@endsection
Step 4: Add Route
{{-- routes/web.php --}}
Route::get('/admin/new-page', function () {
    return view('admin.new-page');
});
🎯 What Happens When Page Loads
Laravel Process:

Route → view('admin.new-page')
Blade → Finds @extends('layouts.admin')
Layout → Loads layouts/admin.blade.php
Merge → Inserts page sections into layout
Render → Complete HTML page
Final Result:

<!-- From admin.blade.php layout -->
<html>
<head>
    <title>My New Page</title>  {{-- From @section('title') --}}
    <!-- Common CSS/JS -->
</head>
<body>
    <x-sidebar />  {{-- From layout --}}
    
    <header>
        <h2>My New Page Title</h2>  {{-- From @section('header-content') --}}
    </header>
    
    <main>
        <div class="bg-white p-6 rounded-xl">
            <h3>My page content</h3>  {{-- From @section('content') --}}
        </div>
    </main>
    
    <x-logout-confirmation-modal />  {{-- From layout --}}
    
    <!-- Global logout JS from layout -->
</body>
</html>
📋 Quick Reference: Available Sections
Section	Required?	Purpose	Example
@section('title')	❌ Optional	Page title	'My Page'
@section('head')	❌ Optional	Extra CSS/JS	<style>...</style>
@section('header-content')	✅ Required	Page title/breadcrumbs	<h2>Title</h2>
@section('content')	✅ Required	Main page content	<div>Content</div>
@section('scripts')	❌ Optional	Extra JavaScript	<script>...</script>
🎨 Real Examples from Our App
Dashboard Page:

@extends('layouts.admin')
@section('header-content')
    <h2>Welcome back, Elite Homes</h2>
@endsection
@section('content')
    {{-- Stats cards, recent listings --}}
@endsection
Analytics Page:

@extends('layouts.admin')
@section('header-content')
    <nav aria-label="Breadcrumb"><!-- breadcrumbs --></nav>
@endsection
@section('content')
    {{-- Charts, metrics --}}
@endsection
💡 Key Benefits
✅ Automatic Features: Every new page gets:

Sidebar navigation
Logout modal functionality
Consistent header layout
Common CSS/JS
✅ Minimal Code: Just 3-5 lines to create a new page

✅ Consistent Design: All pages look and behave the same

✅ Easy Maintenance: Change layout = affects all pages