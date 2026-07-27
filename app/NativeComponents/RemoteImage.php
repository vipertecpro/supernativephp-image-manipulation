<?php

namespace App\NativeComponents;

use App\Concerns\InteractsWithImageCropper;
use Illuminate\Support\Str;
use Illuminate\View\View;
use InvalidArgumentException;
use Native\Mobile\Edge\NativeComponent;
use Native\Mobile\Facades\Dialog;
use Vipertecpro\ImageCropper\Facades\ImageCropper;

/**
 * Remote image example — type/paste any image URL and crop it.
 *
 * Real apps often need to re-crop an image that already lives on a CDN or
 * API (the user's current avatar, a product photo, …). Enter the http(s)
 * URL and the plugin downloads it natively — showing a themed loading
 * screen with Cancel — then opens the same editor as for local files.
 * No picker, no manual download code.
 *
 * The plugin only accepts croppable formats: a URL ending in .pdf/.mp4/…
 * throws immediately (surfaced here as a toast), and downloaded content
 * that doesn't decode as an image fires CropCancelled.
 */
class RemoteImage extends NativeComponent
{
    use InteractsWithImageCropper;

    /** The URL typed by the user (bound live to the input via native:model). */
    public string $imageUrl = '';

    /** Crop whatever URL is in the input — no picker involved. */
    public function startEdit(): void
    {
        $url = trim($this->imageUrl);

        if ($url === '') {
            Dialog::toast('Enter an image URL first — or tap Sample.');

            return;
        }

        try {
            ImageCropper::open($url, ['theme' => $this->cropperTheme()] + $this->cropperOptions());
        } catch (InvalidArgumentException $e) {
            // Non-croppable extension or unsupported scheme — the plugin
            // rejects it before the native side is ever involved.
            Dialog::toast($e->getMessage());
        }
    }

    /** Fill the input with a random sample photo URL (does not auto-open). */
    public function fillSample(): void
    {
        $this->imageUrl = 'https://picsum.photos/seed/'.Str::random(8).'/1600/1200';
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
