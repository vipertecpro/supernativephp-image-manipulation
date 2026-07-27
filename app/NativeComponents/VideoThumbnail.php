<?php

namespace App\NativeComponents;

use App\Concerns\InteractsWithImageCropper;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

/**
 * Video thumbnail example — a YouTube-style 16:9 thumbnail editor.
 *
 * Thumbnails are locked 16:9 but live or die on punchy colour, so this
 * config enables ALL the editing modes (crop, adjust, filter) inside the
 * fixed frame and exports at the platform-standard 1280×720.
 */
class VideoThumbnail extends NativeComponent
{
    use InteractsWithImageCropper;

    protected function cropperOptions(): array
    {
        return [
            'preset' => 'landscape',    // 16:9, like a video thumbnail
            'presets' => [],            // locked — thumbnails are always 16:9
            'modes' => ['crop', 'adjust', 'filter'],
            'tools' => ['zoom', 'rotate'],
            'outputSize' => 1280,       // 1280×720 thumbnail export
        ];
    }

    protected function storageDirectory(): string
    {
        return 'thumbnails';
    }

    protected function uploadEndpoint(): ?string
    {
        return 'https://your-api.example.com/api/videos/thumbnail';
    }

    public function render(): View
    {
        return view('native.video-thumbnail');
    }
}
