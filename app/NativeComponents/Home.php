<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class Home extends NativeComponent
{
    public function openImageStudio(): void
    {
        $this->navigate('/image-studio');
    }

    public function openProfilePhoto(): void
    {
        $this->navigate('/profile-photo');
    }

    public function openCoverPhoto(): void
    {
        $this->navigate('/cover-photo');
    }

    public function openAdjustPhoto(): void
    {
        $this->navigate('/adjust-photo');
    }

    public function openFilterPhoto(): void
    {
        $this->navigate('/filter-photo');
    }

    public function render(): View
    {
        return view('native.home');
    }
}
