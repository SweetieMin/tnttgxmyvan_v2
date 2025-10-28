<?php

namespace App\Traits\Personnel;


trait HandlesScouterForm
{
    protected function resetForm()
    {
        $this->reset([
            

        ]);

        $this->isEditScouterMode=false;

        $this->resetErrorBag();
    }

    
}
