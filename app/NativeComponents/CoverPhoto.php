<?php

namespace App\NativeComponents;

use App\Concerns\InteractsWithImageCropper;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

/**
 * Cover / banner example — a locked-down wide crop.
 *
 * A fixed wide "cover" aspect ratio (~2.7:1, timeline/banner style) with no
 * preset switching and no colour editing — just reframe (zoom + rotate) and
 * Done. Output is 1600px wide.
 */
class CoverPhoto extends NativeComponent
{
    use InteractsWithImageCropper;

    protected function cropperOptions(): array
    {
        return [
            'preset' => 'cover',        // wide banner, locked ~2.7:1
            'presets' => [],            // hide the preset selector — the ratio is fixed
            'modes' => ['crop'],        // no adjust / filter — cropping only
            'tools' => ['zoom', 'rotate'],
            'outputSize' => 1600,
        ];
    }

    protected function storageDirectory(): string
    {
        return 'covers';
    }

    protected function uploadEndpoint(): ?string
    {
        return 'https://your-api.example.com/api/user/cover';
    }

    public function render(): View
    {
        return view('native.cover-photo');
    }
}
