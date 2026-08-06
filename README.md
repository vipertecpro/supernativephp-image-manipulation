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

▶️ **[Watch the full demo](art/demo.mp4)** — a walkthrough of the examples on iOS.

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

**Requirements:** PHP 8.4, [NativePHP Mobile](https://nativephp.com/docs/mobile/4/getting-started/installation)
set up (Xcode for iOS, Android Studio for Android), and — because of
`nativephp/mobile-ui` — **iOS 18.2+** / **Android API 26+** on the simulator or
device you target.

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

This app tracks **SuperNative** (NativePHP Mobile v4) on stable, published
releases. Every dependency resolves from Packagist or the `nativephp-plugins`
composer repo — **no inline package pins** — so a fresh clone just works:

- `nativephp/mobile` — `^4.0` (currently `4.0.1`)
- `nativephp/mobile-ui` — `^0.3` (theme + native UI components, namespace
  `Native\Mobile\UI`). **Requires iOS 18.2+.**
- `nativephp/mobile-camera` — `^1.0`
- `vipertecpro/image-cropper` — `^1.0`

Two GitHub Actions keep it healthy: `update-dependencies.yml` runs
`composer update` daily and commits any lockfile changes, and `tests.yml` runs
the suite on PHP 8.4 for every push.

After changing native dependencies, rebuild the native shell:

```bash
rm -rf nativephp
php artisan native:install --force
php artisan native:run ios      # or: android
```

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
