{{-- Quote call-to-action block (App\Filament\Blocks\ContactCtaBlock). Phone, email, WhatsApp: Site settings. --}}
<section id="contact" class="bg-steel py-14 text-white sm:py-[72px] lg:py-[100px]">
  <div class="wrap flex flex-col items-stretch gap-10 lg:flex-row lg:items-center lg:gap-[72px]">
    @if (! empty($data['image']))
      <div class="relative aspect-[4/3] min-w-0 overflow-hidden bg-ink-3 cut-48 sm:aspect-[16/10] sm:cut-64 lg:aspect-auto lg:h-[420px] lg:flex-[1_1_480px] lg:cut-120">
        <img src="{{ media_url($data['image']) }}" alt="{{ $data['image_alt'] ?? '' }}" loading="lazy" class="absolute inset-0 size-full object-cover">
      </div>
    @endif
    <div class="min-w-0 lg:flex-[1_1_520px]">
      @if (! empty($data['eyebrow']))<p class="eyebrow text-white">{{ $data['eyebrow'] }}</p>@endif
      <h2 class="section-title text-[clamp(34px,4.6vw,56px)]">{!! nl2br(e($data['heading'] ?? '')) !!}</h2>
      <div class="mt-[26px] flex flex-col gap-3 text-lg font-bold break-words">
        @if ($settings->phone)<a href="{{ $settings->phoneHref() }}" class="hover:text-peach">{{ $settings->phone }}</a>@endif
        @if ($settings->email)<a href="mailto:{{ $settings->email }}" class="hover:text-peach">{{ $settings->email }}</a>@endif
      </div>
      <div class="mt-[30px] flex flex-col gap-3.5 sm:flex-row sm:flex-wrap">
        <a href="{{ $data['primary_url'] ?? route('contact') }}" class="btn-primary">{{ $data['primary_label'] ?? 'Send your brief' }} <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
        @if ($settings->whatsappUrl())<a href="{{ $settings->whatsappUrl() }}" class="btn-outline-light">WhatsApp us</a>@endif
      </div>
      @if (! empty($data['heading_ar']))
        <p lang="ar" dir="rtl" class="mt-[26px] text-left font-arabic text-[22px] font-semibold">{{ $data['heading_ar'] }}</p>
      @endif
    </div>
  </div>
</section>
