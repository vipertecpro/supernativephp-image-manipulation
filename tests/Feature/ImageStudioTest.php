<?php

use App\NativeComponents\ImageStudio;
use Illuminate\Support\Facades\File;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Events\Gallery\MediaSelected;
use Native\Mobile\Testing\Native;
use Nativephp\ImageCropper\Events\ImageCropped;

/**
 * Write a throwaway JPEG whose SOF0 marker reports the given dimensions. Built
 * from raw bytes (no GD) so the suite runs even where GD is absent — like the
 * on-device runtime.
 */
function fakePickedImage(int $width = 600, int $height = 400): string
{
    $path = storage_path('app/studio-tests/'.uniqid('picked_').'.jpg');
    File::ensureDirectoryExists(dirname($path));

    $sof0 = "\xFF\xC0\x00\x11\x08".
        pack('n', $height).pack('n', $width).
        "\x03\x01\x22\x00\x02\x11\x01\x03\x11\x01";

    file_put_contents(
        $path,
        "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00\x01\x01\x00\x00\x01\x00\x01\x00\x00".$sof0."\xFF\xD9"
    );

    return $path;
}

afterEach(function () {
    File::deleteDirectory(storage_path('app/studio-tests'));
});

it('shows an empty preview with Edit + Update', function () {
    Native::test(ImageStudio::class)
        ->assertSet('sourcePath', null)
        ->assertSee('Edit')
        ->assertSee('Update')
        ->assertSee('Add a photo to get started');
});

it('opens the gallery from Update', function () {
    Native::test(ImageStudio::class)
        ->call('update')
        ->assertNativeCalled('Camera.PickMedia');
});

it('opens the camera from updateFromCamera', function () {
    Native::test(ImageStudio::class)
        ->call('updateFromCamera')
        ->assertNativeCalled('Camera.GetPhoto');
});

it('sets the source when a photo is selected (and opens the editor)', function () {
    $source = fakePickedImage();

    Native::test(ImageStudio::class)
        ->emitNative(MediaSelected::class, ['success' => true, 'files' => [$source], 'count' => 1])
        ->assertSet('sourcePath', $source)
        ->assertSee('Tap Edit to crop');
});

it('sets the source from a captured photo', function () {
    $source = fakePickedImage();

    Native::test(ImageStudio::class)
        ->emitNative(PhotoTaken::class, ['path' => $source])
        ->assertSet('sourcePath', $source);
});

it('ignores a cancelled / empty selection', function () {
    Native::test(ImageStudio::class)
        ->emitNative(MediaSelected::class, ['success' => false, 'files' => [], 'count' => 0])
        ->assertSet('sourcePath', null);
});

it('Edit opens the crop editor when there is a photo', function () {
    $source = fakePickedImage();

    Native::test(ImageStudio::class)
        ->emitNative(MediaSelected::class, ['success' => true, 'files' => [$source], 'count' => 1])
        ->call('startEdit')          // no-op bridge off-device, must not error
        ->assertSet('sourcePath', $source);
});

it('Edit picks a photo first when there is none', function () {
    Native::test(ImageStudio::class)
        ->call('startEdit')
        ->assertNativeCalled('Camera.PickMedia');
});

it('adopts the cropped file returned by the native cropper', function () {
    $source = fakePickedImage();
    $cropped = fakePickedImage(400, 400);

    Native::test(ImageStudio::class)
        ->emitNative(MediaSelected::class, ['success' => true, 'files' => [$source], 'count' => 1])
        ->emitNative(ImageCropped::class, ['path' => $cropped])
        ->assertSet('sourcePath', $cropped);
});

it('resets back to the empty preview', function () {
    $source = fakePickedImage();

    Native::test(ImageStudio::class)
        ->emitNative(MediaSelected::class, ['success' => true, 'files' => [$source], 'count' => 1])
        ->call('reset')
        ->assertSet('sourcePath', null)
        ->assertSee('Add a photo to get started');
});
