<?php

namespace App\Traits\Personnel;


trait HandlesChildrenInactiveForm
{
    protected function resetForm()
    {
        $this->reset([
            

        ]);

        $this->isEditChildrenInactiveMode=false;

        $this->resetErrorBag();
    }

    
}
