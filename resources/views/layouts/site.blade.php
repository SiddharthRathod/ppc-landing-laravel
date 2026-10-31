<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- SEO: $seo comes from the controller (Page::seo() or Post::seo()) --}}
<title>{{ $seo['title'] ?? 'Professional Photography Company | Media Production in Saudi Arabia' }}</title>
<meta name="description" content="{{ $seo['description'] ?? '' }}">
<meta name="robots" content="{{ $seo['robots'] ?? 'index,follow' }}">
<link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
<meta property="og:title" content="{{ $seo['og_title'] ?? $seo['title'] ?? '' }}">
<meta property="og:description" content="{{ $seo['description'] ?? '' }}">
<meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
@isset($seo['og_image'])<meta property="og:image" content="{{ $seo['og_image'] }}">@endisset
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&family=IBM+Plex+Sans+Arabic:wght@500;600&display=swap">
@vite(['resources/css/app.css', 'resources/js/landing.js'])
</head>
<body>
@include('partials.header')

<main>
@yield('content')
</main>

@include('partials.footer')
</body>
</html>
