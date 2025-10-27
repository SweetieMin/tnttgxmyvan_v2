<?php

namespace App\Traits\Management;


trait HandlesRegulationForm
{
    protected function resetForm()
    {
        $this->reset([
            

        ]);

        $this->isEditRegulationMode=false;

        $this->resetErrorBag();
    }

    
}
