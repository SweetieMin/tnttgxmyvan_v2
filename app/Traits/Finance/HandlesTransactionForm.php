<?php

namespace App\Traits\Finance;


trait HandlesTransactionForm
{
    protected function resetForm()
    {
        $this->reset([
            

        ]);

        $this->isEditTransactionMode=false;

        $this->resetErrorBag();
    }

    
}
