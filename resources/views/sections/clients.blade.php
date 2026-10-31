{{-- Client logos block (App\Filament\Blocks\ClientsBlock). --}}
<section class="border-y border-line py-9">
  <div class="wrap">
    @if (! empty($data['heading']))
      <p class="eyebrow text-center text-ink">{{ $data['heading'] }}</p>
    @endif
    <div class="mt-7 flex flex-wrap items-center justify-center gap-x-9 gap-y-6 lg:justify-between lg:gap-8">
      @foreach ($data['logos'] ?? [] as $logo)
        <img src="{{ media_url($logo['image']) }}" alt="{{ $logo['name'] }}" loading="lazy" class="h-auto max-h-[52px] w-auto max-w-[120px] object-contain lg:max-h-[70px] lg:max-w-[180px]">
      @endforeach
    </div>
  </div>
</section>
