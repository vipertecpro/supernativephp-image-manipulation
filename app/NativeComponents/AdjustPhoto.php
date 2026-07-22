<?php

namespace App\NativeComponents;

use App\Concerns\InteractsWithImageCropper;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

/**
 * Adjust-only example — no cropping at all.
 *
 * With `modes => ['adjust']` the editor drops the crop frame entirely and works
 * on the WHOLE image: brightness / contrast / saturation rulers, baked into the
 * full photo on Done.
 */
class AdjustPhoto extends NativeComponent
{
    use InteractsWithImageCropper;

    protected function cropperOptions(): array
    {
        return [
            'modes' => ['adjust'],   // just colour adjustments — no crop, no filters
        ];
    }

    protected function storageDirectory(): string
    {
        return 'adjusted';
    }

    protected function uploadEndpoint(): ?string
    {
        return 'https://your-api.example.com/api/photos/adjusted';
    }

    public function render(): View
    {
        return view('native.adjust-photo');
    }
}
