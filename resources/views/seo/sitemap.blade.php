{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
{{-- Printed with {!! !!} because Blade would read a literal "<?" as the start of PHP code. --}}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($pages as $page)
  <url>
    <loc>{{ $page->url() }}</loc>
    <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
  </url>
@endforeach
@if ($posts->isNotEmpty())
  <url>
    <loc>{{ route('blog.index') }}</loc>
    <lastmod>{{ $posts->first()->updated_at->toAtomString() }}</lastmod>
  </url>
@endif
@foreach ($posts as $post)
  <url>
    <loc>{{ route('blog.show', $post) }}</loc>
    <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
  </url>
@endforeach
</urlset>
