<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Services\ContactSubmissionService;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Handle a Contact Us submission. Validation runs in StoreContactRequest
     * before this method is called.
     */
    public function store(StoreContactRequest $request, ContactSubmissionService $service): RedirectResponse
    {
        // except('website'): the empty honeypot field is not stored.
        $service->submit($request->safe()->except('website'), $request->ip(), $request->userAgent());

        return back()->with('contact_sent', true);
    }
}
