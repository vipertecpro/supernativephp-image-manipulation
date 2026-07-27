<?php

namespace App\NativeComponents;

use App\Concerns\InteractsWithImageCropper;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

/**
 * Story example — a vertical 9:16 status/story, WhatsApp/Instagram style.
 *
 * Stories are always full-screen portrait, so the ratio is locked to 9:16
 * and the user just frames the shot and (optionally) tweaks the colour.
 */
class StoryPhoto extends NativeComponent
{
    use InteractsWithImageCropper;

    protected function cropperOptions(): array
    {
        return [
            'preset' => 'story',        // 9:16 vertical, like a story/status
            'presets' => [],            // locked — stories are always this shape
            'modes' => ['crop', 'adjust'],
            'tools' => ['zoom', 'rotate'],
            'outputSize' => 1080,       // 1080×1920 story export
        ];
    }

    protected function storageDirectory(): string
    {
        return 'stories';
    }

    protected function uploadEndpoint(): ?string
    {
        return 'https://your-api.example.com/api/stories';
    }

    public function render(): View
    {
        return view('native.story-photo');
    }
}
