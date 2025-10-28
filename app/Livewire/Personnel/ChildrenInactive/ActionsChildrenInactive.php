<?php

namespace App\Livewire\Personnel\ChildrenInactive;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Validation\Personnel\ChildrenInactiveRules;
use App\Traits\Personnel\HandlesChildrenInactiveForm;
use App\Repositories\Interfaces\ChildrenInactiveRepositoryInterface;


class ActionsChildrenInactive extends Component
{
    use HandlesChildrenInactiveForm;

    protected ChildrenInactiveRepositoryInterface $children_inactiveRepository;

    public $isEditChildrenInactiveMode = false;


    public $children_inactiveID;

    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return ChildrenInactiveRules::rules($this->children_inactiveID);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return ChildrenInactiveRules::messages();
    }

    public function boot(ChildrenInactiveRepositoryInterface $children_inactiveRepository)
    {
        $this->children_inactiveRepository = $children_inactiveRepository;
    }


    public function render()
    {
        return view('livewire.personnel.children-inactive.actions-children-inactive');
    }

    #[On('addChildrenInactive')]
    public function addChildrenInactive()
    {
        $this->resetForm();
        Flux::modal('action-children-inactive')->show();
    }

    public function createChildrenInactive()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->children_inactiveRepository->create($data);

            session()->flash('success', 'ChildrenInactive tạo thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo children_inactive thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.children-inactives', navigate: true);
    }

    #[On('editChildrenInactive')]
    public function editChildrenInactive($id)
    {
        $this->resetForm();

        $children_inactive = $this->children_inactiveRepository->find($id);

        if ($children_inactive) {
            // Gán dữ liệu vào form
            $this->children_inactiveID = $children_inactive->id;
            $this->isEditChildrenInactiveMode = true;
    
            

            // Hiển thị modal
            Flux::modal('action-children-inactive')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy children_inactive');
            return $this->redirectRoute('admin.management.children-inactives', navigate: true);
        }

    }

    public function updateChildrenInactive()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->children_inactiveRepository->update($this->children_inactiveID,$data);

            session()->flash('success', 'ChildrenInactive cập nhật thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật children_inactive thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.children-inactives', navigate: true);
    }

    #[On('deleteChildrenInactive')]
    public function deleteChildrenInactive($id)
    {

        $this->resetForm();

        $children_inactive = $this->children_inactiveRepository->find($id);

        if ($children_inactive) {
            // Gán dữ liệu vào form
            $this->children_inactiveID = $children_inactive->id;
                
            // Hiển thị modal
            Flux::modal('delete-children-inactive')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy children_inactive');
            return $this->redirectRoute('admin.management.children-inactives', navigate: true);
        }

    }

    public function deleteChildrenInactiveConfirm()
    {
        try {
            $this->children_inactiveRepository->delete($this->children_inactiveID);

            session()->flash('success', 'ChildrenInactive xoá thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Xoá children_inactive thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.children-inactives', navigate: true);
    }
}
