<?php

namespace App\Livewire\Management\CourseSector;

use Livewire\Component;
use Livewire\Attributes\Title;


#[Title('Lớp GL & Ngành SH')]
class CourseSectorList extends Component
{
    public function render()
    {
        return view('livewire.management.course-sector.course-sector-list');
    }
}
