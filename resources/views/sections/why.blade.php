{{-- Why us block (App\Filament\Blocks\WhyBlock). --}}
<section class="bg-fog py-14 sm:py-[72px] lg:py-[110px]">
  <div class="wrap flex flex-col items-stretch gap-10 lg:flex-row lg:items-center lg:gap-[72px]">
    @if (! empty($data['image']))
      <div class="relative aspect-[4/3] min-w-0 overflow-hidden bg-ink-3 cut-48 sm:aspect-[16/10] sm:cut-64 lg:aspect-auto lg:h-[520px] lg:flex-[1_1_520px] lg:cut-120">
        <img src="{{ media_url($data['image']) }}" alt="{{ $data['image_alt'] ?? '' }}" loading="lazy" class="absolute inset-0 size-full object-cover">
      </div>
    @endif
    <div class="min-w-0 lg:flex-[1_1_520px]">
      @if (! empty($data['eyebrow']))<p class="eyebrow">{{ $data['eyebrow'] }}</p>@endif
      <h2 class="section-title">{!! nl2br(e($data['heading'] ?? '')) !!}</h2>
      <div class="mt-9 grid grid-cols-1 gap-[22px] sm:grid-cols-2 sm:gap-x-8 sm:gap-y-7">
        @foreach ($data['points'] ?? [] as $point)
          <div><span class="accent-bar w-7"></span><p class="mt-3.5 text-[19px] font-extrabold tracking-[-.01em]">{{ $point['title'] }}</p>@if (! empty($point['text']))<p class="mt-2 text-[15px] leading-[1.55] text-slate">{{ $point['text'] }}</p>@endif</div>
        @endforeach
      </div>
      @if (! empty($data['closing']))
        <p class="mt-8 text-[15px] font-bold">{{ $data['closing'] }}</p>
      @endif
    </div>
  </div>
</section>
