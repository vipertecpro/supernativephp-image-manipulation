<?php

namespace App\Concerns;

use Illuminate\Support\Facades\File;
use Native\Mobile\Attributes\On;
use Native\Mobile\Events\Gallery\MediaSelected;
use Native\Mobile\Facades\Camera;
use Native\Mobile\Facades\Dialog;
use Vipertecpro\ImageCropper\Events\CropCancelled;
use Vipertecpro\ImageCropper\Events\ImageCropped;
use Vipertecpro\ImageCropper\Facades\ImageCropper;

/**
 * Drop-in cropper behaviour for a preview screen.
 *
 * A screen picks a photo, opens the native crop editor with ITS OWN config
 * (via {@see cropperOptions()}), and receives a real cropped file back. All the
 * plumbing — picking, opening, handling the result, persisting — lives here so
 * each example screen is just a preview + a config.
 */
trait InteractsWithImageCropper
{
    /** Absolute path to the current (picked or cropped) image, or null when empty. */
    public ?string $sourcePath = null;

    /**
     * The per-screen crop configuration handed to {@see ImageCropper::open()}.
     * Return `[]` for the full-featured default, or lock it down, e.g.:
     *
     *   return ['preset' => 'profile', 'presets' => [], 'modes' => ['crop']];
     *
     * @return array<string, mixed>
     */
    abstract protected function cropperOptions(): array;

    // --- Preview actions -----------------------------------------------------

    /** Pick / replace the photo from the gallery. */
    public function update(): void
    {
        Camera::pickImages('images', false);
    }

    /** Re-open the editor on the current photo (picks one first if empty). */
    public function startEdit(): void
    {
        $this->hasSource() ? $this->openCropEditor() : $this->update();
    }

    /**
     * A preview box [width, height] in dp that HUGS the current image's aspect
     * ratio, so the preview shows the whole photo with no black letterbox bars.
     * Fits inside maxWidth × maxHeight; falls back to that box when there's no
     * readable image. (`getimagesize()` works on-device — it's ext/standard.)
     *
     * @return array{0: int, 1: int}
     */
    public function previewBox(int $maxWidth = 330, int $maxHeight = 400): array
    {
        $size = $this->sourcePath ? @getimagesize($this->sourcePath) : false;

        if (! $size || $size[0] < 1 || $size[1] < 1) {
            return [$maxWidth, $maxHeight];
        }

        $ratio = $size[0] / $size[1];
        $width = $maxWidth;
        $height = (int) round($width / $ratio);

        if ($height > $maxHeight) {
            $height = $maxHeight;
            $width = (int) round($height * $ratio);
        }

        return [$width, $height];
    }

    // --- Picking → straight into the native editor ---------------------------

    #[On(MediaSelected::class)]
    public function onMediaSelected(bool $success, array $files, int $count): void
    {
        if (! $success || $files === []) {
            return;
        }

        $first = $files[0];
        $path = is_array($first) ? ($first['path'] ?? null) : $first;

        if (is_string($path)) {
            $this->loadSource($path);
        }
    }

    /**
     * The colours handed to the native editor so it renders in THIS app's
     * theme instead of the plugin's default look. All keys are optional —
     * anything omitted falls back to the plugin's system-adaptive default.
     * Override per screen for bespoke palettes.
     *
     * @return array{background?: string, text?: string, accent?: string, highlight?: string}
     */
    protected function cropperTheme(): array
    {
        return [
            'background' => '#121417',                                          // the app's screen background
            'text' => '#FFFFFF',
            'accent' => config('native-ui.theme.light.primary', '#C2410C'),     // brand primary → Done button
            'highlight' => config('native-ui.theme.light.primary', '#C2410C'), // …and active states
        ];
    }

    /**
     * Open the native crop editor with this screen's config. Pass a path to edit
     * a freshly-picked image; omit it to re-edit the current one.
     */
    public function openCropEditor(?string $path = null): void
    {
        $path ??= $this->sourcePath;

        if ($path !== null && is_file($path)) {
            ImageCropper::open($path, ['theme' => $this->cropperTheme()] + $this->cropperOptions());
        }
    }

    #[On(ImageCropped::class)]
    public function onImageCropped(string $path): void
    {
        $this->sourcePath = $this->persistCroppedImage($path);

        Dialog::toast('Saved.');
    }

    #[On(CropCancelled::class)]
    public function onCropCancelled(): void
    {
        // User backed out of the native cropper — keep whatever we had.
    }

    // --- Integration point (override these per screen) -----------------------

    /**
     * The sub-directory (under storage/app) where THIS screen keeps its saved
     * images on the device. Override per screen so avatars, covers, adjusted
     * photos, etc. don't all pile into one folder.
     */
    protected function storageDirectory(): string
    {
        return 'cropped';
    }

    /**
     * The backend endpoint THIS screen uploads its result to, or null to keep
     * it on the device only. Override per screen so each image type hits its
     * own API route (avatar, cover, …). See {@see persistCroppedImage()}.
     */
    protected function uploadEndpoint(): ?string
    {
        return null;
    }

    /**
     * 🔌 INTEGRATION POINT — persist the cropped image.
     *
     * The native cropper writes its result to a fresh file and hands you the
     * path. This runs on the device the instant the user taps Done. By default
     * it keeps a copy in this screen's {@see storageDirectory()} and, when the
     * screen defines an {@see uploadEndpoint()}, is ready to POST it there.
     * Override this whole method if a screen needs something bespoke; whatever
     * you return becomes the path the preview shows.
     */
    protected function persistCroppedImage(string $path): string
    {
        // 1. Keep a permanent copy on the device, in THIS screen's own folder
        //    (the cropper's temp output may be cleaned up by the OS).
        $destination = storage_path('app/'.$this->storageDirectory().'/'.basename($path));
        File::ensureDirectoryExists(dirname($destination));
        File::copy($path, $destination);

        // 2. …and/or push it to THIS screen's own backend endpoint. Runs on the
        //    device, so use your real HTTPS route + whatever auth your API needs.
        //    Uncomment to enable — each screen already declares its own URL.
        //
        // if ($endpoint = $this->uploadEndpoint()) {
        //     try {
        //         $response = \Illuminate\Support\Facades\Http::withToken(config('services.backend.token'))
        //             ->attach('image', file_get_contents($destination), basename($destination))
        //             ->post($endpoint);
        //
        //         // If your API returns the stored URL, show THAT instead:
        //         // if ($response->successful()) { return $response->json('url'); }
        //     } catch (\Throwable $e) {
        //         report($e); // log & carry on — the local copy is still valid
        //     }
        // }

        return $destination;
    }

    // --- Internals -----------------------------------------------------------

    private function loadSource(string $path): void
    {
        // Jump STRAIGHT into the editor with the freshly-picked image. We do NOT
        // set it as the preview here — the preview only updates once the crop
        // returns (see onImageCropped) — so there's no raw-image flash before the
        // editor appears; tapping Edit goes directly to the editor.
        $this->openCropEditor($path);
    }

    private function hasSource(): bool
    {
        return $this->sourcePath !== null && is_file($this->sourcePath);
    }
}
