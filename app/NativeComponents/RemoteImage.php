<?php

namespace App\NativeComponents;

use App\Concerns\InteractsWithImageCropper;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;
use Vipertecpro\ImageCropper\Facades\ImageCropper;

/**
 * Remote image example — crop an image straight from a URL.
 *
 * Real apps often need to re-crop an image that already lives on a CDN or
 * API (the user's current avatar, a product photo, …). Hand the cropper the
 * http(s) URL and the plugin downloads it natively — showing a themed
 * loading screen with Cancel — then opens the same editor as for local
 * files. No picker, no manual download code.
 */
class RemoteImage extends NativeComponent
{
    use InteractsWithImageCropper;

    /** The remote source being edited (seeded → deterministic sample photo). */
    public string $imageUrl = 'https://picsum.photos/seed/nativephp/1600/1200';

    /** Edit the CURRENT remote image — no picker involved. */
    public function startEdit(): void
    {
        ImageCropper::open($this->imageUrl, ['theme' => $this->cropperTheme()] + $this->cropperOptions());
    }

    /** Point at a different random remote photo and edit that instead. */
    public function update(): void
    {
        $this->imageUrl = 'https://picsum.photos/seed/'.Str::random(8).'/1600/1200';
        $this->startEdit();
    }

    protected function cropperOptions(): array
    {
        return [
            'preset' => 'landscape',    // opens on 16:9; every preset stays available
            'outputSize' => 1280,
        ];
    }

    protected function storageDirectory(): string
    {
        return 'remote';
    }

    protected function uploadEndpoint(): ?string
    {
        return 'https://your-api.example.com/api/images/recrop';
    }

    public function render(): View
    {
        return view('native.remote-image');
    }
}
