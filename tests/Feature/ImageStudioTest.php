<?php

use App\NativeComponents\AdjustPhoto;
use App\NativeComponents\CoverPhoto;
use App\NativeComponents\FilterPhoto;
use App\NativeComponents\ImageStudio;
use App\NativeComponents\ProfilePhoto;
use Illuminate\Support\Facades\File;
use Native\Mobile\Events\Gallery\MediaSelected;
use Native\Mobile\Testing\Native;
use Vipertecpro\ImageCropper\Events\ImageCropped;

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

/** Read a component's protected crop config for assertions. */
function cropperOptionsOf(object $component): array
{
    $method = new ReflectionMethod($component, 'cropperOptions');
    $method->setAccessible(true);

    return $method->invoke($component);
}

/** Invoke any protected no-arg method (storageDirectory / uploadEndpoint). */
function callProtected(object $component, string $method): mixed
{
    $reflection = new ReflectionMethod($component, $method);
    $reflection->setAccessible(true);

    return $reflection->invoke($component);
}

afterEach(function () {
    File::deleteDirectory(storage_path('app/studio-tests'));
    foreach (['cropped', 'studio', 'avatars', 'covers', 'adjusted', 'filtered'] as $dir) {
        File::deleteDirectory(storage_path('app/'.$dir));
    }
});

// The five example screens all share InteractsWithImageCropper, so the flow is
// identical — only their config, empty-state copy, and storage folder differ.
dataset('cropperScreens', [
    'ImageStudio' => [ImageStudio::class, 'Add a photo to get started', 'studio'],
    'ProfilePhoto' => [ProfilePhoto::class, 'Add a profile photo', 'avatars'],
    'CoverPhoto' => [CoverPhoto::class, 'Add a cover photo', 'covers'],
    'AdjustPhoto' => [AdjustPhoto::class, 'Add a photo to adjust', 'adjusted'],
    'FilterPhoto' => [FilterPhoto::class, 'Add a photo to filter', 'filtered'],
]);

it('shows an empty preview with Edit + Update', function (string $component, string $emptyText) {
    Native::test($component)
        ->assertSet('sourcePath', null)
        ->assertSee('Edit')
        ->assertSee('Update')
        ->assertSee($emptyText);
})->with('cropperScreens');

it('opens the gallery from Update', function (string $component) {
    Native::test($component)
        ->call('update')
        ->assertNativeCalled('Camera.PickMedia');
})->with('cropperScreens');

it('goes straight to the editor when a photo is selected (no preview flash)', function (string $component) {
    $source = fakePickedImage();

    // Picking opens the editor with the raw image but does NOT set it as the
    // preview — the preview only updates once the crop result comes back.
    Native::test($component)
        ->emitNative(MediaSelected::class, ['success' => true, 'files' => [$source], 'count' => 1])
        ->assertSet('sourcePath', null);
})->with('cropperScreens');

it('ignores a cancelled / empty selection', function (string $component) {
    Native::test($component)
        ->emitNative(MediaSelected::class, ['success' => false, 'files' => [], 'count' => 0])
        ->assertSet('sourcePath', null);
})->with('cropperScreens');

it('Edit picks a photo first when there is none', function (string $component) {
    Native::test($component)
        ->call('startEdit')
        ->assertNativeCalled('Camera.PickMedia');
})->with('cropperScreens');

it('persists the cropped file into the screen\'s own folder', function (string $component, string $emptyText, string $dir) {
    $source = fakePickedImage();
    $cropped = fakePickedImage(400, 400);
    $stored = storage_path('app/'.$dir.'/'.basename($cropped));

    Native::test($component)
        ->emitNative(MediaSelected::class, ['success' => true, 'files' => [$source], 'count' => 1])
        ->emitNative(ImageCropped::class, ['path' => $cropped])
        ->assertSet('sourcePath', $stored);

    expect(is_file($stored))->toBeTrue();
})->with('cropperScreens');

it('locks down the profile + cover configs but leaves the studio open', function () {
    $profile = cropperOptionsOf(new ProfilePhoto);
    expect($profile['preset'])->toBe('profile')
        ->and($profile['presets'])->toBe([])
        ->and($profile['modes'])->toBe(['crop']);

    $cover = cropperOptionsOf(new CoverPhoto);
    expect($cover['preset'])->toBe('cover')->and($cover['modes'])->toBe(['crop']);

    expect(cropperOptionsOf(new ImageStudio))->toBe([]);

    // Adjust / filter examples turn crop off entirely.
    expect(cropperOptionsOf(new AdjustPhoto)['modes'])->toBe(['adjust']);
    expect(cropperOptionsOf(new FilterPhoto)['modes'])->toBe(['filter']);
});

it('routes each screen to its own device folder and backend endpoint', function () {
    expect(callProtected(new ProfilePhoto, 'storageDirectory'))->toBe('avatars')
        ->and(callProtected(new ProfilePhoto, 'uploadEndpoint'))->toContain('/api/user/avatar');

    expect(callProtected(new CoverPhoto, 'storageDirectory'))->toBe('covers')
        ->and(callProtected(new CoverPhoto, 'uploadEndpoint'))->toContain('/api/user/cover');

    expect(callProtected(new AdjustPhoto, 'storageDirectory'))->toBe('adjusted');
    expect(callProtected(new FilterPhoto, 'storageDirectory'))->toBe('filtered');
});
