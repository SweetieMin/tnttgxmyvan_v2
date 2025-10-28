<?php

namespace App\Traits\Personnel;


trait HandlesChildrenForm
{
    protected function resetForm()
    {
        $this->reset([
            

        ]);

        $this->isEditChildrenMode=false;

        $this->resetErrorBag();
    }

    
}
