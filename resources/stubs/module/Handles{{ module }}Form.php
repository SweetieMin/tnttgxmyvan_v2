<?php

namespace App\Traits\{{ group }};


trait Handles{{ module }}Form
{
    protected function resetForm()
    {
        $this->reset([
            

        ]);

        $this->isEdit{{ module }}Mode=false;

        $this->resetErrorBag();
    }

    
}
