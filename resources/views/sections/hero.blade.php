{{-- Hero block (App\Filament\Blocks\HeroBlock). $data = the block's fields. --}}
<section class="pt-10 pb-14 lg:pt-16 lg:pb-[72px]">
  <div class="wrap flex flex-col items-stretch gap-10 lg:flex-row lg:items-center lg:gap-16">
    <div class="min-w-0 lg:flex-[1_1_560px]">
      <span class="accent-bar"></span>
      @if (! empty($data['eyebrow']))
        <p class="eyebrow mt-[26px]">{{ $data['eyebrow'] }}</p>
      @endif
      <h1 class="mt-[22px] text-[clamp(40px,6.2vw,76px)] leading-none">{!! nl2br(e($data['heading'] ?? '')) !!}</h1>
      @if (! empty($data['heading_ar']))
        <p lang="ar" dir="rtl" class="mt-[22px] text-left font-arabic text-[24px] font-semibold text-rust sm:text-[30px]">{{ $data['heading_ar'] }}</p>
      @endif
      @if (! empty($data['text']))
        <p class="mt-5 max-w-[580px] text-[17px] leading-[1.6] text-muted sm:text-[19px]">{{ $data['text'] }}</p>
      @endif
      @if (! empty($data['chips']))
        <ul class="mt-6 flex flex-wrap gap-2 text-[13px] font-semibold">
          @foreach ($data['chips'] as $chip)
            <li class="inline-flex items-center gap-2 border-[1.5px] border-rule px-3.5 py-[9px] cut-10"><span class="size-[5px] rounded-full bg-orange" aria-hidden="true"></span>{{ $chip }}</li>
          @endforeach
        </ul>
      @endif
      <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:flex-wrap">
        @if (! empty($data['primary_label']))
          <a href="{{ $data['primary_url'] ?? '#' }}" class="btn-primary">{{ $data['primary_label'] }} <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
        @endif
        @if (! empty($data['secondary_label']))
          <a href="{{ $data['secondary_url'] ?? '#' }}" class="btn-outline">{{ $data['secondary_label'] }}</a>
        @endif
      </div>
      @if (! empty($data['showreel_url']))
        <a href="{{ $data['showreel_url'] }}" target="_blank" rel="noopener" class="mt-6 inline-flex items-center gap-2 font-semibold hover:text-rust">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg> Watch showreel
        </a>
      @endif
    </div>
    <div class="relative aspect-[4/3] min-w-0 overflow-hidden bg-ink-3 lg:aspect-auto lg:h-[600px] lg:flex-[1_1_520px]">
      @if (! empty($data['image']))
        {{-- Above the fold: load it first (fetchpriority) and never lazily. --}}
        <img src="{{ media_url($data['image']) }}" alt="{{ $data['image_alt'] ?? '' }}" fetchpriority="high" class="absolute inset-0 size-full object-cover">
      @endif
      @if (! empty($data['video']))
        {{-- Muted + playsinline are needed for autoplay on phones. The photo is the poster while it loads. --}}
        <video autoplay muted loop playsinline preload="metadata" poster="{{ media_url($data['image'] ?? null) }}" aria-hidden="true" class="absolute inset-0 size-full object-cover">
          <source src="{{ media_url($data['video']) }}" type="video/mp4">
        </video>
      @endif
      <span class="absolute top-5 left-[18px] size-6 border-t-[3px] border-l-[3px] border-white sm:top-12 sm:left-[42px] sm:size-9"></span>
      <span class="absolute top-5 right-[18px] size-6 border-t-[3px] border-r-[3px] border-white sm:top-12 sm:right-[42px] sm:size-9"></span>
      <span class="absolute bottom-5 left-[18px] size-6 border-b-[3px] border-l-[3px] border-white sm:bottom-14 sm:left-[42px] sm:size-9"></span>
      <span class="absolute right-[18px] bottom-5 size-6 border-r-[3px] border-b-[3px] border-white sm:right-[42px] sm:bottom-14 sm:size-9"></span>
      <span class="absolute top-[30px] left-[30px] inline-flex items-center gap-2.5 bg-[rgba(44,48,52,.85)] px-2.5 py-2 text-[11px] leading-none font-semibold tracking-[.16em] text-white sm:top-16 sm:left-16 sm:px-3.5 sm:py-3 sm:text-[13px]"><span class="size-2 rounded-full bg-orange"></span>REC</span>
      <span class="absolute top-[30px] right-[30px] bg-[rgba(44,48,52,.85)] px-2.5 py-2 text-[11px] leading-none font-semibold tracking-[.16em] text-white sm:top-16 sm:right-16 sm:px-3.5 sm:py-3 sm:text-[13px]">00:00:30:00</span>
    </div>
  </div>
  @if (! empty($data['stats']))
    <dl class="wrap mt-11 grid grid-cols-2 gap-x-5 gap-y-7 border-t border-line pt-10 lg:mt-16 lg:grid-cols-4 lg:gap-10">
      @foreach ($data['stats'] as $stat)
        <div class="flex flex-col"><span class="accent-bar"></span><dd class="order-1 mt-5 text-[38px] leading-none font-extrabold tracking-[-.04em] lg:text-[46px]">{{ $stat['value'] }}</dd><dt class="order-2 mt-2 text-[15px] leading-[1.55] text-muted">{{ $stat['label'] }}</dt></div>
      @endforeach
    </dl>
  @endif
</section>
