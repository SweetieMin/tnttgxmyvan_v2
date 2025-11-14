<?php

namespace App\Traits\Settings;

use App\Services\GeneralSettingService;

trait HandlesGeneralSettingForm
{
    protected function resetForm()
    {
        $this->resetErrorBag();
    }
    
    public function updatedFacebookUrl($value, GeneralSettingService $generalSettingService)
    {
        if (!empty($value)) {
            $isValid = $generalSettingService->checkValidUrl($value, 'facebook');
            // Xử lý kết quả validation ở đây
            if (!$isValid) {
                $this->isValidFacebookUrl = false;
                $this->checkUrlBeforeSave();
                $this->addError('facebook_url', 'URL Facebook không hợp lệ hoặc không thể truy cập.(https://www.facebook.com/.....)');
                return;
            }
            // URL hợp lệ
            $this->isValidFacebookUrl = true;
            $this->resetErrorBag('facebook_url');
            $this->checkUrlBeforeSave();
        }else{
            $this->isValidFacebookUrl = true;
            $this->resetErrorBag('facebook_url');
            $this->checkUrlBeforeSave();
        }
    }

    public function updatedInstagramUrl($value, GeneralSettingService $generalSettingService)
    {
        if (!empty($value)) {
            $isValid = $generalSettingService->checkValidUrl($value, 'instagram');
            // Xử lý kết quả validation ở đây
            if (!$isValid) {
                $this->isValidInstagramUrl = false;
                $this->checkUrlBeforeSave();
                $this->addError('instagram_url', 'URL Instagram không hợp lệ hoặc không thể truy cập.(https://www.instagram.com/.....)');
                return;
            }
            // URL hợp lệ
            $this->isValidInstagramUrl = true;
            $this->resetErrorBag('instagram_url');
            $this->checkUrlBeforeSave();
        }else{
            $this->isValidInstagramUrl = true;
            $this->resetErrorBag('instagram_url');
            $this->checkUrlBeforeSave();
        }
    }

    public function updatedTiktokUrl($value, GeneralSettingService $generalSettingService)
    {
        if (!empty($value)) {
            $isValid = $generalSettingService->checkValidUrl($value, 'tiktok');
            // Xử lý kết quả validation ở đây
            if (!$isValid) {
                $this->isValidTiktokUrl = false;
                $this->checkUrlBeforeSave();
                $this->addError('tiktok_url', 'URL tiktok không hợp lệ hoặc không thể truy cập.(https://www.tiktok.com/.....)');
                return;
            }
            // URL hợp lệ
            $this->isValidTiktokUrl = true;
            $this->resetErrorBag('tiktok_url');
            $this->checkUrlBeforeSave();
        }else{
            $this->isValidTiktokUrl = true;
            $this->resetErrorBag('tiktok_url');
            $this->checkUrlBeforeSave();
        }
    }

    public function updatedYoutubeUrl($value, GeneralSettingService $generalSettingService)
    {
        if (!empty($value)) {
            $isValid = $generalSettingService->checkValidUrl($value, 'youtube');
            // Xử lý kết quả validation ở đây
            if (!$isValid) {
                $this->isValidYoutubeUrl = false;
                $this->checkUrlBeforeSave();
                $this->addError('youtube_url', 'URL youtube không hợp lệ hoặc không thể truy cập.(https://www.youtube.com/.....)');
                return;
            }
            // URL hợp lệ
            $this->isValidYoutubeUrl = true;
            $this->resetErrorBag('youtube_url');
            $this->checkUrlBeforeSave();
        }else{
            $this->isValidYoutubeUrl = true;
            $this->resetErrorBag('youtube_url');
            $this->checkUrlBeforeSave();
        }
    }

    public function checkUrlBeforeSave()
    {
        if($this->isValidFacebookUrl && $this->isValidInstagramUrl && $this->isValidTiktokUrl && $this->isValidYoutubeUrl) {
            $this->canSaveData = true;
        }else{
            $this->canSaveData = false;
        }
    }

    
}
