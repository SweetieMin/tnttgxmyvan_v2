<?php

namespace App\Livewire\Personnel\Catechist;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Validation\Personnel\CatechistRules;
use App\Traits\Personnel\HandlesCatechistForm;
use App\Repositories\Interfaces\CatechistRepositoryInterface;


class ActionsCatechist extends Component
{
    use HandlesCatechistForm;

    protected CatechistRepositoryInterface $catechistRepository;

    public $isEditCatechistMode = false;


    public $catechistID;

    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return CatechistRules::rules($this->catechistID);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return CatechistRules::messages();
    }

    public function boot(CatechistRepositoryInterface $catechistRepository)
    {
        $this->catechistRepository = $catechistRepository;
    }


    public function render()
    {
        return view('livewire.personnel.catechist.actions-catechist');
    }

    #[On('addCatechist')]
    public function addCatechist()
    {
        $this->resetForm();
        Flux::modal('action-catechist')->show();
    }

    public function createCatechist()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->catechistRepository->create($data);

            session()->flash('success', 'Catechist tạo thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo catechist thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.personnel.catechists', navigate: true);
    }

    #[On('editCatechist')]
    public function editCatechist($id)
    {
        $this->resetForm();

        $catechist = $this->catechistRepository->find($id);

        if ($catechist) {
            // Gán dữ liệu vào form
            $this->catechistID = $catechist->id;
            $this->isEditCatechistMode = true;
    
            

            // Hiển thị modal
            Flux::modal('action-catechist')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy catechist');
            return $this->redirectRoute('admin.personnel.catechists', navigate: true);
        }

    }

    public function updateCatechist()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->catechistRepository->update($this->catechistID,$data);

            session()->flash('success', 'Catechist cập nhật thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật catechist thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.personnel.catechists', navigate: true);
    }

    #[On('deleteCatechist')]
    public function deleteCatechist($id)
    {

        $this->resetForm();

        $catechist = $this->catechistRepository->find($id);

        if ($catechist) {
            // Gán dữ liệu vào form
            $this->catechistID = $catechist->id;
                
            // Hiển thị modal
            Flux::modal('delete-catechist')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy catechist');
            return $this->redirectRoute('admin.personnel.catechists', navigate: true);
        }

    }

    public function deleteCatechistConfirm()
    {
        try {
            $this->catechistRepository->delete($this->catechistID);

            session()->flash('success', 'Catechist xoá thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Xoá catechist thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.personnel.catechists', navigate: true);
    }
}
