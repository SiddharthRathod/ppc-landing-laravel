<footer class="bg-ink pt-14 pb-10 text-cloud lg:pt-[88px]">
  <div class="wrap">
    <div class="flex flex-col gap-12 lg:flex-row lg:justify-between lg:gap-14">
      <div class="min-w-0 lg:flex-[1_1_440px]">
        <img src="{{ $settings->footerLogoUrl() }}" alt="Professional Photography Company" class="block h-[60px] w-auto lg:h-20">
        @if ($settings->footer_tagline)
          <p class="mt-9 text-sm font-semibold tracking-[.24em] text-white">{{ $settings->footer_tagline }}</p>
        @endif
        @if ($settings->footer_tagline_ar)
          <p lang="ar" dir="rtl" class="mt-6 text-left font-arabic text-[19px] font-semibold text-white">{{ $settings->footer_tagline_ar }}</p>
        @endif
      </div>
      <div class="grid grid-cols-2 gap-x-5 gap-y-7 sm:grid-cols-3 sm:gap-8 lg:flex-[1_1_560px]">
        @foreach ($settings->footer_links ?? [] as $column)
          <div><p class="eyebrow mb-6 text-ember">{{ $column['heading'] }}</p><ul class="text-base leading-[2.15]">@foreach ($column['links'] ?? [] as $link)<li><a href="{{ $link['url'] }}" class="hover:text-white">{{ $link['label'] }}</a></li>@endforeach</ul></div>
        @endforeach
        @if ($settings->cities)
          <div><p class="eyebrow mb-6 text-ember">Cities</p><ul class="text-base leading-[2.15]">@foreach ($settings->cities as $city)<li>{{ $city }}</li>@endforeach</ul></div>
        @endif
      </div>
    </div>
    <div class="mt-16 flex flex-col gap-4 border-t border-ink-line pt-8 text-[13px] text-silver sm:flex-row sm:items-center sm:justify-between">
      {{-- Only the parts that are filled in, joined with " · ". --}}
      <p>{{ collect([
          '© '.now()->year.' Professional Photography Company',
          $settings->cr_number ? 'CR '.$settings->cr_number : null,
          $settings->vat_number ? 'VAT '.$settings->vat_number : null,
          'A Studio 52 Group company',
      ])->filter()->join(' · ') }}</p>
      @if ($settings->social_links)
        <ul class="flex flex-wrap gap-5">
          @foreach ($settings->social_links as $social)
            <li><a href="{{ $social['url'] }}" target="_blank" rel="noopener" class="hover:text-white">{{ \Illuminate\Support\Str::headline($social['platform']) }}</a></li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>
</footer>

@if ($settings->whatsappUrl())
<a href="{{ $settings->whatsappUrl() }}" aria-label="Chat with us on WhatsApp"
  class="fixed right-4 bottom-4 z-50 inline-flex size-[58px] items-center justify-center gap-2.5 rounded-full bg-whatsapp text-[15px] font-bold text-ink shadow-[0_8px_24px_rgba(28,31,34,.28)] sm:right-7 sm:bottom-7 sm:h-[60px] sm:w-auto sm:pr-[22px] sm:pl-3.5">
  <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.5 11.6a8.6 8.6 0 0 1-12.7 7.6L3.3 20.5l1.3-4.3a8.6 8.6 0 1 1 15.9-4.6z"/><path d="M9 8.2c-.2 3.3 3.3 6.8 6.6 6.6l1-1.3-2-1.1-.9.8a4.6 4.6 0 0 1-2.3-2.3l.8-.9-1.1-2z" fill="currentColor" stroke-width="1"/></svg>
  <span class="sr-only sm:not-sr-only">Chat on WhatsApp</span>
</a>
@endif
