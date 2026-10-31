{{-- Portfolio block (App\Filament\Blocks\PortfolioBlock). --}}
@php
  // Where each of the 7 frames sits in the desktop film strip (4 columns x 3 rows).
  // Frame 1 is the large 2x2 one; on phones every frame is half width except the first.
  $positions = [
      'col-span-2 row-span-2 lg:col-[1/3] lg:row-[1/3]',
      'lg:col-[3] lg:row-[1]',
      'lg:col-[4] lg:row-[1/3]',
      'lg:col-[3] lg:row-[2]',
      'lg:col-[1] lg:row-[3]',
      'lg:col-[2/4] lg:row-[3]',
      'lg:col-[4] lg:row-[3]',
  ];
@endphp
<section id="portfolio" class="bg-ink py-14 text-white sm:py-[72px] lg:pt-[100px] lg:pb-[110px]">
  <div class="wrap">
    <div class="mb-10 flex flex-col items-start gap-5 lg:flex-row lg:items-end lg:justify-between lg:gap-10">
      <div>
        @if (! empty($data['eyebrow']))<p class="eyebrow text-ember">{{ $data['eyebrow'] }}</p>@endif
        <h2 class="section-title">{!! nl2br(e($data['heading'] ?? '')) !!}</h2>
      </div>
      <div class="flex max-w-[440px] flex-col items-start gap-[22px]">
        @if (! empty($data['text']))
          <p class="text-base leading-[1.65] text-silver lg:text-lg">{{ $data['text'] }}</p>
        @endif
        @if (! empty($data['button_label']))
          <a href="{{ $data['button_url'] ?? '#' }}" class="btn-primary">{{ $data['button_label'] }} <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
        @endif
      </div>
    </div>
    <div class="h-3.5 bg-[repeating-linear-gradient(90deg,var(--color-ink-line)_0_18px,transparent_18px_30px)]" aria-hidden="true"></div>
    <div class="my-3.5 grid auto-rows-[160px] grid-cols-2 gap-2 sm:auto-rows-[190px] sm:gap-3 lg:grid-cols-4 lg:grid-rows-[repeat(3,200px)]">
      @foreach ($data['frames'] ?? [] as $frame)
        @php($large = $loop->first)
        <div class="group/pf relative block overflow-hidden bg-ink-3 text-white {{ $positions[$loop->index] ?? '' }}">
          <img src="{{ media_url($frame['image']) }}" alt="{{ $frame['caption'] }}" loading="lazy" class="absolute inset-0 size-full object-cover transition-transform duration-[600ms] group-hover/pf:scale-105">
          <span class="absolute inset-0 bg-[linear-gradient(180deg,rgba(28,31,34,.25)_0%,rgba(28,31,34,0)_35%,rgba(28,31,34,.8)_100%)]"></span>
          @if ($large)
            <span class="absolute top-6 left-6 size-[26px] border-t-[2.5px] border-l-[2.5px] border-white"></span>
            <span class="absolute right-6 bottom-6 size-[26px] border-r-[2.5px] border-b-[2.5px] border-white"></span>
          @endif
          <span class="absolute top-3.5 right-4 text-[11px] font-bold tracking-[.2em] text-cloud">FR {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
          <span class="absolute {{ $large ? 'bottom-[22px] left-[22px] sm:bottom-9 sm:left-10' : 'bottom-4 left-[18px]' }} flex flex-col gap-2"><span class="block h-[3px] w-7 bg-orange"></span><span class="{{ $large ? 'text-[21px] sm:text-[26px]' : 'text-[15px] sm:text-[17px]' }} font-extrabold tracking-[-.01em]">{{ $frame['caption'] }}</span></span>
        </div>
      @endforeach
    </div>
    <div class="h-3.5 bg-[repeating-linear-gradient(90deg,var(--color-ink-line)_0_18px,transparent_18px_30px)]" aria-hidden="true"></div>
  </div>
</section>
