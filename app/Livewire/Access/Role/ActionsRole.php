<?php

namespace App\Livewire\Access\Role;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Validation\Access\RoleRules;
use App\Traits\Access\HandlesRoleForm;
use App\Repositories\Interfaces\RoleRepositoryInterface;


class ActionsRole extends Component
{
    use HandlesRoleForm;

    protected RoleRepositoryInterface $roleRepository;

    public $isEditRoleMode = false;


    public $roleID;

    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return RoleRules::rules($this->roleID);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return RoleRules::messages();
    }

    public function boot(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }


    public function render()
    {
        return view('livewire.access.role.actions-role');
    }

    #[On('addRole')]
    public function addRole()
    {
        $this->resetForm();
        Flux::modal('action-role')->show();
    }

    public function createRole()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->roleRepository->create($data);

            session()->flash('success', 'Role tạo thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo role thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.access.roles', navigate: true);
    }

    #[On('editRole')]
    public function editRole($id)
    {
        $this->resetForm();

        $role = $this->roleRepository->find($id);

        if ($role) {
            // Gán dữ liệu vào form
            $this->roleID = $role->id;
            $this->isEditRoleMode = true;
    
            

            // Hiển thị modal
            Flux::modal('action-role')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy role');
            return $this->redirectRoute('admin.access.roles', navigate: true);
        }

    }

    public function updateRole()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->roleRepository->update($this->roleID,$data);

            session()->flash('success', 'Role cập nhật thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật role thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.access.roles', navigate: true);
    }

    #[On('deleteRole')]
    public function deleteRole($id)
    {

        $this->resetForm();

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
}
