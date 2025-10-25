<?php

namespace App\Livewire\Access\Role;

use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Repositories\Interfaces\RoleRepositoryInterface;

#[Title('Chức vụ')]
class Roles extends Component
{
    use WithPagination;

    protected RoleRepositoryInterface $roleRepository;

    public $roleID;

    public $page = 1; // 🔹 Tên trùng với query string

    protected $queryString = [
        'page' => ['except' => 1, 'as' => 'page', 'keep' => true],
    ];

    public function mount()
    {
        $this->page = request()->query('page', 1);
    }


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
        $this->redirectRoute('admin.access.roles.action', ['parameter' => 'addRole'], navigate: true);
    }

    public function editRole($id)
    {
        $this->redirectRoute('admin.access.roles.action', ['parameter' => 'editRole', 'roleID' => $id, 'page' =>  $this->page], navigate: true);
    }

    public function deleteRole($id)
    {

        $role = $this->roleRepository->find($id);

        if ($role) {
            // Gán dữ liệu vào form
            $this->roleID = $role->id;

            // Hiển thị modal
            Flux::modal('delete-role')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy role');
            return $this->redirectRoute('admin.access.roles', navigate: true);
        }
    }

    public function deleteRoleConfirm()
    {
        try {
            $this->roleRepository->delete($this->roleID);

            session()->flash('success', 'Role xoá thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Xoá role thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.access.roles', navigate: true);
    }

    public function updateRolesOrdering($ids)
    {
        $success = $this->roleRepository->updateOrdering($ids);

        if ($success) {
            session()->flash('success', 'Sắp xếp chương trình học thành công!');
        } else {
            session()->flash('error', 'Sắp xếp thất bại! Vui lòng thử lại.');
        }

        $this->redirectRoute('admin.access.roles', navigate: true);
    }
}
