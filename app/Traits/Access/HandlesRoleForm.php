<?php

namespace App\Traits\Access;


trait HandlesRoleForm
{
    protected function resetForm()
    {
        $this->reset([
            

        ]);

        $this->isEditRoleMode=false;

        $this->resetErrorBag();
    }

    
}
