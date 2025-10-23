<?php

namespace App\Traits\Management;


trait HandlesProgramForm
{
    protected function resetForm()
    {
        $this->reset([
            

        ]);

        $this->isEditProgramMode=false;

        $this->resetErrorBag();
    }

    
}
