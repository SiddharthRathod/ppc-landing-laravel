<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Seeded content uses the design images in public/images. They are copied
 * to the public disk so seeded and uploaded images are stored the same way
 * (a path on the disk) and the admin's upload fields can preview them.
 */
trait CopiesDemoImages
{
    /**
     * Copy public/images/{name} to storage/app/public/demo/{name}.
     *
     * @return string The path on the public disk, to store in the database.
     */
    protected function demoImage(string $name): string
    {
        $path = 'demo/'.$name;

        if (! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, file_get_contents(public_path('images/'.$name)));
        }

        return $path;
    }
}
