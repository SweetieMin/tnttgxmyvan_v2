<?php

namespace App\Livewire\Management\Program;

use Flux\Flux;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Validation\Management\ProgramRules;
use App\Traits\Management\HandlesProgramForm;
use App\Repositories\Interfaces\ProgramRepositoryInterface;


class ActionsProgram extends Component
{
    use HandlesProgramForm;

    protected ProgramRepositoryInterface $programRepository;

    public $isEditProgramMode = false;

    public string $course;

    public string $sector;

    public string $description = '';

    public $programID;

    /**
     * Quy tắc xác thực
     */
    protected function rules()
    {
        return ProgramRules::rules($this->programID);
    }

    /**
     * Thông báo lỗi xác thực (tiếng Việt)
     */
    protected function messages()
    {
        return ProgramRules::messages();
    }

    public function boot(ProgramRepositoryInterface $programRepository)
    {
        $this->programRepository = $programRepository;
    }


    public function render()
    {
        return view('livewire.management.program.actions-program');
    }

    #[On('addProgram')]
    public function addProgram()
    {
        $this->resetForm();
        Flux::modal('action-program')->show();
    }

    public function createProgram()
    {
        $this->validate();

        $data = $this->only([
            'course',
            'sector',
            'description',
        ]);

        try {
            $this->programRepository->create($data);

            Flux::toast(
                heading: 'Thành công',
                text: 'Chương trình học tạo thành công.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Thất bại',
                text: 'Tạo chương trình học thất bại. ' . $e->getMessage(),
                variant: 'danger',
            );
        }

        $this->redirectRoute('admin.management.programs', navigate: true);
    }

    #[On('editProgram')]
    public function editProgram($id)
    {
        $this->resetForm();
        $this->canSaveData = true;
        $program = $this->programRepository->find($id);

        if ($program) {
            // Gán dữ liệu vào form
            $this->programID = $program->id;
            $this->isEditProgramMode = true;

            $this->course = $program->course;
            $this->sector = $program->sector;
            $this->description = $program->description;

            // Hiển thị modal
            Flux::modal('action-program')->show();
        } else {
            // Nếu không tìm thấy
            Flux::toast(
                heading: 'Lỗi',
                text: 'Không tìm thấy chương trình học.',
                variant: 'danger',
            );
            return $this->redirectRoute('admin.management.programs', navigate: true);
        }
    }

    public function updateProgram()
    {
        $this->validate();

        $data = $this->only([
            'course',
            'sector',
            'description',
        ]);

        try {
            $this->programRepository->update($this->programID, $data);

            Flux::toast(
                heading: 'Thành công',
                text: 'Chương trình học cập nhật thành công.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Thất bại',
                text: 'Cập nhật chương trình học thất bại. ' . $e->getMessage(),
                variant: 'danger',
            );
        }

        $this->redirectRoute('admin.management.programs', navigate: true);
    }

    #[On('deleteProgram')]
    public function deleteProgram($id)
    {

        $this->resetForm();

        $program = $this->programRepository->find($id);

        if ($program) {
            // Gán dữ liệu vào form
            $this->programID = $program->id;

            // Hiển thị modal
            Flux::modal('delete-program')->show();
        } else {
            // Nếu không tìm thấy
            Flux::toast(
                heading: 'Lỗi',
                text: 'Không tìm thấy chương trình học.',
                variant: 'danger',
            );
            return $this->redirectRoute('admin.management.programs', navigate: true);
        }
    }

    public function deleteProgramConfirm()
    {
        try {
            $this->programRepository->delete($this->programID);

            Flux::toast(
                heading: 'Thành công',
                text: 'Chương trình học xoá thành công.',
                variant: 'success',
            );
        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Thất bại',
                text: 'Xoá chương trình học thất bại. ' . $e->getMessage(),
                variant: 'danger',
            );
        }

        $this->redirectRoute('admin.management.programs', navigate: true);
    }


}
