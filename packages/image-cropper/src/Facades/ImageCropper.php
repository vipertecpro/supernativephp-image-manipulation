<?php

namespace Nativephp\ImageCropper\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void open(string $path, array $options = [])
 *
 * @see \Nativephp\ImageCropper\ImageCropper
 */
class ImageCropper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Nativephp\ImageCropper\ImageCropper::class;
    }
}
