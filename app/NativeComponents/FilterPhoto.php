<?php

namespace App\NativeComponents;

use App\Concerns\InteractsWithImageCropper;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

/**
 * Filter-only example — no cropping at all.
 *
 * With `modes => ['filter']` the editor drops the crop frame and just offers the
 * one-tap filter presets (Original / Vivid / Mono / Noir / Soft / Punch), baked
 * into the full photo on Done.
 */
class FilterPhoto extends NativeComponent
{
    use InteractsWithImageCropper;

    protected function cropperOptions(): array
    {
        return [
            'modes' => ['filter'],   // just filters — no crop, no manual adjustments
        ];
    }

    protected function storageDirectory(): string
    {
        return 'filtered';
    }

    protected function uploadEndpoint(): ?string
    {
        return 'https://your-api.example.com/api/photos/filtered';
    }

    public function render(): View
    {
        return view('native.filter-photo');
    }
}
