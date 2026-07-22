<?php

use App\NativeComponents\Home;
use Native\Mobile\Testing\Native;

it('renders the example gallery', function () {
    $logoPath = public_path('images/nativephp-logo.png');

    expect($logoPath)->toBeFile();

    Native::visit('/')
        ->assertSee('Image Cropper')
        ->assertSee('Image Studio')
        ->assertSee('Profile Photo')
        ->assertSee('Cover Photo')
        ->assertSee('Adjust')
        ->assertSee('Filter')
        ->assertMissingElement('top_bar')
        ->assertElement('image', fn (array $node): bool => ($node['props']['src'] ?? null) === $logoPath
            && ($node['props']['alt'] ?? null) === 'NativePHP');
});

it('navigates to each example screen', function () {
    Native::test(Home::class)->call('openImageStudio')->assertNavigatedTo('/image-studio');
    Native::test(Home::class)->call('openProfilePhoto')->assertNavigatedTo('/profile-photo');
    Native::test(Home::class)->call('openCoverPhoto')->assertNavigatedTo('/cover-photo');
    Native::test(Home::class)->call('openAdjustPhoto')->assertNavigatedTo('/adjust-photo');
    Native::test(Home::class)->call('openFilterPhoto')->assertNavigatedTo('/filter-photo');
});

it('opens an example by tapping its card', function () {
    Native::visit('/')
        ->tap('Profile Photo')
        ->assertNavigatedTo('/profile-photo');
});

it('is accessible', function () {
    Native::visit('/')->assertAccessible();
});
