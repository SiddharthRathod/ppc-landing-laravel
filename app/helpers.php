<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('media_url')) {
    /**
     * Public URL of an uploaded file (a path on the "public" disk), or null.
     * Used by the page sections: {{ media_url($data['image']) }}
     */
    function media_url(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }
}
