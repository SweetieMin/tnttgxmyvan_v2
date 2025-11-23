<?php

namespace App\Livewire\Personnel\Spiritual;

use Flux\Flux;

use Livewire\Component;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use App\Support\User\UserHelper;
use App\Validation\Personnel\SpiritualRules;
use App\Traits\Personnel\HandlesSpiritualForm;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\SpiritualRepositoryInterface;


#[Title('Thao tác linh hướng')]
class ActionsSpiritual extends Component
{
    use HandlesSpiritualForm;

    protected SpiritualRepositoryInterface $spiritualRepository;
    protected RoleRepositoryInterface $roleRepository;

    public $isEditSpiritualMode = false;

    public $tokenQrCode, $christian_name, $name, $last_name, $full_name,  $position, $birthday, $account_code, $phone, $address, $email, $bio, $password, $token;

    public $gender = 'male';

    public $status_login = 'active';

    public $roles = [];

    public $spiritualID;

    public $picture;

    public $tab = 'profile';
    public $isShowTabParent = false;
    public $isShowTabCatechism = false;
    public $isShowTabAchievement = false;

    #[On('switchTab')]
    public function handleSwitchTab($tab)
    {
        $this->tab = $tab;
        // Lưu tab vào session
        session(['spiritual_action_tab' => $tab]);
    }

    public function selectTab($tab)
    {
        $this->tab = $tab;
        // Lưu tab vào session
        session(['spiritual_action_tab' => $tab]);
        
        // Redirect không có tham số trên URL
        $url = route('admin.personnel.spirituals.action', []) . '#section';
        
        $this->redirect($url, navigate: true);
    }

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

    public function boot(SpiritualRepositoryInterface $spiritualRepository, RoleRepositoryInterface $roleRepository)
    {
        $this->spiritualRepository = $spiritualRepository;
        $this->roleRepository = $roleRepository;
    }

    public function loadData()
    {

        $this->roles = $this->roleRepository->getRoleSpiritual();
    }


    public function mount()
    {
        // Lấy tất cả từ session
        $parameter = session('spiritual_action_parameter');
        $sessionSpiritualID = session('current_spiritual_id');
        $tab = session('spiritual_action_tab', 'profile');

        if ($parameter === 'editSpiritual') {
            if ($sessionSpiritualID) {
                $this->spiritualID = $sessionSpiritualID;
                $this->isEditSpiritualMode = true;
                $this->tab = $tab;
                $this->editSpiritual($this->spiritualID);
            } else {
                // Nếu không có ID trong session, redirect về danh sách
                $this->redirectRoute('admin.personnel.spirituals', navigate: true);
            return;
        }
        } elseif ($parameter === 'addSpiritual') {
            $this->spiritualID = null;
            $this->isEditSpiritualMode = false;
        $this->tab = $tab;
            $this->addSpiritual();
        } else {
            // Nếu không có parameter trong session, redirect về danh sách
            $this->redirectRoute('admin.personnel.spirituals', navigate: true);
            return;
        }

        $this->loadData();
    }



    public function render()
    {
        return view('livewire.personnel.spiritual.actions-spiritual');
    }

    public function backSpiritual()
    {
        // Clear session khi quay lại
        session()->forget([
            'spiritual_action_parameter',
            'current_spiritual_id',
            'spiritual_action_tab',
        ]);
        
        $this->redirectRoute('admin.personnel.spirituals', navigate: true);
    }

    #[On('addSpiritual')]
    public function addSpiritual()
    {
        $this->resetForm();

        $tokenQrCode = UserHelper::generateTokenQrCode();
        $this->tokenQrCode = $tokenQrCode['svg'];
        $this->token = $tokenQrCode['token'];
        $this->picture = "/storage/images/users/default-avatar.png";
    }

    public function createSpiritual()
    {

        $this->validate();

        $data = $this->only([
            'christian_name',
            'name',
            'last_name',
            'token',
            'account_code',
            'gender',
            'birthday',
            'status_login',
            'phone',
            'picture',
            'address',
            'email',
            'bio',
            'password',
        ]);

        try {
            $spiritual = $this->spiritualRepository->create($data);

            $spiritual->details()->create([
                'bio' => $data['bio'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'gender' => $data['gender'],
            ]);

            $spiritual->roles()->sync($this->position);

            // Lưu tất cả vào session và component state
            $this->spiritualID = $spiritual->id;
            session([
                'spiritual_action_parameter' => 'editSpiritual',
                'current_spiritual_id' => $spiritual->id,
                'spiritual_action_tab' => $this->tab,
            ]);
            $this->isEditSpiritualMode = true;

            // Emit event để child components load lại data
            $this->dispatch('loadCatechismData', userID: $spiritual->id);
            $this->dispatch('loadParentData', userID: $spiritual->id);

            Flux::toast(
                heading: 'Thành công',
                text: 'Người linh hướng được tạo thành công.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Thất bại',
                text: 'Tạo người linh hướng thất bại. ' . $e->getMessage(),
                variant: 'danger',
            );
        }

        // Redirect không có tham số trên URL
        $url = route('admin.personnel.spirituals.action', []) . '#section';
        
        $this->redirect($url, navigate: true);
    }

    public function editSpiritual($id)
    {
        $this->resetForm();

        $spiritual = $this->spiritualRepository->findSpiritualWithRelations($id);

        if ($spiritual) {
            // Gán dữ liệu vào form
            $this->spiritualID = $spiritual->id;
            $this->isEditSpiritualMode = true;

            $this->tokenQrCode = $spiritual->getTokenQrCode();
            $this->christian_name = $spiritual->christian_name;
            $this->full_name = $spiritual->full_name;
            $this->account_code = $spiritual->account_code;
            $this->gender = $spiritual->gender;
            $this->birthday = $spiritual->birthday;
            $this->status_login = $spiritual->status_login;
            $this->phone = $spiritual->details?->phone;
            $this->email = $spiritual->email;
            $this->bio = $spiritual->details?->bio;
            $this->password = $spiritual->password;
            $this->position = $spiritual->roles?->first()?->id;

            $this->roles = $spiritual->roles;
            $this->gender = $spiritual->details?->gender;
            $this->address = $spiritual->details?->address;
            $this->picture = $spiritual->details?->picture;

            // Hiển thị modal
             $this->isShowTabParent = true;
             $this->isShowTabCatechism = true;
             
            // Emit event để child components load lại data
            $this->dispatch('loadCatechismData', userID: $id);
            $this->dispatch('loadParentData', userID: $id);
             
        } else {
            // Nếu không tìm thấy
            Flux::toast(
                heading: 'Thất bại',
                text: 'Không tìm thấy spiritual',
                variant: 'danger',
            );
            return $this->redirectRoute('admin.personnel.spirituals.action', navigate: true);
        }
    }

    public function updateSpiritual()
    {

        $parts = UserHelper::separateFullName($this->full_name);
        $this->name = $parts['name'];
        $this->last_name = $parts['last_name'];

        $this->validate();

        $data = $this->only([
            'christian_name',
            'name',
            'last_name',
            'gender',
            'status_login',
            'phone',
            'picture',
            'address',
            'email',
            'bio',
        ]);

        try {
            $spiritual = $this->spiritualRepository->update($this->spiritualID, $data);

            $spiritual->details()->updateOrCreate(
                ['user_id' => $spiritual->id], // điều kiện để tìm
                [
                    'bio'     => $data['bio'],
                    'phone'   => $data['phone'],
                    'address' => $data['address'],
                    'gender'  => $data['gender'],
                ]
            );

            $spiritual->roles()->sync($this->position);

            Flux::toast(
                heading: 'Thành công',
                text: 'Spiritual cập nhật thành công.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Thất bại',
                text: 'Cập nhật spiritual thất bại. ' . $e->getMessage(),
                variant: 'danger',
            );
        }

        // Lưu tab vào session
        session(['spiritual_action_tab' => $this->tab]);
        
        // Redirect không có tham số trên URL
        $url = route('admin.personnel.spirituals.action', []) . '#section';
        
        $this->redirect($url, navigate: true);
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
            Flux::toast(
                heading: 'Thất bại',
                text: 'Không tìm thấy spiritual',
                variant: 'danger',
            );
            return $this->redirectRoute('admin.personnel.spirituals.action', navigate: true);
        }
    }

    public function deleteSpiritualConfirm()
    {
        try {
            $this->spiritualRepository->delete($this->spiritualID);

            Flux::toast(
                heading: 'Thành công',
                text: 'Spiritual xoá thành công.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Thất bại',
                text: 'Xoá spiritual thất bại. ' . $e->getMessage(),
                variant: 'danger',
            );
        }

        // Clear session khi xóa
        session()->forget([
            'spiritual_action_parameter',
            'current_spiritual_id',
            'spiritual_action_tab',
        ]);

        $this->redirectRoute('admin.personnel.spirituals', navigate: true);
    }
}
