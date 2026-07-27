# Image Cropper — NativePHP Mobile Demo

A [NativePHP Mobile](https://nativephp.com/docs/mobile) demo app for the
[`vipertecpro/image-cropper`](https://github.com/vipertecpro/image-cropper)
plugin — a fully native, hand-written image cropper & editor for iOS and Android.

The home screen is a gallery of **nine examples**, each opening the *same* plugin
configured differently — from a full-featured studio down to a bare, locked
cropper — modeled on the image flows real apps use (profile avatars, cover
banners, feed posts, stories, video thumbnails).

## Demo

[![Watch the demo](art/demo-poster.png)](art/demo.mp4)

▶️ **[Watch the full demo](art/demo.mp4)** — a walkthrough of the five examples on iOS.

## The examples

| Screen | Configuration | Real-world use case |
|---|---|---|
| **Image Studio** | everything on (all presets, crop + adjust + filter) | a full editor |
| **Profile Photo** | `preset: profile`, `presets: []`, `modes: ['crop']` | locked circular avatar (LinkedIn/Facebook-style) |
| **Cover Photo** | `preset: cover`, `presets: []`, `modes: ['crop']` | locked wide banner (profile cover) |
| **Social Post** | `presets: [square, portrait, landscape]`, `modes: ['crop','filter']` | feed post with selectable ratios + filters (Instagram-style) |
| **Story** | `preset: story`, `presets: []`, `modes: ['crop','adjust']` | locked 9:16 vertical story/status |
| **Video Thumbnail** | `preset: landscape`, `presets: []`, all modes, `outputSize: 1280` | 16:9 video thumbnail (YouTube-style) |
| **Remote Image** | an `https://` URL as the source | re-crop an image already hosted on a CDN/API — the plugin downloads it natively |
| **Adjust** | `modes: ['adjust']` | colour-adjust the whole photo, no crop |
| **Filter** | `modes: ['filter']` | one-tap filters on the whole photo, no crop |

Each screen is a thin `NativeComponent` under
[`app/NativeComponents`](app/NativeComponents) that shares one trait,
[`App\Concerns\InteractsWithImageCropper`](app/Concerns/InteractsWithImageCropper.php),
and only declares its own crop config, storage folder, and (optional) upload
endpoint:

```php
protected function cropperOptions(): array   { return ['preset' => 'profile', 'presets' => [], 'modes' => ['crop']]; }
protected function storageDirectory(): string { return 'avatars'; }
protected function uploadEndpoint(): ?string  { return 'https://your-api.example.com/api/user/avatar'; }
```

## How the flow works

1. Tap **Edit** → pick a photo → the native crop editor opens **immediately**.
2. Adjust / crop / filter, then tap **Done**.
3. The plugin returns a real cropped file; the trait's `persistCroppedImage()`
   saves it into that screen's folder (and is ready to POST it to that screen's
   endpoint), then the preview updates.

The storage/backend hook is the single integration point — see
[`persistCroppedImage()`](app/Concerns/InteractsWithImageCropper.php). It keeps a
copy on device by default, with a fully-commented `Http` upload ready to enable.

## Running it

**Requirements:** PHP 8.4 and [NativePHP Mobile](https://nativephp.com/docs/mobile/4/getting-started/installation)
set up (Xcode for iOS, Android Studio for Android).

```bash
git clone https://github.com/vipertecpro/supernativephp-image-manipulation
cd supernativephp-image-manipulation
composer install

php artisan native:run ios       # or: android
```

The `vipertecpro/image-cropper` plugin installs via Composer and is registered in
[`app/Providers/NativeServiceProvider.php`](app/Providers/NativeServiceProvider.php).

## Tests

```bash
php artisan test
```

## Stability & upgrading

This app tracks **SuperNative** (NativePHP Mobile v4) and stays on tagged,
mutually-compatible releases so a fresh clone always builds and boots:

- `nativephp/mobile` is constrained to `~4.0.0@RC` (currently `4.0.0-rc.1`).
- `nativephp/native-ui` is **pinned to `0.1.0`** — the newest version that
  pairs with rc.1. Newer releases (`0.2.0+`, renamed to `nativephp/mobile-ui`,
  namespace `Native\Mobile\UI`, iOS min 18.2) require an unreleased runtime
  and will not compile against rc.1. The pin is served through an inline
  package repository in `composer.json`, so `composer install` works for
  everyone with no extra setup.

**When the next NativePHP Mobile release ships**, upgrading is three steps:

1. `composer require nativephp/mobile:<new version>` and switch
   `nativephp/native-ui` to the paired `nativephp/mobile-ui` release (drop the
   inline repository entry from `composer.json`).
2. Update the provider import in
   [`app/Providers/NativeServiceProvider.php`](app/Providers/NativeServiceProvider.php)
   to `Native\Mobile\UI\NativeUIServiceProvider`, and rename any `@press`
   bindings to `@tap` if that release adopts the new event name.
3. Delete `nativephp/android/app/src/main/java/com/nativephp/plugins/` (stale
   copies of old plugin sources) and run `php artisan native:install --force`,
   then `php artisan native:run`.

`php artisan test` plus the CI workflow should stay green at every step.

## Contributing

This app exists to demonstrate the plugin, so the most useful contributions are
**new example screens** or clearer existing ones. To add one, create a
`NativeComponent` in [`app/NativeComponents`](app/NativeComponents) that uses the
`InteractsWithImageCropper` trait, add it to the home gallery, and open a pull
request. Bug reports and doc fixes are welcome too.

Found something in the cropper itself? Please report it on the
[plugin repo](https://github.com/vipertecpro/image-cropper/issues).

## License

MIT — see [LICENSE.md](LICENSE.md).
