{{--
  Visible breadcrumb trail. $items comes from App\Services\BreadcrumbService (view composer in AppServiceProvider).
  The last item is the current page: no link, aria-current="page". Scrolls sideways on narrow screens.
--}}
@props(['items' => []])

@if (count($items) > 1)
  <nav aria-label="Breadcrumb" class="border-b border-line bg-mist">
    <ol class="wrap flex items-center gap-2 overflow-x-auto py-3 text-[13px] whitespace-nowrap text-muted">
      @foreach ($items as $item)
        <li class="flex items-center gap-2">
          @if ($loop->last)
            <span aria-current="page" class="max-w-[60vw] truncate font-semibold text-ink sm:max-w-none">{{ $item['label'] }}</span>
          @else
            <a href="{{ $item['url'] }}" class="hover:text-rust">{{ $item['label'] }}</a>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
          @endif
        </li>
      @endforeach
    </ol>
  </nav>
@endif
