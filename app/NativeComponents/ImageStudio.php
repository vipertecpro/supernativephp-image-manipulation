<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\Attributes\On;
use Native\Mobile\Edge\NativeComponent;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Events\Gallery\MediaSelected;
use Native\Mobile\Facades\Camera;
use Native\Mobile\Facades\Dialog;
use Nativephp\ImageCropper\Events\CropCancelled;
use Nativephp\ImageCropper\Events\ImageCropped;
use Nativephp\ImageCropper\Facades\ImageCropper;

/**
 * Image Studio — a single preview screen backed by the native crop editor.
 *
 *   • The screen shows a big circular preview: a placeholder when empty, or the
 *     saved/cropped image, with Edit + Update.
 *   • Update  → pick / replace the photo. Edit → re-open the editor.
 *   • Selecting a photo (or tapping Edit) goes STRAIGHT into the native
 *     image-cropper plugin — a full-screen editor with live crop presets,
 *     draggable Zoom / Rotate rulers and freehand gestures — which returns a
 *     real cropped file via {@see ImageCropped}. There is no intermediate
 *     editor screen.
 */
class ImageStudio extends NativeComponent
{
    /** Absolute path to the current (picked or cropped) image. */
    public ?string $sourcePath = null;

    // --- Preview actions -----------------------------------------------------

    /** "Update" — pick / replace the photo from the gallery. */
    public function update(): void
    {
        Camera::pickImages('images', false);
    }

    /** "Update" via the camera. */
    public function updateFromCamera(): void
    {
        Camera::getPhoto();
    }

    /** "Edit" — open the native crop editor (picks a photo first if none yet). */
    public function startEdit(): void
    {
        if ($this->hasSource()) {
            $this->openCropEditor();
        } else {
            $this->update();
        }
    }

    // --- Picking → straight into the native editor ---------------------------

    #[On(PhotoTaken::class)]
    public function onPhotoTaken(string $path): void
    {
        $this->loadSource($path);
    }

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
     * Open the native crop editor for the current image. It carries its own live
     * preset selector (Profile/Square/16:9/…), draggable Zoom / Rotate rulers and
     * freehand gestures, and returns a real cropped file via {@see ImageCropped}.
     */
    public function openCropEditor(): void
    {
        if ($this->hasSource()) {
            ImageCropper::open($this->sourcePath); // default shape + all presets, switchable live
        }
    }

    #[On(ImageCropped::class)]
    public function onImageCropped(string $path): void
    {
        // The cropped file becomes the preview.
        $this->sourcePath = $path;
        Dialog::toast('Saved.');
    }

    #[On(CropCancelled::class)]
    public function onCropCancelled(): void
    {
        // User backed out of the native cropper — keep whatever we had.
    }

    /** Discard the current image and return to the empty preview. */
    public function reset(): void
    {
        $this->sourcePath = null;
    }

    // --- Internals -----------------------------------------------------------

    private function loadSource(string $path): void
    {
        $this->sourcePath = $path;

        // Selecting a photo goes straight into the native crop editor.
        $this->openCropEditor();
    }

    private function hasSource(): bool
    {
        return $this->sourcePath !== null && is_file($this->sourcePath);
    }

    public function render(): View
    {
        return view('native.image-studio');
    }
}
