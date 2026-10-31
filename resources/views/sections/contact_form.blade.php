{{-- Contact form block (App\Filament\Blocks\ContactFormBlock). The form fields match StoreContactRequest. --}}
<section class="bg-ink py-14 text-white sm:py-[72px] lg:py-[100px]">
  <div class="wrap grid grid-cols-1 gap-12 lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)] lg:gap-[72px]">
    <div>
      <span class="accent-bar"></span>
      @if (! empty($data['eyebrow']))<p class="eyebrow mt-[26px] text-ember">{{ $data['eyebrow'] }}</p>@endif
      <h1 class="mt-[22px] text-[clamp(36px,5vw,60px)] leading-[1.02]">{!! nl2br(e($data['heading'] ?? '')) !!}</h1>
      @if (! empty($data['text']))
        <p class="mt-5 max-w-[440px] text-base leading-[1.65] text-silver lg:text-lg">{{ $data['text'] }}</p>
      @endif
      <div class="mt-8 flex flex-col gap-3 text-lg font-bold break-words">
        @if ($settings->phone)<a href="{{ $settings->phoneHref() }}" class="hover:text-ember">{{ $settings->phone }}</a>@endif
        @if ($settings->email)<a href="mailto:{{ $settings->email }}" class="hover:text-ember">{{ $settings->email }}</a>@endif
      </div>
      @if (! empty($data['heading_ar']))
        <p lang="ar" dir="rtl" class="mt-[26px] text-left font-arabic text-[22px] font-semibold">{{ $data['heading_ar'] }}</p>
      @endif
    </div>

    <form action="{{ route('contact.store') }}" method="post" novalidate data-contact-form class="border border-ink-line bg-ink-2 p-5 cut-40 sm:p-8 lg:p-10">
      @csrf
      <div data-form-success @unless(session('contact_sent')) hidden @endunless class="mb-6 border-l-4 border-orange bg-ink-3 px-4 py-3 text-[15px]">Brief received. A producer will reply within 24 hours.</div>
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
          <label for="name" class="mb-2 block text-[13px] font-semibold text-cloud">Full name *</label>
          <input id="name" name="name" value="{{ old('name') }}" type="text" required autocomplete="name" class="field" placeholder="Your name">
          <p data-error-for="name" class="mt-1.5 {{ $errors->has('name') ? '' : 'hidden' }} text-[13px] text-ember">{{ $errors->first('name') ?: 'Enter your name.' }}</p>
        </div>
        <div>
          <label for="company" class="mb-2 block text-[13px] font-semibold text-cloud">Company or entity</label>
          <input id="company" name="company" value="{{ old('company') }}" type="text" autocomplete="organization" class="field" placeholder="Company name">
        </div>
        <div>
          <label for="email" class="mb-2 block text-[13px] font-semibold text-cloud">Work email *</label>
          <input id="email" name="email" value="{{ old('email') }}" type="email" required autocomplete="email" class="field" placeholder="you@company.sa">
          <p data-error-for="email" class="mt-1.5 {{ $errors->has('email') ? '' : 'hidden' }} text-[13px] text-ember">{{ $errors->first('email') ?: 'Enter a valid email address.' }}</p>
        </div>
        <div>
          <label for="phone" class="mb-2 block text-[13px] font-semibold text-cloud">Phone or WhatsApp *</label>
          <input id="phone" name="phone" value="{{ old('phone') }}" type="tel" required autocomplete="tel" inputmode="tel" class="field" placeholder="+966 5x xxx xxxx">
          <p data-error-for="phone" class="mt-1.5 {{ $errors->has('phone') ? '' : 'hidden' }} text-[13px] text-ember">{{ $errors->first('phone') ?: 'Enter a phone number we can reach.' }}</p>
        </div>
        <div class="sm:col-span-2">
          <label for="service" class="mb-2 block text-[13px] font-semibold text-cloud">What do you need?</label>
          <div class="relative"><select id="service" name="service" class="field appearance-none pr-11">
            <option value="">Choose a service</option>
            <option @selected(old('service')==='Corporate video')>Corporate video</option>
            <option @selected(old('service')==='Event coverage & live streaming')>Event coverage &amp; live streaming</option>
            <option @selected(old('service')==='Photography')>Photography</option>
            <option @selected(old('service')==='Chroma / green screen')>Chroma / green screen</option>
            <option @selected(old('service')==='Safety & HSE video')>Safety &amp; HSE video</option>
            <option @selected(old('service')==='Construction timelapse')>Construction timelapse</option>
            <option @selected(old('service')==='Drone filming')>Drone filming</option>
            <option @selected(old('service')==='Animation & CGI')>Animation &amp; CGI</option>
            <option @selected(old('service')==='Audio & IVR')>Audio &amp; IVR</option>
            <option @selected(old('service')==='Several of these')>Several of these</option>
          </select><svg class="pointer-events-none absolute top-1/2 right-3.5 -translate-y-1/2 text-silver" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></div>
        </div>
        <div class="sm:col-span-2">
          <label for="message" class="mb-2 block text-[13px] font-semibold text-cloud">Project brief *</label>
          <textarea id="message" name="message" rows="5" required class="field min-h-[140px] py-3.5" placeholder="Location, dates, deliverables, budget range">{{ old('message') }}</textarea>
          <p data-error-for="message" class="mt-1.5 {{ $errors->has('message') ? '' : 'hidden' }} text-[13px] text-ember">{{ $errors->first('message') ?: 'Tell us a little about the project.' }}</p>
        </div>
        <div class="hidden" aria-hidden="true"><label for="website">Website</label><input id="website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
      </div>
      <div class="mt-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-[13px] text-silver">We only use your details to reply to this request.</p>
        <button type="submit" class="btn-primary w-full sm:w-auto">Send brief <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
      </div>
    </form>
  </div>
</section>
