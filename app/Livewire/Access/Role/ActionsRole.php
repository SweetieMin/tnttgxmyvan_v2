<?php

namespace App\Livewire\Access\Role;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Validation\Access\RoleRules;
use App\Traits\Access\HandlesRoleForm;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Livewire\Attributes\Title;

#[Title('Thêm chức vụ')]
class ActionsRole extends Component
{
    use HandlesRoleForm;

    protected RoleRepositoryInterface $roleRepository;

    public $isEditRoleMode = false;

    public $name;
    public $description;
    public array $hierarchies = [];

    public $roleID;

    public $rolesExceptCurrentRole;

    public $page = 1;

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

    public function loadRole()
    {
        $this->rolesExceptCurrentRole = $this->roleRepository->getRoleExceptCurrentRole($this->roleID);
    }

    public function boot(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function mount(): void
    {
        $this->page = request()->input('page', 1);
    
        $parameter = request()->input('parameter');
        $roleID    = request()->input('roleID');
    
        if (!$parameter) {
            return;
        }
    
        $this->isEditRoleMode = $parameter === 'editRole';
        $this->roleID = $this->isEditRoleMode ? $roleID : null;
    
        if ($this->isEditRoleMode) {
            $this->editRole($this->roleID);
        } else {
            $this->addRole();
        }
    
        $this->loadRole($this->roleID);
    }

    public function render()
    {
        return view('livewire.access.role.actions-role');
    }

    public function addRole()
    {
        $this->resetForm();
    }

    public function backRole()
    {
        $this->redirectRoute('admin.access.roles', navigate: true);
    }


    public function createRole()
    {
        $data = $this->only(['name', 'description']);
    
        try {
            $role = $this->roleRepository->create($data);
    
            if (!empty($this->hierarchies)) {
                $role->roleHierarchies()->sync($this->hierarchies);
            }
    
            session()->flash('success', 'Role tạo thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo role thất bại.' . $e->getMessage());
        }
    
        $this->redirectRoute('admin.access.roles', ['page' => $this->page], navigate: true);
    }

    public function editRole($id)
    {
        $this->resetForm();

        $role = $this->roleRepository->find($id);

        if ($role) {
            // Gán dữ liệu vào form
            $this->roleID = $role->id;
            $this->isEditRoleMode = true;

            $this->name = $role->name;
            $this->description = $role->description;

            $this->hierarchies = $role->subRoles()->pluck('roles.id')->toArray();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy role');
            return $this->redirectRoute('admin.access.roles', navigate: true);
        }
    }

    public function updateRole()
    {
        //$this->validate();

        $data = $this->only([
            'name',
            'description'
        ]);

        try {
            $role = $this->roleRepository->update($this->roleID, $data);

            $role = $this->roleRepository->find($this->roleID);

            if (!empty($this->hierarchies)) {
                $role->roleHierarchies()->sync($this->hierarchies);
            }

            session()->flash('success', 'Role cập nhật thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật role thất bại.' . $e->getMessage());
        }

        $this->redirectRoute('admin.access.roles', ['page' => $this->page], navigate: true);
    }

}
