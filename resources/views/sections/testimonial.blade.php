{{-- Testimonial block (App\Filament\Blocks\TestimonialBlock). --}}
<section class="relative overflow-hidden bg-ink py-20 text-white lg:py-[120px]">
  @if (! empty($data['image']))
    <img src="{{ media_url($data['image']) }}" alt="" loading="lazy" class="absolute inset-0 size-full object-cover">
  @endif
  <span class="absolute inset-0 bg-ink/75"></span>
  <div class="wrap relative text-center">
    <span class="accent-bar mx-auto"></span>
    <blockquote class="mt-7 text-[clamp(26px,3.6vw,40px)] leading-[1.2] font-extrabold tracking-[-.02em]">“{{ $data['quote'] ?? '' }}”</blockquote>
    @if (! empty($data['author']))
      <p class="mt-[18px] text-[15px] text-cloud">{{ $data['author'] }}</p>
    @endif
    <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row sm:flex-wrap">
      @if (! empty($data['primary_label']))
        <a href="{{ $data['primary_url'] ?? '#' }}" class="btn-primary">{{ $data['primary_label'] }} <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      @endif
      @if (! empty($data['secondary_label']))
        <a href="{{ $data['secondary_url'] ?? '#' }}" class="btn-outline-light">{{ $data['secondary_label'] }}</a>
      @endif
    </div>
  </div>
</section>
