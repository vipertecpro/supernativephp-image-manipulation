<?php

namespace App\NativeComponents;

use App\Concerns\InteractsWithImageCropper;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

/**
 * Social post example — an Instagram-style post composer.
 *
 * Real feed posts let the user pick between a few ratios (square 1:1,
 * portrait 4:5, landscape 16:9) and add a one-tap filter — but nothing
 * else. `presets` limits the in-screen selector to exactly those three,
 * and `modes` offers crop + filter (no fine colour rulers).
 */
class SocialPost extends NativeComponent
{
    use InteractsWithImageCropper;

    protected function cropperOptions(): array
    {
        return [
            'preset' => 'square',                       // opens on 1:1, like a feed post
            'presets' => ['square', 'portrait', 'landscape'], // the three post ratios
            'modes' => ['crop', 'filter'],              // reframe + one-tap filter, no rulers
            'tools' => ['zoom'],                        // pinch/zoom only — posts aren't rotated
            'outputSize' => 1080,                       // feed-standard export width
        ];
    }

    protected function storageDirectory(): string
    {
        return 'posts';
    }

    protected function uploadEndpoint(): ?string
    {
        return 'https://your-api.example.com/api/posts/image';
    }

    public function render(): View
    {
        return view('native.social-post');
    }
}
