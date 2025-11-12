<?php

namespace App\Traits\Management;


trait HandlesRegulationForm
{
    protected function resetForm()
    {
        $this->reset([
            'points',
            'description'
        ]);

        $this->isEditRegulationMode=false;

        $this->resetErrorBag();
    }

    
}
