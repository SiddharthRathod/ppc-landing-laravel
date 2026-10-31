{{-- Process steps block (App\Filament\Blocks\ProcessBlock). The last step is drawn in orange. --}}
<section class="py-14 sm:py-[72px] lg:py-[110px]">
  <div class="wrap">
    <div class="mb-10 text-center lg:mb-14">
      @if (! empty($data['eyebrow']))<p class="eyebrow">{{ $data['eyebrow'] }}</p>@endif
      <h2 class="section-title">{!! nl2br(e($data['heading'] ?? '')) !!}</h2>
    </div>
    @php($steps = $data['steps'] ?? [])
    {{-- One column per step on desktop (lg:grid-cols-5 for 5 steps). Full class names so Tailwind finds them. --}}
    <ol class="grid grid-cols-1 {{ [1 => 'lg:grid-cols-1', 2 => 'lg:grid-cols-2', 3 => 'lg:grid-cols-3', 4 => 'lg:grid-cols-4', 5 => 'lg:grid-cols-5', 6 => 'lg:grid-cols-6'][count($steps)] ?? 'lg:grid-cols-5' }}">
      @foreach ($steps as $step)
        <li class="group/step grid grid-cols-[56px_1fr] gap-x-4 lg:block {{ $loop->last ? '' : 'lg:pr-7' }}">
          <div class="flex flex-col items-center lg:flex-row">
            <span class="inline-flex size-14 flex-none items-center justify-center text-xl font-extrabold cut-14 {{ $loop->last ? 'bg-orange text-ink' : 'bg-ink text-white' }}">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
            <span class="{{ $loop->last ? 'hidden h-0.5 flex-1 bg-rule lg:block' : 'w-0.5 flex-1 bg-rule lg:h-0.5 lg:w-auto' }}"></span>
          </div>
          <div class="{{ $loop->last ? '' : 'pb-7 lg:pb-0' }}"><span class="accent-bar mt-1 w-7 lg:mt-[18px]"></span><h3 class="mt-3.5 text-[22px]">{{ $step['title'] }}</h3>@if (! empty($step['text']))<p class="mt-1.5 text-[15px] leading-[1.55] text-muted">{{ $step['text'] }}</p>@endif</div>
        </li>
      @endforeach
    </ol>
  </div>
</section>
