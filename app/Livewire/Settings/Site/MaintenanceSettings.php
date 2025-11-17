<?php

namespace App\Livewire\Settings\Site;

use Flux\Flux;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use App\Models\MaintenanceSetting;
use Illuminate\Support\Facades\Artisan;
use App\Validation\Setting\MaintenanceRules;

#[Title('Cấu hình bảo trì')]
class MaintenanceSettings extends Component
{

    public $is_maintenance = false;
    public $secret_key = '';
    public $message = 'Cập nhật phiên bản mới và vá lỗi';
    public $start_at = '';
    public $end_at = '';

    public function mount()
    {

        $maintenanceSetting = MaintenanceSetting::first();
        if (!$maintenanceSetting) {
            return;
        }

        $this->is_maintenance = $maintenanceSetting->is_maintenance;
        $this->secret_key = $maintenanceSetting->secret_key;
        $this->message = $maintenanceSetting->message;
        $this->start_at = $maintenanceSetting->start_at;
        $this->end_at = $maintenanceSetting->end_at;
    }

    public function updatedIsMaintenance($value)
    {
        $this->is_maintenance = $value;
        if ($value) {
            $this->secret_key = Str::orderedUuid()->toString();
        }
    }

    public function render()
    {
        return view('livewire.settings.site.maintenance-settings');
    }

    public function rules()
    {
        return MaintenanceRules::rules($this->is_maintenance);
    }

    public function messages()
    {
        return MaintenanceRules::messages();
    }

    public function updateMaintenanceSettings()
    {
        $this->validate();

        $maintenanceSettings = MaintenanceSetting::first();

        if (!$maintenanceSettings) {
            $maintenanceSettings = new MaintenanceSetting();
        }

        $maintenanceSettings->is_maintenance = $this->is_maintenance;
        $maintenanceSettings->secret_key = $this->secret_key;
        $maintenanceSettings->message = $this->message;
        $maintenanceSettings->start_at = $this->start_at;
        $maintenanceSettings->end_at = $this->end_at;
        $maintenanceSettings->save();

        if ($this->is_maintenance) {
            Flux::modal('maintenance-confirm')->show();
        } else {
            Artisan::call("up");
            Flux::toast(
                heading: 'Thành công',
                text: 'Hệ thống đã được online.',
                variant: 'success',
            );
            $this->redirectRoute('admin.settings.maintenance', navigate: true);
            if ($maintenanceSettings)
            {
                $maintenanceSettings->delete();
            }
        }

    }

    public function enableMaintenanceConfirm()
    {
        Artisan::call("down --secret={$this->secret_key}");
        $this->redirect("/{$this->secret_key}", navigate: true);
    }

}
