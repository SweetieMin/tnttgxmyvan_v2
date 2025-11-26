<?php

namespace App\Traits\Settings;

use Flux\Flux;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Storage;

trait HandlesLogoFaviconSettingForm
{
    public $logo;

    public $existLogo;

    public $favicon;

    public $existFavicon;

    public function removeLogo()
    {
        $this->logo->delete();
        $this->logo = null;
    }

    protected function checkImageAspectRatio($file, $type)
    {
        [$width, $height] = getimagesize($file->getRealPath());

        if ($type == 'logo') {
            // Chỉ chấp nhận hình ngang (width > height)
            if ($width <= $height) {
                $this->addError('logo', 'Logo phải là hình ngang (width > height).');
                $this->logo->delete();
                $this->logo = null;
                return false;
            }

            // Nếu hợp lệ → xoá lỗi cũ
            $this->resetErrorBag('logo');
            return true;
        }

        if ($type == 'favicon') {
            if ($width != $height) {
                $this->addError('favicon', 'Favicon phải là hình vuông (width = height).');
                $this->favicon->delete();
                $this->favicon = null;
                return;
            }

            $this->resetErrorBag('favicon');

            return;
        }
    }

    public function updatedLogo($file)
    {
        $this->checkImageAspectRatio($file, 'logo');
    }

    public function updatedFavicon($file)
    {
        $this->checkImageAspectRatio($file, 'favicon');
    }

    public function saveLogo()
    {
        $this->validate([
            'logo' => 'required|image|max:10240',
        ], [
            'logo.required' => 'Logo là bắt buộc.',
            'logo.image' => 'Logo phải là hình ảnh.',
            'logo.max' => 'Logo không được vượt quá 10MB.',
        ]);

        $logo = $this->logo;

        // Kiểm tra tỉ lệ logo (phải là hình ngang)
        [$width, $height] = getimagesize($logo->getRealPath());
        if ($width <= $height) {
            $this->addError('logo', 'Logo phải là hình ngang (width > height).');
            $this->logo->delete();
            $this->logo = null;
            return;
        }

        // Tên file
        $filename = 'LOGO-' . uniqid() . '.' . $logo->getClientOriginalExtension();

        // Lưu file vào storage/app/public/images/sites
        $logo->storeAs('images/sites', $filename, 'public');

        // ĐƯỜNG DẪN LƯU TRONG DB (KHÔNG CÓ /storage/)
        $newPath = 'images/sites/' . $filename;

        // Lấy hoặc tạo record general setting
        $generalSetting = GeneralSetting::firstOrCreate([]);

        // Xoá logo cũ nếu có
        if ($generalSetting->site_logo && Storage::disk('public')->exists($generalSetting->site_logo)) {
            Storage::disk('public')->delete($generalSetting->site_logo);
        }

        // Lưu logo mới vào DB
        $generalSetting->site_logo = $newPath;
        $generalSetting->save();

        // Xoá file Livewire temp
        $this->logo->delete();
        $this->logo = null;

        // Thông báo thành công
        Flux::toast(
            heading: 'Thành công',
            text: 'Cập nhật Logo thành công.',
            variant: 'success',
        );

        // Quay về đúng tab
        $this->redirectRoute('admin.settings.general', ['tab' => 'logo-favicon'], navigate: true);
    }


    public function saveFavicon()
    {
        $this->validate([
            'favicon' => 'required|image|max:10240',
        ]);

        $favicon = $this->favicon;

        // Tên file
        $filename = 'FAVICON-' . uniqid() . '.' . $favicon->getClientOriginalExtension();

        // Lưu file vào storage/app/public/images/sites
        $favicon->storeAs('images/sites', $filename, 'public');

        // ĐƯỜNG DẪN LƯU TRONG DB (KHÔNG CÓ /storage/)
        $newPath = 'images/sites/' . $filename;

        // Lấy hoặc tạo
        $generalSetting = GeneralSetting::firstOrCreate([]);

        // Xoá file cũ nếu tồn tại
        if ($generalSetting->site_favicon && Storage::disk('public')->exists($generalSetting->site_favicon)) {
            Storage::disk('public')->delete($generalSetting->site_favicon);
        }

        // Lưu favicon mới
        $generalSetting->site_favicon = $newPath;
        $generalSetting->save();

        // Xoá file Livewire temp
        $this->favicon->delete();
        $this->favicon = null;

        Flux::toast(
            heading: 'Thành công',
            text: 'Cập nhật Favicon thành công.',
            variant: 'success',
        );

        $this->redirectRoute('admin.settings.general', ['tab' => 'logo-favicon'], navigate: true);
    }
}
