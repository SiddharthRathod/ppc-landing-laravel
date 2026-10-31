{{-- Services block (App\Filament\Blocks\ServicesBlock). --}}
<section id="services" class="border-t border-line bg-mist py-14 sm:py-[72px] lg:pt-[110px] lg:pb-[120px]">
  <div class="wrap">
    <div class="mb-9 flex flex-col items-start gap-5 lg:mb-14 lg:flex-row lg:items-end lg:justify-between lg:gap-10">
      <div>
        @if (! empty($data['eyebrow']))<p class="eyebrow">{{ $data['eyebrow'] }}</p>@endif
        <h2 class="section-title">{!! nl2br(e($data['heading'] ?? '')) !!}</h2>
      </div>
      @if (! empty($data['text']))
        <p class="max-w-[470px] text-base leading-[1.65] text-muted lg:text-lg">{{ $data['text'] }}</p>
      @endif
    </div>

    <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 xl:grid-cols-4">
      @foreach ($data['cards'] ?? [] as $card)
        @php($number = str_pad($loop->iteration, 2, '0', STR_PAD_LEFT))
        @if (! empty($card['sub_images']))
          {{-- Wide grey card with up to 4 photos (Photography in the design). --}}
          <a href="{{ $card['url'] ?? '#' }}" class="grid min-h-[300px] grid-cols-1 gap-2.5 bg-steel p-2.5 text-white cut-40 sm:col-span-2 sm:grid-cols-[minmax(0,1fr)_minmax(0,1.15fr)]">
            <span class="flex flex-col py-3 pr-2 pl-3">
              <span class="flex items-center justify-between"><span class="text-[11px] font-bold tracking-[.2em]">{{ $number }}</span>@if (! empty($card['badge']))<span class="bg-orange px-2 py-[5px] text-[10px] font-bold tracking-[.18em] text-ink uppercase">{{ $card['badge'] }}</span>@endif</span>
              <span class="min-h-10 flex-1"></span>
              <span class="accent-bar w-8"></span>
              <span class="mt-3 block text-2xl font-extrabold tracking-[-.02em]">{{ $card['title'] }}</span>
              @if (! empty($card['text']))<span class="mt-1.5 block text-[13px] leading-[1.5] text-snow">{{ $card['text'] }}</span>@endif
            </span>
            <span class="grid min-h-[300px] grid-cols-2 grid-rows-2 gap-1.5 sm:min-h-0">
              @foreach ($card['sub_images'] as $photo)
                <span class="relative block overflow-hidden bg-ink-3 cut-18"><img src="{{ media_url($photo['image']) }}" alt="" loading="lazy" class="absolute inset-0 size-full object-cover"><span class="absolute inset-0 bg-[linear-gradient(180deg,rgba(28,31,34,0)_45%,rgba(28,31,34,.85)_100%)]"></span><span class="absolute bottom-[9px] left-2.5 text-xs font-bold">{{ $photo['label'] }}</span></span>
              @endforeach
            </span>
          </a>
        @else
          <a href="{{ $card['url'] ?? '#' }}" class="relative block min-h-[260px] overflow-hidden bg-ink-3 text-white cut-40 sm:min-h-[300px]">
            @if (! empty($card['image']))
              <img src="{{ media_url($card['image']) }}" alt="" loading="lazy" class="absolute inset-0 size-full object-cover">
            @endif
            <span class="absolute inset-0 bg-[linear-gradient(180deg,rgba(28,31,34,.15)_0%,rgba(28,31,34,.35)_45%,rgba(28,31,34,.92)_100%)]"></span>
            <span class="absolute top-5 left-5 text-[11px] font-bold tracking-[.2em]">{{ $number }}</span>
            <span class="absolute right-5 bottom-[22px] left-5"><span class="accent-bar w-8"></span><span class="mt-3 block text-[19px] leading-[1.15] font-extrabold tracking-[-.01em]">{{ $card['title'] }}</span>@if (! empty($card['text']))<span class="mt-1.5 block text-[13px] leading-[1.45] text-fog">{{ $card['text'] }}</span>@endif</span>
          </a>
        @endif
      @endforeach
    </div>

    @if (! empty($data['cta_text']))
      <div class="mt-10 flex flex-col gap-6 bg-ink px-5 py-6 cut-32 sm:px-8 sm:py-7 lg:flex-row lg:items-center lg:justify-between">
        <p class="text-[17px] font-semibold text-white">{{ $data['cta_text'] }}</p>
        <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
          @if (! empty($data['cta_primary_label']))
            <a href="{{ $data['cta_primary_url'] ?? '#' }}" class="btn-outline-light">{{ $data['cta_primary_label'] }} <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
          @endif
          @if (! empty($data['cta_secondary_label']))
            <a href="{{ $data['cta_secondary_url'] ?? '#' }}" class="btn-primary">{{ $data['cta_secondary_label'] }}</a>
          @endif
        </div>
      </div>
    @endif
  </div>
</section>
