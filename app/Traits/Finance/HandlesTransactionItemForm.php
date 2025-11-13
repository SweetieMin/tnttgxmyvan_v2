<?php

namespace App\Traits\Finance;


trait HandlesTransactionItemForm
{
    protected function resetForm()
    {
        $this->reset([
            'name',
            'transaction_itemID',
            'description',
        ]);

        $this->isEditTransactionItemMode = false;
        $this->is_system = false;

        $this->resetErrorBag();
    }

    
}
