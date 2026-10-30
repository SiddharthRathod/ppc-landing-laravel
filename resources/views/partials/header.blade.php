<header class="group sticky top-0 z-40 border-b border-line bg-white" data-header>
  <div class="wrap flex min-h-[76px] items-center justify-between gap-3 lg:min-h-[100px] lg:gap-6">
    <a href="{{ route('home') }}" aria-label="Professional Photography Company home">
      <img src="{{ $settings->logoUrl() }}" alt="Professional Photography Company" class="block h-[46px] w-auto lg:h-[60px]">
    </a>
    <nav aria-label="Main" class="hidden gap-6 text-base font-medium lg:flex xl:gap-[38px]">
      <a href="{{ route('home') }}#services" class="hover:text-rust">Services</a>
      <a href="{{ route('home') }}#portfolio" class="hover:text-rust">Work</a>
      <a href="{{ route('home') }}#industries" class="hover:text-rust">Industries</a>
      <a href="#" class="hover:text-rust">About</a>
      <a href="{{ route('blog.index') }}" class="hover:text-rust">Blog</a>
    </nav>
    <div class="flex items-center gap-3 lg:gap-6">
      <a href="#" lang="ar" class="hidden font-arabic text-[17px] hover:text-rust lg:inline">العربية</a>
      <a href="{{ route('contact') }}" class="btn-outline hidden min-h-[46px] px-[22px] text-[15px] sm:inline-flex">Get a quote</a>
      <button type="button" data-menu-toggle aria-expanded="false" aria-controls="mobile-nav" aria-label="Open menu"
        class="inline-flex size-[46px] items-center justify-center border-2 border-ink text-ink cut-12 lg:hidden">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
          <path class="origin-center transition-transform group-data-open:translate-y-[5px] group-data-open:rotate-45" d="M3 7h18"/>
          <path class="transition-opacity group-data-open:opacity-0" d="M3 12h18"/>
          <path class="origin-center transition-transform group-data-open:-translate-y-[5px] group-data-open:-rotate-45" d="M3 17h18"/>
        </svg>
      </button>
    </div>
  </div>
  <nav id="mobile-nav" aria-label="Mobile" class="grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 group-data-open:grid-rows-[1fr] lg:hidden">
    <div class="overflow-hidden">
      <div class="wrap flex flex-col border-t border-line pt-2 pb-6 text-xl font-bold">
        <a href="{{ route('home') }}#services" class="border-b border-mist py-3">Services</a>
        <a href="{{ route('home') }}#portfolio" class="border-b border-mist py-3">Work</a>
        <a href="{{ route('home') }}#industries" class="border-b border-mist py-3">Industries</a>
        <a href="#" class="border-b border-mist py-3">About</a>
        <a href="{{ route('blog.index') }}" class="border-b border-mist py-3">Blog</a>
        <a href="#" lang="ar" class="border-b border-mist py-3 font-arabic">العربية</a>
        <a href="{{ route('contact') }}" class="btn-primary mt-4 w-full">Get a quote in 24 hours</a>
      </div>
    </div>
  </nav>
</header>
