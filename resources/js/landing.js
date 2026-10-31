// Mobile menu
(() => {
  const header = document.querySelector('[data-header]');
  const toggle = header?.querySelector('[data-menu-toggle]');
  if (!toggle) return;
  const set = (open) => {
    header.toggleAttribute('data-open', open);
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
  };
  toggle.addEventListener('click', () => set(!header.hasAttribute('data-open')));
  header.querySelectorAll('#mobile-nav a').forEach((a) => a.addEventListener('click', () => set(false)));
  document.addEventListener('keydown', (e) => e.key === 'Escape' && set(false));
  matchMedia('(min-width: 64rem)').addEventListener('change', (e) => e.matches && set(false));
})();

// Contact form: client-side checks. The server validates again; this only gives faster feedback.
(() => {
  const form = document.querySelector('[data-contact-form]');
  if (!form) return;
  const checks = {
    name: (v) => v.trim().length > 1,
    email: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),
    // Same rule as App\Rules\PhoneNumber: allowed characters, 8 to 15 digits.
    phone: (v) => /^\+?[\d\s\-().]+$/.test(v.trim()) && v.replace(/\D/g, '').length >= 8 && v.replace(/\D/g, '').length <= 15,
    message: (v) => v.trim().length > 5,
  };
  const validate = (name) => {
    const field = form.elements[name];
    const ok = checks[name](field.value);
    form.querySelector(`[data-error-for="${name}"]`)?.classList.toggle('hidden', ok);
    field.setAttribute('aria-invalid', String(!ok));
    return ok;
  };
  Object.keys(checks).forEach((n) => form.elements[n].addEventListener('blur', () => validate(n)));
  form.addEventListener('submit', (e) => {
    const results = Object.keys(checks).map(validate);
    if (results.includes(false)) {
      e.preventDefault();
      form.querySelector('[aria-invalid="true"]')?.focus();
      return;
    }
    // Static preview only: show the success state. In Laravel, remove this block and let the form post.
    if (form.getAttribute('action') === '#') {
      e.preventDefault();
      form.querySelector('[data-form-success]').hidden = false;
      form.reset();
    }
  });
})();
