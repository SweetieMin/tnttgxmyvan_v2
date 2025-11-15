<?php

namespace App\Livewire\Settings\Site;

use Flux\Flux;

use Livewire\Component;
use App\Models\GeneralSetting;
use Livewire\Attributes\Title;
use App\Validation\Setting\GeneralRules;
use App\Traits\Settings\HandlesGeneralSettingForm;

#[Title('Cấu hình chung')]

class GeneralSettings extends Component
{

    //use HandlesGeneralSettingForm;

    public function render()
    {
        return view('livewire.settings.site.general-settings');
    }

    public $site_title = '';
    public $site_email = '';
    public $site_phone = '';
    public $site_meta_keywords = '';
    public $site_meta_description = '';
    public $facebook_url = '';
    public $instagram_url = '';
    public $youtube_url = '';
    public $tikTok_url = '';

    public $isValidFacebookUrl = false;
    public $isValidInstagramUrl = false;
    public $isValidTiktokUrl = false;
    public $isValidYoutubeUrl = false;

    public $canSaveData = true;

    public function mount()
    {

        $generalSettings = GeneralSetting::first();

        if (!$generalSettings) {
            return;
        }

        $this->site_title = $generalSettings?->site_title;
        $this->site_email = $generalSettings?->site_email;
        $this->site_phone = $generalSettings?->site_phone;
        $this->site_meta_keywords = $generalSettings?->site_meta_keywords;
        $this->site_meta_description = $generalSettings?->site_meta_description;
        $this->facebook_url = $generalSettings?->facebook_url;
        $this->instagram_url = $generalSettings?->instagram_url;
        $this->youtube_url = $generalSettings?->youtube_url;
        $this->tikTok_url = $generalSettings?->tikTok_url;
    }

    public function rules()
    {
        return GeneralRules::rules();
    }

    public function messages()
    {
        return GeneralRules::messages();
    }

    public function updateGeneralSettings()
    {
        // Lưu lại các custom errors trước khi validate
        $customErrors = [];
        if ($this->getErrorBag()->has('facebook_url')) {
            $customErrors['facebook_url'] = $this->getErrorBag()->first('facebook_url');
        }
        if ($this->getErrorBag()->has('instagram_url')) {
            $customErrors['instagram_url'] = $this->getErrorBag()->first('instagram_url');
        }
        if ($this->getErrorBag()->has('tiktok_url')) {
            $customErrors['tiktok_url'] = $this->getErrorBag()->first('tiktok_url');
        }
        if ($this->getErrorBag()->has('youtube_url')) {
            $customErrors['youtube_url'] = $this->getErrorBag()->first('youtube_url');
        }

        $this->validate();

        // Khôi phục lại custom errors sau khi validate
        foreach ($customErrors as $field => $message) {
            $this->addError($field, $message);
        }

        if (!$this->canSaveData) {
            Flux::toast(
                heading: 'Thất bại',
                text: 'Vui lòng kiểm tra lại các đường dẫn URL.',
                variant: 'danger',
            );
            return;
        }

        $generalSettings = GeneralSetting::first();

        if (!$generalSettings) {
            $generalSettings = new GeneralSetting();
        }

        $generalSettings->site_title = $this->site_title;
        $generalSettings->site_email = $this->site_email;
        $generalSettings->site_phone = $this->site_phone;
        $generalSettings->site_meta_keywords = $this->site_meta_keywords;
        $generalSettings->site_meta_description = $this->site_meta_description;
        $generalSettings->facebook_url = $this->facebook_url;
        $generalSettings->instagram_url = $this->instagram_url;
        $generalSettings->youtube_url = $this->youtube_url;
        $generalSettings->tikTok_url = $this->tikTok_url;
        $generalSettings->save();

        Flux::toast(
            heading: 'Thành công',
            text: 'Cấu hình chung cập nhật thành công.',
            variant: 'success',
        );

        $this->redirectRoute('admin.settings.general', navigate: true);
    }
}
