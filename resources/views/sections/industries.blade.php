{{-- Industries block (App\Filament\Blocks\IndustriesBlock). Icons: sections/icons/{icon}.blade.php --}}
<section id="industries" class="bg-fog py-14 sm:py-[72px] lg:py-[110px]">
  <div class="wrap">
    <div class="mb-10 text-center lg:mb-14">
      @if (! empty($data['eyebrow']))<p class="eyebrow">{{ $data['eyebrow'] }}</p>@endif
      <h2 class="section-title">{!! nl2br(e($data['heading'] ?? '')) !!}</h2>
      @if (! empty($data['text']))
        <p class="mx-auto mt-4 max-w-[640px] text-base leading-[1.6] text-slate sm:text-lg">{{ $data['text'] }}</p>
      @endif
    </div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      @foreach ($data['cards'] ?? [] as $card)
        <article class="flex min-h-[200px] flex-col border-2 border-orange bg-white p-[30px] cut-40 sm:min-h-[230px]">
          <span class="flex items-start justify-between"><span class="text-xs font-semibold tracking-[.2em] text-rust">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="inline-flex size-[52px] flex-none items-center justify-center bg-peach text-orange cut-14">@includeIf('sections.icons.'.($card['icon'] ?? ''))</span></span>
          <span class="flex-1"></span><span class="accent-bar"></span>
          <h3 class="mt-[18px] text-[21px]">{{ $card['title'] }}</h3>
          @if (! empty($card['text']))<p class="mt-2 text-[15px] leading-[1.55] text-muted">{{ $card['text'] }}</p>@endif
        </article>
      @endforeach
    </div>
  </div>
</section>
