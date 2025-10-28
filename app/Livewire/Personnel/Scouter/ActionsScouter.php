<?php

namespace App\Livewire\Personnel\Scouter;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Validation\Personnel\ScouterRules;
use App\Traits\Personnel\HandlesScouterForm;
use App\Repositories\Interfaces\ScouterRepositoryInterface;


class ActionsScouter extends Component
{
    use HandlesScouterForm;

    protected ScouterRepositoryInterface $scouterRepository;

    public $isEditScouterMode = false;


    public $scouterID;

    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return ScouterRules::rules($this->scouterID);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return ScouterRules::messages();
    }

    public function boot(ScouterRepositoryInterface $scouterRepository)
    {
        $this->scouterRepository = $scouterRepository;
    }


    public function render()
    {
        return view('livewire.personnel.scouter.actions-scouter');
    }

    #[On('addScouter')]
    public function addScouter()
    {
        $this->resetForm();
        Flux::modal('action-scouter')->show();
    }

    public function createScouter()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->scouterRepository->create($data);

            session()->flash('success', 'Scouter tạo thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo scouter thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.scouters', navigate: true);
    }

    #[On('editScouter')]
    public function editScouter($id)
    {
        $this->resetForm();

        $scouter = $this->scouterRepository->find($id);

        if ($scouter) {
            // Gán dữ liệu vào form
            $this->scouterID = $scouter->id;
            $this->isEditScouterMode = true;
    
            

            // Hiển thị modal
            Flux::modal('action-scouter')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy scouter');
            return $this->redirectRoute('admin.management.scouters', navigate: true);
        }

    }

    public function updateScouter()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->scouterRepository->update($this->scouterID,$data);

            session()->flash('success', 'Scouter cập nhật thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật scouter thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.scouters', navigate: true);
    }

    #[On('deleteScouter')]
    public function deleteScouter($id)
    {

        $this->resetForm();

        $scouter = $this->scouterRepository->find($id);

        if ($scouter) {
            // Gán dữ liệu vào form
            $this->scouterID = $scouter->id;
                
            // Hiển thị modal
            Flux::modal('delete-scouter')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy scouter');
            return $this->redirectRoute('admin.management.scouters', navigate: true);
        }

    }

    public function deleteScouterConfirm()
    {
        try {
            $this->scouterRepository->delete($this->scouterID);

            session()->flash('success', 'Scouter xoá thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Xoá scouter thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.management.scouters', navigate: true);
    }
}
