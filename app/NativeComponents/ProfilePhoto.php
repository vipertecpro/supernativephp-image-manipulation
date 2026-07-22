<?php

namespace App\NativeComponents;

use App\Concerns\InteractsWithImageCropper;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

/**
 * Profile photo example — a locked-down, "super simple" cropper.
 *
 * A circular 1:1 avatar crop with NO preset switching and NO colour editing —
 * just position the photo (zoom + rotate) and Done. The output is a transparent
 * circular PNG at 512px.
 */
class ProfilePhoto extends NativeComponent
{
    use InteractsWithImageCropper;

    protected function cropperOptions(): array
    {
        return [
            'preset' => 'profile',      // round avatar, locked 1:1
            'presets' => [],            // hide the preset selector — the shape is fixed
            'modes' => ['crop'],        // no adjust / filter — cropping only
            'tools' => ['zoom', 'rotate'],
            'outputSize' => 512,
        ];
    }

    protected function storageDirectory(): string
    {
        return 'avatars';
    }

    protected function uploadEndpoint(): ?string
    {
        return 'https://your-api.example.com/api/user/avatar';
    }

    public function render(): View
    {
        return view('native.profile-photo');
    }
}
