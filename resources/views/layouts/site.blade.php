<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- SEO: $seo comes from the controller (Page::seo() or Post::seo()) --}}
<x-seo :seo="$seo ?? []" />
{{-- Page-specific extras, e.g. JSON-LD structured data (@push('head') in a page view) --}}
@stack('head')
{{-- BreadcrumbList for Google, built from the same trail as the visible breadcrumbs --}}
@if ($breadcrumbSchema)
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endif
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&family=IBM+Plex+Sans+Arabic:wght@500;600&display=swap">
@vite(['resources/css/app.css', 'resources/js/landing.js'])
</head>
<body>
@include('partials.header')
<x-breadcrumbs :items="$breadcrumbs" />

<main>
@yield('content')
</main>

@include('partials.footer')
</body>
</html>
