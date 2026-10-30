{{-- One blog card. Used on the home page and on /blog. --}}
<a href="{{ route('blog.show', $post) }}" class="group/post block text-ink">
  <span class="relative block h-[220px] overflow-hidden bg-ink-3 cut-40 sm:h-[280px] sm:cut-56">
    @if ($post->imageUrl())
      <img src="{{ $post->imageUrl() }}" alt="" loading="lazy" class="absolute inset-0 size-full object-cover">
    @endif
  </span>
  @if ($post->category)
    <span class="eyebrow mt-5 block">{{ $post->category }}</span>
  @endif
  <span class="mt-2 block text-[22px] leading-[1.2] font-extrabold tracking-[-.02em] group-hover/post:text-rust">{{ $post->title }}</span>
  <span class="mt-3 inline-flex items-center gap-2 text-sm font-semibold">Read guide <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
</a>
