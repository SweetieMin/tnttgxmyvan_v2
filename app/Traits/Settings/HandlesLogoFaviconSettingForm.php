<?php

namespace App\Traits\Settings;

use Flux\Flux;
use Livewire\Attributes\Validate;

trait HandlesLogoFaviconSettingForm
{
   
    #[Validate('nullable|image|max:10240')] // 10MB Max
    public $logo;

    public $existLogo;

    #[Validate('nullable|image|max:10240')] // 10MB Max
    public $favicon;

    public $existFavicon;

    public function removeLogo()
    {
        $this->logo->delete();
        $this->logo = null;
    }

    public function updatedLogo($file)
    {
        [$width, $height] = getimagesize($file->getRealPath());

        if ($width == $height) {
            dd("vuông");
        } elseif ($width > $height) {
            dd("ngang");
        } else {
            dd("doc");
        }
    }

    public function saveLogo()
    {
        Flux::toast(
            heading: 'Cảnh báo',
            text: 'Tính năng đang được phát triển.',
            variant: 'warning',
        );
        $this->redirectRoute('admin.settings.general', ['tab' => 'logo-favicon'], navigate: true);
    }

    public function saveFavicon()
    {
        Flux::toast(
            heading: 'Cảnh báo',
            text: 'Tính năng đang được phát triển.',
            variant: 'warning',
        );
        $this->redirectRoute('admin.settings.general', ['tab' => 'logo-favicon'], navigate: true);
    }

    
}
