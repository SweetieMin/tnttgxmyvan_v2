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

    public $search = '';

    public $page = 1; // 🔹 Tên trùng với query string

    public $perPage = 25;

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
        $perPage = $this->perPage === '' || $this->perPage === null ? null : (int) $this->perPage;

        $roles = $this->roleRepository
            ->roleWithSearchAndPage($this->search, $perPage);

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
            Flux::toast(
                heading: 'Đã xảy ra lỗi!',
                text: 'Không tìm thấy chức vụ.',
                variant: 'error',
            );
            return $this->redirectRoute('admin.access.roles', navigate: true);
        }
    }

    public function deleteRoleConfirm()
    {
        try {
            $this->roleRepository->delete($this->roleID);

            Flux::toast(
                heading: 'Thành công',
                text: 'Chức vụ đã được xóa thành công.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Đã xảy ra lỗi!',
                text: 'Không thể xóa chức vụ. ' . (app()->environment('local') ? $e->getMessage() : 'Vui lòng thử lại sau.'),
                variant: 'error',
            );
        }

        $this->redirectRoute('admin.access.roles', navigate: true);
    }

    public function updateRolesOrdering($ids)
    {



        try {
            $this->roleRepository->updateOrdering($ids);


            Flux::toast(
                heading: 'Thành công',
                text: 'Thứ tự chức vụ đã được cập nhật.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Đã xảy ra lỗi!',
                text: 'Lỗi khi cập nhật thứ tự: ' . (app()->environment('local') ? $e->getMessage() : 'Vui lòng thử lại sau.'),
                variant: 'error',
            );
        }

        $this->redirectRoute('admin.access.roles', ['page' => $this->page], navigate: true);
    }
}
