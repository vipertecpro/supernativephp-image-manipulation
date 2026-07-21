## nativephp/plugin-image-cropper

A NativePHP Mobile plugin

### Installation

```bash
composer require nativephp/plugin-image-cropper
```

### PHP Usage (Livewire/Blade)

Use the `ImageCropper` facade:

@verbatim
<code-snippet name="Using ImageCropper Facade" lang="php">
use Nativephp\ImageCropper\Facades\ImageCropper;

// Execute the plugin functionality
$result = ImageCropper::execute(['option1' => 'value']);

// Get the current status
$status = ImageCropper::getStatus();
</code-snippet>
@endverbatim

### Available Methods

- `ImageCropper::execute()`: Execute the plugin functionality
- `ImageCropper::getStatus()`: Get the current status

### Events

- `ImageCropperCompleted`: Listen with `#[OnNative(ImageCropperCompleted::class)]`

@verbatim
<code-snippet name="Listening for ImageCropper Events" lang="php">
use Native\Mobile\Attributes\OnNative;
use Nativephp\ImageCropper\Events\ImageCropperCompleted;

#[OnNative(ImageCropperCompleted::class)]
public function handleImageCropperCompleted($result, $id = null)
{
    // Handle the event
}
</code-snippet>
@endverbatim

### JavaScript Usage (Vue/React/Inertia)

@verbatim
<code-snippet name="Using ImageCropper in JavaScript" lang="javascript">
import { imageCropper } from '@nativephp/plugin-image-cropper';

// Execute the plugin functionality
const result = await imageCropper.execute({ option1: 'value' });

// Get the current status
const status = await imageCropper.getStatus();
</code-snippet>
@endverbatim