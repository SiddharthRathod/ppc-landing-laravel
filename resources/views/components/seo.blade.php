{{--
  All SEO tags for <head>. Usage in the layout: <x-seo :seo="$seo ?? []" />
  $seo comes from Page::seo() or Post::seo(): title, description, canonical, robots, og_image, type.
  Empty values are left out instead of printing content="".
--}}
@props(['seo' => []])

@php
  $title = $seo['title'] ?? 'Professional Photography Company | Media Production in Saudi Arabia';
  $description = $seo['description'] ?? null;
  $canonical = $seo['canonical'] ?? url()->current();
  $image = $seo['og_image'] ?? null;
@endphp

<title>{{ $title }}</title>
@if ($description)<meta name="description" content="{{ $description }}">@endif
<meta name="robots" content="{{ $seo['robots'] ?? 'index,follow' }}">
<link rel="canonical" href="{{ $canonical }}">

{{-- Open Graph: the preview card on WhatsApp, LinkedIn, Facebook --}}
<meta property="og:type" content="{{ $seo['type'] ?? 'website' }}">
<meta property="og:site_name" content="Professional Photography Company">
<meta property="og:locale" content="en_US">
<meta property="og:title" content="{{ $title }}">
@if ($description)<meta property="og:description" content="{{ $description }}">@endif
<meta property="og:url" content="{{ $canonical }}">
@if ($image)<meta property="og:image" content="{{ $image }}">@endif

{{-- Twitter / X: large image card when there is an image --}}
<meta name="twitter:card" content="{{ $image ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $title }}">
@if ($description)<meta name="twitter:description" content="{{ $description }}">@endif
@if ($image)<meta name="twitter:image" content="{{ $image }}">@endif
