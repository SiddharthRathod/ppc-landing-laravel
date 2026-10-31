@extends('layouts.site')

{{-- Every section of the page, in the order set in the admin.
     Each block is ['type' => 'hero', 'data' => [...]] and is drawn by sections/{type}.blade.php. --}}
@section('content')
  @foreach ($page->content ?? [] as $block)
    @includeIf('sections.'.$block['type'], ['data' => $block['data']])
  @endforeach
@endsection
