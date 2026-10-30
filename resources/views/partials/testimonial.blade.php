<section class="relative overflow-hidden py-20 text-white lg:py-[120px]">
  <img src="{{ asset('images/testimonial-bg.webp') }}" alt="" loading="lazy" class="absolute inset-0 size-full object-cover">
  <span class="absolute inset-0 bg-ink/75"></span>
  <div class="wrap relative text-center">
    <span class="accent-bar mx-auto"></span>
    <blockquote class="mt-7 text-[clamp(26px,3.6vw,40px)] leading-[1.2] font-extrabold tracking-[-.02em]">“Professional and reliable media partner.”</blockquote>
    <p class="mt-[18px] text-[15px] text-cloud">Daniel Kevin, Senior Electrical Engineer, SEW-Eurodrive. Studio 52 Group client</p>
    <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row sm:flex-wrap">
      <a href="{{ route('contact') }}" class="btn-primary">Get a quote in 24 hours <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      <a href="{{ route('contact') }}" class="btn-outline-light">Talk to a producer</a>
    </div>
  </div>
</section>
