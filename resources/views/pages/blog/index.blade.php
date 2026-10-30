@extends('layouts.site')

@section('content')
<section class="py-14 sm:py-[72px] lg:py-[100px]">
  <div class="wrap">
    <span class="accent-bar"></span>
    <p class="eyebrow mt-[26px]">Guides</p>
    <h1 class="mt-[22px] text-[clamp(36px,5vw,60px)] leading-[1.02]">From the blog</h1>
    <p class="mt-5 max-w-[560px] text-base leading-[1.65] text-muted lg:text-lg">Practical guides to planning video, photography and timelapse projects in Saudi Arabia.</p>

    @if ($posts->isEmpty())
      <p class="mt-12 text-muted">No guides published yet.</p>
    @else
      <div class="mt-12 grid grid-cols-1 gap-x-8 gap-y-12 sm:grid-cols-2 lg:mt-16 lg:grid-cols-3">
        @foreach ($posts as $post)
          @include('partials.post-card', ['post' => $post])
        @endforeach
      </div>

      <div class="mt-14">{{ $posts->links() }}</div>
    @endif
  </div>
</section>
@endsection
