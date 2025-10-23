<?php

namespace App\Livewire\Access\Role;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Livewire\Attributes\Title;

#[Title('Chức vụ')]
class Roles extends Component
{
    use WithPagination;

    protected RoleRepositoryInterface $roleRepository;


    public function boot(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function render()
    {
        $roles = $this->roleRepository
            ->paginate(15);

        return view('livewire.access.role.roles', [
            'roles' => $roles,
        ]);
    }

    public function addRole()
    {
        $this->dispatch('addRole');
    }

    public function editRole($id){
        $this->dispatch('editRole', $id);
    }

    public function deleteRole($id){
        $this->dispatch('deleteRole', $id);
    }
}
