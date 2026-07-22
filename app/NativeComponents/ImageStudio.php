<?php

namespace App\NativeComponents;

use App\Concerns\InteractsWithImageCropper;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

/**
 * Image Studio — the FULL-featured example.
 *
 * Every option is on: all crop presets are switchable live in-screen, and the
 * editor offers crop + adjust + filter modes. This is the "kitchen sink" demo;
 * see {@see ProfilePhoto} / {@see CoverPhoto} for locked-down configurations.
 */
class ImageStudio extends NativeComponent
{
    use InteractsWithImageCropper;

    /** Full-featured: default shape, every preset, crop + adjust + filter. */
    protected function cropperOptions(): array
    {
        return [];
    }

    protected function storageDirectory(): string
    {
        return 'studio';
    }

    protected function uploadEndpoint(): ?string
    {
        return 'https://your-api.example.com/api/photos';
    }

    public function render(): View
    {
        return view('native.image-studio');
    }
}
