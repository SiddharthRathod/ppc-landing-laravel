@extends('layouts.site')

{{-- Home page only: Organization structured data for Google.
     JSON_HEX_TAG turns < and > into < / >, so admin text can never close the <script> tag. --}}
@if ($page->slug === 'home')
  @push('head')
    <script type="application/ld+json">{!! json_encode($settings->organizationSchema(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
  @endpush
@endif

{{-- Every section of the page, in the order set in the admin.
     Each block is ['type' => 'hero', 'data' => [...]] and is drawn by sections/{type}.blade.php. --}}
@section('content')
  @foreach ($page->content ?? [] as $block)
    @includeIf('sections.'.$block['type'], ['data' => $block['data']])
  @endforeach
@endsection
