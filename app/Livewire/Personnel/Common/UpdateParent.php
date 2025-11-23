<?php

namespace App\Livewire\Personnel\Common;

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Repositories\Interfaces\SpiritualRepositoryInterface;

class UpdateParent extends Component
{

    
    protected SpiritualRepositoryInterface $userRepository;

    public $christian_name_father, $name_father, $phone_father, $christian_name_mother, $name_mother, $phone_mother, $christian_name_god_parent, $name_god_parent, $phone_god_parent;

    public $userID;

    public function boot(SpiritualRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function mount($userID = null)
    {
        // Nếu userID được truyền từ prop, sử dụng nó
        if ($userID) {
            $this->userID = $userID;
        }

        $this->loadData();
    }

    #[On('loadParentData')]
    public function loadData($userID = null)
    {
        // Nếu userID được truyền từ event, cập nhật
        if ($userID) {
        $this->userID = $userID;
        }

        // Nếu chưa có userID, không làm gì
        if (!$this->userID) {
            return;
        }

        $user = $this->userRepository->findSpiritualWithRelations($this->userID);

        if (!$user) {
            return;
        }

        $this->christian_name_father = $user->parents?->christian_name_father;
        $this->name_father = $user->parents?->name_father;
        $this->phone_father = $user->parents?->phone_father;
        $this->christian_name_mother = $user->parents?->christian_name_mother;
        $this->name_mother = $user->parents?->name_mother;
        $this->phone_mother = $user->parents?->phone_mother;
        $this->christian_name_god_parent = $user->parents?->christian_name_god_parent;
        $this->name_god_parent = $user->parents?->name_god_parent;
        $this->phone_god_parent = $user->parents?->phone_god_parent;
    }

    // Hook để tự động load lại data khi userID thay đổi
    public function updatedUserID()
    {
        $this->loadData();
    }

    public function rules()
    {
        return [
            'christian_name_father' => 'nullable|string|max:255',
            'name_father' => 'nullable|string|max:255',


            'phone_father' => [
                'nullable',
                'string',
                'regex:/^(\+84|0)[0-9]{9,10}$/', // Định dạng số điện thoại Việt Nam
            ],

            'christian_name_mother' => 'nullable|string|max:255',
            'name_mother' => 'nullable|string|max:255',


            'phone_mother' => [
                'nullable',
                'string',
                'regex:/^(\+84|0)[0-9]{9,10}$/', // Định dạng số điện thoại Việt Nam
            ],

            'christian_name_god_parent' => 'nullable|string|max:255',
            'name_god_parent' => 'nullable|string|max:255',

            'phone_god_parent' => [
                'nullable',
                'string',
                'regex:/^(\+84|0)[0-9]{9,10}$/', // Định dạng số điện thoại Việt Nam
            ],
        ];
    }

    public function messages()
    {
        return [
            // Father
            'christian_name_father.string' => 'Tên thánh của cha phải là chuỗi ký tự.',
            'christian_name_father.max' => 'Tên thánh của cha không được vượt quá 255 ký tự.',
            'name_father.string' => 'Tên của cha phải là chuỗi ký tự.',
            'name_father.max' => 'Tên của cha không được vượt quá 255 ký tự.',
            'phone_father.regex' => 'Số điện thoại của cha phải đúng định dạng Việt Nam (0xxxxxxxxx hoặc +84xxxxxxxxx).',

            // Mother
            'christian_name_mother.string' => 'Tên thánh của mẹ phải là chuỗi ký tự.',
            'christian_name_mother.max' => 'Tên thánh của mẹ không được vượt quá 255 ký tự.',
            'name_mother.string' => 'Tên của mẹ phải là chuỗi ký tự.',
            'name_mother.max' => 'Tên của mẹ không được vượt quá 255 ký tự.',
            'phone_mother.regex' => 'Số điện thoại của mẹ phải đúng định dạng Việt Nam (0xxxxxxxxx hoặc +84xxxxxxxxx).',

            // God Parent
            'christian_name_god_parent.string' => 'Tên thánh cha/mẹ đỡ đầu phải là chuỗi ký tự.',
            'christian_name_god_parent.max' => 'Tên thánh cha/mẹ đỡ đầu không được vượt quá 255 ký tự.',
            'name_god_parent.string' => 'Tên cha/mẹ đỡ đầu phải là chuỗi ký tự.',
            'name_god_parent.max' => 'Tên cha/mẹ đỡ đầu không được vượt quá 255 ký tự.',
            'phone_god_parent.regex' => 'Số điện thoại cha/mẹ đỡ đầu phải đúng định dạng Việt Nam (0xxxxxxxxx).',
        ];
    }

    public function render()
    {
        return view('livewire.personnel.common.update-parent');
    }

    public function updateParent()
    {
        $this->validate();

        try {
            $user = $this->userRepository->findSpiritualWithRelations($this->userID);

            $user->parents()->updateOrCreate(
                ['user_id' => $user->id], // điều kiện để tìm
                [
                    'christian_name_father' => $this->christian_name_father,
                    'name_father' => $this->name_father,
                    'phone_father' => $this->phone_father,
                    'christian_name_mother' => $this->christian_name_mother,
                    'name_mother' => $this->name_mother,
                    'phone_mother' => $this->phone_mother,
                    'christian_name_god_parent' => $this->christian_name_god_parent,
                    'name_god_parent' => $this->name_god_parent,
                    'phone_god_parent' => $this->phone_god_parent,
                ]
            );

            Flux::toast(
                heading: 'Thành công',
                text: 'Cập nhật phụ huynh thành công.',
                variant: 'success',
            );
            
            // Emit event để parent component cập nhật tab mà không cần truyền ID trên URL
            $this->dispatch('switchTab', tab: 'parent');

        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Thất bại',
                text: 'Cập nhật phụ huynh thất bại. ' . $e->getMessage(),
                variant: 'danger',
            );
        }
    }
}
