<?php

namespace App\Livewire\Personnel\Children;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Validation\Personnel\ChildrenRules;
use App\Traits\Personnel\HandlesChildrenForm;
use App\Repositories\Interfaces\ChildrenRepositoryInterface;


class ActionsChildren extends Component
{
    use HandlesChildrenForm;

    protected ChildrenRepositoryInterface $childrenRepository;

    public $isEditChildrenMode = false;


    public $childrenID;

    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return ChildrenRules::rules($this->childrenID);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return ChildrenRules::messages();
    }

    public function boot(ChildrenRepositoryInterface $childrenRepository)
    {
        $this->childrenRepository = $childrenRepository;
    }


    public function render()
    {
        return view('livewire.personnel.children.actions-children');
    }

    #[On('addChildren')]
    public function addChildren()
    {
        $this->resetForm();
        Flux::modal('action-children')->show();
    }

    public function createChildren()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->childrenRepository->create($data);

            session()->flash('success', 'Children tạo thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo children thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.childrens', navigate: true);
    }

    #[On('editChildren')]
    public function editChildren($id)
    {
        $this->resetForm();

        $children = $this->childrenRepository->find($id);

        if ($children) {
            // Gán dữ liệu vào form
            $this->childrenID = $children->id;
            $this->isEditChildrenMode = true;
    
            

            // Hiển thị modal
            Flux::modal('action-children')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy children');
            return $this->redirectRoute('admin.management.childrens', navigate: true);
        }

    }

    public function updateChildren()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->childrenRepository->update($this->childrenID,$data);

            session()->flash('success', 'Children cập nhật thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật children thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.childrens', navigate: true);
    }

    #[On('deleteChildren')]
    public function deleteChildren($id)
    {

        $this->resetForm();

        $children = $this->childrenRepository->find($id);

        if ($children) {
            // Gán dữ liệu vào form
            $this->childrenID = $children->id;
                
            // Hiển thị modal
            Flux::modal('delete-children')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy children');
            return $this->redirectRoute('admin.management.childrens', navigate: true);
        }

    }

    public function deleteChildrenConfirm()
    {
        try {
            $this->childrenRepository->delete($this->childrenID);

            session()->flash('success', 'Children xoá thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Xoá children thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.childrens', navigate: true);
    }
}
