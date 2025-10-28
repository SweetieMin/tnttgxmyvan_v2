<?php

namespace App\Traits\Personnel;


trait HandlesSectorForm
{
    protected function resetForm()
    {
        $this->reset([
            

        ]);

        $this->isEditSectorMode=false;

        $this->resetErrorBag();
    }

    
}
