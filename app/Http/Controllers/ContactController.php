<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Handle a Contact Us submission. Validation runs in StoreContactRequest
     * before this method is called.
     */
    public function store(StoreContactRequest $request): RedirectResponse
    {
        // Next step: save to contact_submissions and dispatch the queued CRM job.

        return back()->with('contact_sent', true);
    }
}
