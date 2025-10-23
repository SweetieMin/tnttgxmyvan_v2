<?php

namespace App\Livewire\{{ group }}\{{ module }};

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Validation\{{ group }}\{{ module }}Rules;
use App\Traits\{{ group }}\Handles{{ module }}Form;
use App\Repositories\Interfaces\{{ module }}RepositoryInterface;


class Actions{{ module }} extends Component
{
    use Handles{{ module }}Form;

    protected {{ module }}RepositoryInterface ${{ moduleLower }}Repository;

    public $isEdit{{ module }}Mode = false;


    public ${{ moduleLower }}ID;

    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return {{ module }}Rules::rules($this->{{ moduleLower }}ID);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return {{ module }}Rules::messages();
    }

    public function boot({{ module }}RepositoryInterface ${{ moduleLower }}Repository)
    {
        $this->{{ moduleLower }}Repository = ${{ moduleLower }}Repository;
    }


    public function render()
    {
        return view('livewire.{{ groupLower }}.{{ moduleKebab }}.actions-{{ moduleKebab }}');
    }

    #[On('add{{ module }}')]
    public function add{{ module }}()
    {
        $this->resetForm();
        Flux::modal('action-{{ moduleKebab }}')->show();
    }

    public function create{{ module }}()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->{{ moduleLower }}Repository->create($data);

            session()->flash('success', '{{ vietnameseName }} tạo thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo {{ vietnameseName }} thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.{{ group }}.{{ moduleKebab }}s', navigate: true);
    }

    #[On('edit{{ module }}')]
    public function edit{{ module }}($id)
    {
        $this->resetForm();

        ${{ moduleLower }} = $this->{{ moduleLower }}Repository->find($id);

        if (${{ moduleLower }}) {
            // Gán dữ liệu vào form
            $this->{{ moduleLower }}ID = ${{ moduleLower }}->id;
            $this->isEdit{{ module }}Mode = true;
    
            

            // Hiển thị modal
            Flux::modal('action-{{ moduleKebab }}')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy {{ vietnameseName }}');
            return $this->redirectRoute('admin.{{ group }}.{{ moduleKebab }}s', navigate: true);
        }

    }

    public function update{{ module }}()
    {
        $this->validate();

        $data = $this->only([
            
        ]);

        try {
            $this->{{ moduleLower }}Repository->update($this->{{ moduleLower }}ID,$data);

            session()->flash('success', '{{ vietnameseName }} cập nhật thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật {{ vietnameseName }} thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.{{ group }}.{{ moduleKebab }}s', navigate: true);
    }

    #[On('delete{{ module }}')]
    public function delete{{ module }}($id)
    {

        $this->resetForm();

        ${{ moduleLower }} = $this->{{ moduleLower }}Repository->find($id);

        if (${{ moduleLower }}) {
            // Gán dữ liệu vào form
            $this->{{ moduleLower }}ID = ${{ moduleLower }}->id;
                
            // Hiển thị modal
            Flux::modal('delete-{{ moduleKebab }}')->show();
        } else {
            // Nếu không tìm thấy
            session()->flash('error', 'Không tìm thấy {{ vietnameseName }}');
            return $this->redirectRoute('admin.{{ group }}.{{ moduleKebab }}s', navigate: true);
        }

    }

    public function delete{{ module }}Confirm()
    {
        try {
            $this->{{ moduleLower }}Repository->delete($this->{{ moduleLower }}ID);

            session()->flash('success', '{{ vietnameseName }} xoá thành công.');

            
        } catch (\Exception $e) {
            session()->flash('error', 'Xoá {{ vietnameseName }} thất bại.' . $e->getMessage());
        }
        
        $this->redirectRoute('admin.{{ group }}.{{ moduleKebab }}s', navigate: true);
    }
}
