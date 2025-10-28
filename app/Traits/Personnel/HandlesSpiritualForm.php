<?php

namespace App\Traits\Personnel;


trait HandlesSpiritualForm
{
    protected function resetForm()
    {
        $this->reset([
            

        ]);

        $this->isEditSpiritualMode=false;

        $this->resetErrorBag();
    }

    
}
