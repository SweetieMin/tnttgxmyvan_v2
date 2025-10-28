<?php

namespace App\Traits\Personnel;


trait HandlesCatechistForm
{
    protected function resetForm()
    {
        $this->reset([
            

        ]);

        $this->isEditCatechistMode=false;

        $this->resetErrorBag();
    }

    
}
