<?php

namespace App\Livewire\Personnel\Spiritual;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Validation\Personnel\SpiritualRules;
use App\Traits\Personnel\HandlesSpiritualForm;
use App\Repositories\Interfaces\SpiritualRepositoryInterface;


class ActionsSpiritual extends Component
{
    use HandlesSpiritualForm;

    protected SpiritualRepositoryInterface $spiritualRepository;

    public $isEditSpiritualMode = false;


    public $spiritualID;

    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return SpiritualRules::rules($this->spiritualID);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return SpiritualRules::messages();
    }

    public function boot(SpiritualRepositoryInterface $spiritualRepository)
    {
        $this->spiritualRepository = $spiritualRepository;
    }


    public function render()
    {
        return view('livewire.personnel.spiritual.actions-spiritual');
    }

    public function backSpiritual()
    {
        $this->redirectRoute('admin.personnel.spirituals', navigate: true);
    }

    #[On('addSpiritual')]
    public function addSpiritual()
    {
        $this->resetForm();
        Flux::modal('action-spiritual')->show();
    }

    public function createSpiritual()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->spiritualRepository->create($data);

            session()->flash('success', 'Spiritual tạo thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo spiritual thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.personnel.spirituals', navigate: true);
    }

    #[On('editSpiritual')]
    public function editSpiritual($id)
    {
        $this->resetForm();

        $spiritual = $this->spiritualRepository->find($id);

        if ($spiritual) {
            // Gán dữ liệu vào form
            $this->spiritualID = $spiritual->id;
            $this->isEditSpiritualMode = true;
    
            

            // Hiển thị modal
            Flux::modal('action-spiritual')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy spiritual');
            return $this->redirectRoute('admin.personnel.spirituals', navigate: true);
        }

    }

    public function updateSpiritual()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->spiritualRepository->update($this->spiritualID,$data);

            session()->flash('success', 'Spiritual cập nhật thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật spiritual thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.personnel.spirituals', navigate: true);
    }

    #[On('deleteSpiritual')]
    public function deleteSpiritual($id)
    {

        $this->resetForm();

        $spiritual = $this->spiritualRepository->find($id);

        if ($spiritual) {
            // Gán dữ liệu vào form
            $this->spiritualID = $spiritual->id;
                
            // Hiển thị modal
            Flux::modal('delete-spiritual')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy spiritual');
            return $this->redirectRoute('admin.personnel.spirituals', navigate: true);
        }

    }

    public function deleteSpiritualConfirm()
    {
        try {
            $this->spiritualRepository->delete($this->spiritualID);

            session()->flash('success', 'Spiritual xoá thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Xoá spiritual thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.personnel.spirituals', navigate: true);
    }
}
