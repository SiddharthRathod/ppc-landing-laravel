{{-- $latestPosts: the 3 latest published posts (view composer in AppServiceProvider). --}}
@if ($latestPosts->isNotEmpty())
<section class="py-14 sm:py-[72px] lg:py-[110px]">
  <div class="wrap">
    <div class="mb-9 flex flex-col items-start gap-5 lg:mb-14 lg:flex-row lg:items-end lg:justify-between lg:gap-10">
      <div>
        <p class="eyebrow">Guides</p>
        <h2 class="section-title">From the blog</h2>
      </div>
      <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 border-b-2 border-ink pb-1 font-semibold hover:text-rust">All guides <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
    </div>
    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
      @foreach ($latestPosts as $post)
        @include('partials.post-card', ['post' => $post])
      @endforeach
    </div>
  </div>
</section>
@endif
