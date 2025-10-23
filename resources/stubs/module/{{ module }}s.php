<?php

namespace App\Livewire\{{ group }}\{{ module }};

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\{{ module }}RepositoryInterface;
use Livewire\Attributes\Title;

#[Title('{{ vietnameseName }}')]
class {{ module }}s extends Component
{
    use WithPagination;

    protected {{ module }}RepositoryInterface ${{ moduleLower }}Repository;


    public function boot({{ module }}RepositoryInterface ${{ moduleLower }}Repository)
    {
        $this->{{ moduleLower }}Repository = ${{ moduleLower }}Repository;
    }

    public function render()
    {
        ${{ moduleLower }}s = $this->{{ moduleLower }}Repository
            ->paginate(15);

        return view('livewire.{{ groupLower }}.{{ moduleKebab }}.{{ moduleKebab }}s', [
            '{{ moduleLower }}s' => ${{ moduleLower }}s,
        ]);
    }

    public function add{{ module }}()
    {
        $this->dispatch('add{{ module }}');
    }

    public function edit{{ module }}($id){
        $this->dispatch('edit{{ module }}', $id);
    }

    public function delete{{ module }}($id){
        $this->dispatch('delete{{ module }}', $id);
    }
}
