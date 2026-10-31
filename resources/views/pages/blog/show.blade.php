@extends('layouts.site')

@section('content')
<article class="py-14 sm:py-[72px] lg:py-[100px]">
  <div class="wrap max-w-[860px]">
    {{-- "Back to Blog" is in the breadcrumbs above. --}}
    <span class="accent-bar"></span>
    @if ($post->category)
      <p class="eyebrow mt-[26px]">{{ $post->category }}</p>
    @endif
    <h1 class="mt-[22px] text-[clamp(34px,4.6vw,56px)] leading-[1.05]">{{ $post->title }}</h1>
    <p class="mt-5 text-sm text-muted"><time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('j F Y') }}</time></p>

    @if ($post->imageUrl())
      <div class="relative mt-10 aspect-[16/9] overflow-hidden bg-ink-3 cut-40 sm:cut-64">
        <img src="{{ $post->imageUrl() }}" alt="" class="absolute inset-0 size-full object-cover">
      </div>
    @endif

    {{-- The body is HTML from the admin's rich editor: sanitizeHtml() strips scripts and other unsafe markup (XSS). --}}
    <div class="prose prose-lg mt-12 max-w-none prose-headings:font-extrabold prose-headings:tracking-[-.02em] prose-headings:text-ink prose-a:text-rust prose-li:marker:text-orange">
      {!! str($post->body)->sanitizeHtml() !!}
    </div>
  </div>
</article>

@if ($quoteCta)
  @include('sections.contact_cta', ['data' => $quoteCta])
@endif
@endsection
