@extends('layouts.site')

@section('content')
<article class="py-14 sm:py-[72px] lg:py-[100px]">
  <div class="wrap max-w-[860px]">
    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold hover:text-rust">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg> All guides
    </a>

    <span class="accent-bar mt-10"></span>
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

@include('partials.contact-cta')
@endsection
