<?php

namespace App\Http\Controllers\Management;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\ResponseToastHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Management\RegulationRequest;
use App\Repositories\Eloquent\RegulationRepository;
use App\Repositories\Eloquent\AcademicYearRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Models\RegulationRole;


class RegulationController extends Controller
{
    protected $regulationRepository;
    protected $academicYearRepository;
    protected $roleRepository;

    public function __construct(RegulationRepository $regulationRepository, AcademicYearRepository $academicYearRepository, RoleRepository $roleRepository)
    {
        $this->regulationRepository = $regulationRepository;
        $this->academicYearRepository = $academicYearRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $academicYearId = $request->get('academic_year_id');

        // Tìm niên khóa hiện tại nếu không có filter
        if (!$academicYearId) {
            $currentYear = date('Y');
            $currentAcademicYear = $this->academicYearRepository->getModel()
                ->where('name', 'like', $currentYear . '%')
                ->first();

            if ($currentAcademicYear) {
                $academicYearId = $currentAcademicYear->id;
            }
        }

        // Query regulations với filter
        $query = $this->regulationRepository->getModel()->with(['academicYear', 'regulationRoles.role']);
        if ($academicYearId) {
            $query->where('academic_year_id', $academicYearId);
        }

        $regulations = $query->orderBy('ordering', 'asc')->paginate(20);
        $academicYears = $this->academicYearRepository->all();

        return Inertia::render('management/regulation/index', [
            'regulations' => $regulations,
            'academicYears' => $academicYears,
            'currentAcademicYearId' => $academicYearId,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $academicYears = $this->academicYearRepository->all();
        $allRoles = $this->roleRepository->all();
        
        // Lấy academic_year_id từ query parameter hoặc tìm niên khóa hiện tại
        $academicYearId = $request->get('academic_year_id');
        if (!$academicYearId) {
            $currentYear = date('Y');
            $currentAcademicYear = $this->academicYearRepository->getModel()
                ->where('name', 'like', $currentYear . '%')
                ->first();
            if ($currentAcademicYear) {
                $academicYearId = $currentAcademicYear->id;
            }
        }

        return Inertia::render('management/regulation/actions-regulation', [
            'academicYears' => $academicYears,
            'allRoles' => $allRoles,
            'currentAcademicYearId' => $academicYearId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegulationRequest $request)
    {
        $data = $request->all();
        $roleIds = $data['role_ids'] ?? [];
        unset($data['role_ids']);

        $regulation = $this->regulationRepository->create($data);

        // Lưu RegulationRole
        if (!empty($roleIds)) {
            foreach ($roleIds as $roleId) {
                RegulationRole::create([
                    'regulation_id' => $regulation->id,
                    'role_id' => $roleId,
                ]);
            }
        }

        // Redirect về niên khóa hiện tại (đang ongoing)
        $academicYearId = $request->academic_year_id ?? null;

        return ResponseToastHelper::successRedirect(
            'management.regulations.index',
            'Nội quy ":description" đã được tạo thành công.',
            ['description' => $request->description],
            ['academic_year_id' => $academicYearId]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $regulation = $this->regulationRepository->find($id);
        $academicYears = $this->academicYearRepository->all();
        $allRoles = $this->roleRepository->all();
        
        // Lấy RegulationRole hiện tại
        $regulationRoles = RegulationRole::where('regulation_id', $id)
            ->with('role')
            ->get()
            ->map(function ($item) {
                return [
                    'role_id' => $item->role_id,
                    'role' => $item->role
                ];
            });

            return Inertia::render('management/regulation/actions-regulation', [
                'regulation' => $regulation,
                'academicYears' => $academicYears,
                'allRoles' => $allRoles,
                'regulationRoles' => $regulationRoles,
                'currentAcademicYearId' => $regulation->academic_year_id,
            ]);
            
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegulationRequest $request, string $id)
    {
        $data = $request->all();
        $roleIds = $data['role_ids'] ?? [];
        unset($data['role_ids']);

        $this->regulationRepository->update($id, $data);

        // Xóa RegulationRole cũ
        RegulationRole::where('regulation_id', $id)->delete();

        // Lưu RegulationRole mới
        if (!empty($roleIds)) {
            foreach ($roleIds as $roleId) {
                RegulationRole::create([
                    'regulation_id' => $id,
                    'role_id' => $roleId,
                ]);
            }
        }

        // Redirect về niên khóa hiện tại (đang ongoing)
        $academicYearId = $request->academic_year_id ?? null;

        return ResponseToastHelper::successRedirect(
            'management.regulations.index',
            'Nội quy ":description" đã được cập nhật thành công.',
            ['description' => $request->description],
            ['academic_year_id' => $academicYearId]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $regulation = $this->regulationRepository->find($id);
        $description = $regulation->description;
        $academicYearId = $regulation->academic_year_id ?? null;

        $this->regulationRepository->delete($id);

        return ResponseToastHelper::successRedirect(
            'management.regulations.index',
            'Nội quy ":description" đã được xóa thành công.',
            ['description' => $description],
            ['academic_year_id' => $academicYearId]
        );
    }
}
